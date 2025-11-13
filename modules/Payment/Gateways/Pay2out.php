<?php

namespace Modules\Payment\Gateways;

use Exception;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Payment\GatewayInterface;
use Modules\Checkout\Events\OrderPlaced;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Payment\Responses\Pay2outResponse;

class Pay2out implements GatewayInterface
{
    public $label;
    public $description;
    public const CURRENCIES = [
        "TRY",
        "EUR",
        "USD",
        "GBP",
        "IRR",
        "NOK",
        "RUB",
        "CHF",
    ];
    public Order $order;


    public function __construct()
    {
        $this->label = setting('pay2out_label');
        $this->description = setting('pay2out_description');
    }


    /**
     * @throws Exception
     */
    public function purchase(Order $order, Request $request)
    {




        $this->order = $order;
        $order = $order->toArray();
        $account = auth()->user();

        $order_id = $this->order->id;
        $amount = (float) $order["sub_total"]->toArray()["amount"];

        // Unique order number oluştur (timestamp + random + user + order)
        $timestamp = time();
        $random = substr(md5(uniqid(rand(), true)), 0, 6);
        $merchant_oid = $account->id . "ORDER" . $order_id . "_" . $timestamp . "_" . $random;

        $merchant_key = setting('pay2out_merchant_salt');

        // Müşteri bilgileri
        $user_name = $account->name ?? 'Müşteri';
        $email = $account->email ?? 'musteri@example.com';
        $user_phone = $account->phone ?? '5551234567';

        if (setting("pay2out_test_mode") == NULL)
            $test_mode = 0;
        else
            $test_mode = 1;



            $currency = "TRY";

            $user_address = "İstanbul";

            $post_vals = array(
                'signature_secret' => $merchant_key,
                'amount' => $amount,
                'currency' => $currency,
                'customer_name' => $user_name,
                'customer_email' => $email,
                'customer_phone' => $user_phone,
                'customer_address' => $user_address,
                'order_number' => $merchant_oid,
                'return_url' => route('checkout.complete.store', ['orderId' => $this->order->id, 'paymentMethod' => 'pay2out']),
                'cancel_url' => route('checkout.create')
            );

            $ch = curl_init();

            // Sunucu uyumluluğu için gelişmiş cURL ayarları
            $curl_options = array(
                CURLOPT_URL => 'https://www.pay2out.com/api/payment/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45, // Timeout'u artır
                CURLOPT_CONNECTTIMEOUT => 30, // Bağlantı timeout'u ekle
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => 1,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'Pay2out-PHP-Client/1.0',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Cache-Control: no-cache'
                ),
            );

            if(!$test_mode){
                $curl_options[CURLOPT_POSTFIELDS] = json_encode($post_vals);
                curl_setopt_array($ch, $curl_options);
            } else {
                $post_vals = array(
                    'signature_secret' => $merchant_key,
                    'amount' => $amount,
                    'currency' => $currency,
                    'description' => "Sipariş ödemesi",
                    'has_installment' => false,
                    'customer_name' => $user_name,
                    'customer_email' => $email,
                    'customer_phone' => $user_phone,
                    'customer_address' => $user_address,
                    'customer_city' => "İstanbul",
                    'customer_district' => "Kadıköy",
                    'customer_postal_code' => "34752",
                    'reference' => "OD-123456789",
                    'order_number' => $merchant_oid,
                    'expires_at' => "2025-11-01T15:00:00Z",
                    'return_url' => route('checkout.complete.store', ['orderId' => $this->order->id, 'paymentMethod' => 'pay2out']),
                    'cancel_url' => route('checkout.create')
                );
                $curl_options[CURLOPT_POSTFIELDS] = json_encode($post_vals);
                curl_setopt_array($ch, $curl_options);
            }

            // Retry mekanizması ile bağlantı dene
            $maxRetries = 2;
            $retryCount = 0;
            $result = false;

            while ($retryCount <= $maxRetries && $result === false) {
                if ($retryCount > 0) {
                    Log::info('Pay2out Purchase Connection Retry', [
                        'attempt' => $retryCount + 1,
                        'max_retries' => $maxRetries + 1
                    ]);
                    sleep(2); // 2 saniye bekle
                }

                $result = @curl_exec($ch);

                if (curl_errno($ch)) {
                    $error = curl_error($ch);
                    $errno = curl_errno($ch);

                    Log::warning('Pay2out Purchase cURL Attempt Failed', [
                        'attempt' => $retryCount + 1,
                        'error' => $error,
                        'errno' => $errno
                    ]);

                    if ($retryCount >= $maxRetries) {
                        curl_close($ch);
                        Log::error('Pay2out Purchase cURL Error - All Retries Failed', [
                            'error' => $error,
                            'errno' => $errno,
                            'total_attempts' => $retryCount + 1
                        ]);
                        throw new Exception("Pay2out connection error: " . $error);
                    }
                } else {
                    // Başarılı, döngüden çık
                    break;
                }

                $retryCount++;
            }

            curl_close($ch);

            // API yanıtını logla (debug için)
            Log::info('Pay2out API Response (Purchase)', [
                'raw_response' => $result,
                'post_data' => $post_vals
            ]);

            $result = json_decode($result, true);

            // API yanıtını güvenli şekilde kontrol et
            if (!$result) {
                Log::error('Pay2out Invalid JSON Response', [
                    'raw_response' => $result
                ]);
                throw new Exception("Pay2out API failed. reason: Invalid JSON response");
            }

            if (isset($result['success']) && $result['success'] === true) {
                // Pay2out API yanıtında payment_url doğrudan root'ta
                if (isset($result['payment_url'])) {
                    Log::info('Pay2out Purchase Successful', [
                        'order_id' => $this->order->id,
                        'payment_url' => $result['payment_url']
                    ]);

                    return new Pay2outResponse($this->order, $result);
                } else {
                    Log::error('Pay2out Payment URL Missing', [
                        'response_keys' => array_keys($result)
                    ]);
                    throw new Exception("Pay2out API failed. reason: Payment URL not found in response");
                }
            } else {
                $error_message = isset($result['message']) ? $result['message'] : 'Unknown API error';
                Log::error('Pay2out API Error', [
                    'error' => $error_message,
                    'full_response' => $result
                ]);
                throw new Exception("Pay2out API failed. reason: " . $error_message);
            }

    }

    public function callback($data) {
        // Debug logging - callback çağrıldığında log'a yaz
        Log::info('Pay2out Callback Called', [
            'data' => $data,
            'signature' => $_SERVER['HTTP_X_SIGNATURE'] ?? 'missing',
            'timestamp' => now(),
            'raw_input' => file_get_contents('php://input')
        ]);

        $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
        $secretKey = setting('pay2out_merchant_salt');

        // Pay2out dökümanına göre payload ile imza doğrulaması
        $payload = file_get_contents('php://input');
        $calculatedSignature = hash_hmac('sha256', $payload, $secretKey);

        // İmza doğrulaması
        if (!hash_equals($calculatedSignature, $signature)) {
            Log::warning('Pay2out Callback Invalid Signature', [
                'received_signature' => $signature,
                'calculated_signature' => $calculatedSignature,
                'payload' => $payload
            ]);
            http_response_code(403);
            echo json_encode(['error' => 'Invalid signature']);
            exit;
        }

        Log::info('Pay2out Callback Signature Validated Successfully');

        // Pay2out callback veri yapısını kontrol et
        Log::info('Pay2out Callback Data Structure', [
            'data_keys' => array_keys($data),
            'data_values' => $data
        ]);

        // Status kontrolü - Pay2out'tan gelen veri yapısına göre
        $status = $data['status'] ?? $data['payment_status'] ?? 'unknown';

        Log::info('Pay2out Callback Status Check', [
            'status' => $status,
            'is_completed' => ($status == 'completed' || $status == 'success' || $status == 'paid')
        ]);

        if ($status == 'completed' || $status == 'success' || $status == 'paid') {

            $merchant_oid = $data['order_number'];

            if (strstr($merchant_oid, "BALANCE")) {
                Log::info('Pay2out Balance Order Processing', [
                    'merchant_oid' => $merchant_oid,
                    'amount' => $data["amount"]
                ]);

                $order_parts = explode("BALANCE", $merchant_oid);

                if (count($order_parts) < 2) {
                    Log::error('Pay2out Balance Order ID Parse Error', [
                        'merchant_oid' => $merchant_oid,
                        'order_parts' => $order_parts
                    ]);
                    return ['status' => 'failed', 'error_message' => 'Invalid order format'];
                }

                // Yeni format: "abc12345BALANCE1_1726876543"
                // User ID'yi timestamp'tan ayır
                $balance_info = $order_parts[1];
                $user_id_parts = explode('_', $balance_info);
                $user_id = $user_id_parts[0]; // İlk kısım user ID

                Log::info('Pay2out Balance User Identified', [
                    'user_id' => $user_id,
                    'order_parts' => $order_parts
                ]);

                auth()->loginUsingId($user_id);
                $account = auth()->user();

                if ($account instanceof User) {
                    $old_balance = $account->balance;
                    $account->balance += $data["amount"];
                    $account->save();

                    Log::info('Pay2out Balance Updated Successfully', [
                        'user_id' => $user_id,
                        'old_balance' => $old_balance,
                        'added_amount' => $data["amount"],
                        'new_balance' => $account->balance
                    ]);
                } else {
                    Log::error('Pay2out Balance Update Failed - User Not Found', [
                        'user_id' => $user_id
                    ]);
                }

                // Pay2out'a başarı bildirimi gönder
                return response('OK', 200)
                    ->header('Content-Type', 'text/plain');

            } else {
                // ORDER işlemi
                $order_parts = explode("ORDER", $merchant_oid);

                if (count($order_parts) < 2) {
                    Log::error('Pay2out Order ID Parse Error', [
                        'merchant_oid' => $merchant_oid,
                        'order_parts' => $order_parts
                    ]);
                    return ['status' => 'failed', 'error_message' => 'Invalid order format'];
                }

                $user_id = $order_parts[0];

                // Yeni format: "1ORDER123_1726876543_abc123"
                // Order ID'yi timestamp ve random'dan ayır
                $order_info = $order_parts[1];
                $order_id_parts = explode('_', $order_info);
                $order_id = $order_id_parts[0]; // İlk kısım order ID

                Log::info('Pay2out Order Processing', [
                    'order_id' => $order_id,
                    'user_id' => $user_id,
                    'merchant_oid' => $merchant_oid
                ]);

                try {
                    $order = Order::findOrFail($order_id);

                    $user = DB::table('users')
                        ->where('id', $user_id)
                        ->first();

                    if ($user && $user->reference != 0) {
                        $reference = DB::table('users')->where('id', $user->reference)->first();
                        if ($reference) {
                            $referenceAmount = $data['amount'] * ($reference->reference_percentage / 100);
                            DB::table('references_logs')->insert([
                                'user_id' => $user->id,
                                'reference_id' => $reference->id,
                                'order_id' => $order_id,
                                'amount' => $referenceAmount
                            ]);

                            Log::info('Pay2out Reference Commission Added', [
                                'user_id' => $user->id,
                                'reference_id' => $reference->id,
                                'commission_amount' => $referenceAmount
                            ]);
                        }
                    }

                    event(new OrderPlaced($order));

                    return [
                        "order_id" => $order_id,
                        "status" => "success"
                    ];

                } catch (Exception $e) {
                    Log::error('Pay2out Order Processing Failed', [
                        'order_id' => $order_id,
                        'error' => $e->getMessage()
                    ]);
                    return [
                        "error_message" => "Order processing failed",
                        "status" => "failed"
                    ];
                }
            }

        } else {
            $error_message = $data['message'] ?? 'Beklenmeyen bir hata oluştu';
            Log::error('Pay2out Callback Failed', [
                'status' => $status,
                'error_message' => $error_message,
                'data' => $data
            ]);

            return [
                "error_message" => $error_message,
                "status" => "failed"
            ];
        }
    }

    public function balance_order($data) {
        Log::info('Pay2out balance_order() Called', [
            'data' => $data,
            'user_id' => auth()->id()
        ]);

        $account = auth()->user();
        $amount = (float) $data['amount'];

        if (setting("pay2out_test_mode") == NULL) {
            $test_mode = 0;
        } else {
            $test_mode = 1;
        }

        $merchant_key = setting("pay2out_merchant_salt");
        $user_id = $account->id;
        $email = $account->email;

        // Unique balance order number oluştur (timestamp + random + user)
        $timestamp = time();
        $random = substr(md5(uniqid(rand(), true)), 0, 8);
        $merchant_oid = $random . "BALANCE" . $user_id . "_" . $timestamp;

        $user_name = ($account->first_name ?? '') . " " . ($account->last_name ?? '');
        $user_phone = $account->phone ?? '5551234567';
        $currency = "TRY";
        $user_address = "İstanbul";

        $post_vals = array(
            'signature_secret' => $merchant_key,
            'amount' => $amount,
            'currency' => $currency,
            'customer_name' => trim($user_name) ?: 'Müşteri',
            'customer_email' => $email,
            'customer_phone' => $user_phone,
            'customer_address' => $user_address,
            'order_number' => $merchant_oid,
            'return_url' => route('account.dashboard.balance') . '?status=1',
            'cancel_url' => route('account.dashboard.balance') . '?status=0'
        );

        $ch = curl_init();

        // Sunucu uyumluluğu için gelişmiş cURL ayarları
        $curl_options = array(
            CURLOPT_URL => 'https://www.pay2out.com/api/payment/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 45, // Timeout'u artır
            CURLOPT_CONNECTTIMEOUT => 30, // Bağlantı timeout'u ekle
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Pay2out-PHP-Client/1.0',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Cache-Control: no-cache'
            ),
        );

        if (!$test_mode) {
            $curl_options[CURLOPT_POSTFIELDS] = json_encode($post_vals);
            curl_setopt_array($ch, $curl_options);
        } else {
            $post_vals = array(
                'signature_secret' => $merchant_key,
                'amount' => $amount,
                'currency' => $currency,
                'description' => "Bakiye yükleme",
                'has_installment' => false,
                'customer_name' => trim($user_name) ?: 'Müşteri',
                'customer_email' => $email,
                'customer_phone' => $user_phone,
                'customer_address' => $user_address,
                'customer_city' => "İstanbul",
                'customer_district' => "Kadıköy",
                'customer_postal_code' => "34752",
                'reference' => "BALANCE-" . $user_id,
                'order_number' => $merchant_oid,
                'expires_at' => "2025-11-01T15:00:00Z",
                'return_url' => route('account.dashboard.balance') . '?status=1',
                'cancel_url' => route('account.dashboard.balance') . '?status=0'
            );
            $curl_options[CURLOPT_POSTFIELDS] = json_encode($post_vals);
            curl_setopt_array($ch, $curl_options);
        }

        // Retry mekanizması ile bağlantı dene
        $maxRetries = 2;
        $retryCount = 0;
        $result = false;

        while ($retryCount <= $maxRetries && $result === false) {
            if ($retryCount > 0) {
                Log::info('Pay2out Connection Retry', [
                    'attempt' => $retryCount + 1,
                    'max_retries' => $maxRetries + 1
                ]);
                sleep(2); // 2 saniye bekle
            }

            $result = @curl_exec($ch);

            if (curl_errno($ch)) {
                $error = curl_error($ch);
                $errno = curl_errno($ch);

                Log::warning('Pay2out cURL Attempt Failed', [
                    'attempt' => $retryCount + 1,
                    'error' => $error,
                    'errno' => $errno
                ]);

                if ($retryCount >= $maxRetries) {
                    curl_close($ch);
                    Log::error('Pay2out cURL Error - All Retries Failed', [
                        'error' => $error,
                        'errno' => $errno,
                        'total_attempts' => $retryCount + 1
                    ]);
                    throw new Exception("Pay2out connection error: " . $error);
                }
            } else {
                // Başarılı, döngüden çık
                break;
            }

            $retryCount++;
        }

        curl_close($ch);

        // API yanıtını logla (debug için)
        Log::info('Pay2out API Response (Balance Order)', [
            'raw_response' => $result,
            'post_data' => $post_vals
        ]);

        $result = json_decode($result, true);

        // API yanıtını güvenli şekilde kontrol et
        if (!$result) {
            Log::error('Pay2out Invalid JSON Response', [
                'raw_response' => $result
            ]);
            throw new Exception("Pay2out API failed. reason: Invalid JSON response");
        }

        if (isset($result['success']) && $result['success'] === true) {
            // Pay2out API yanıtında payment_url doğrudan root'ta
            if (isset($result['payment_url'])) {
                Log::info('Pay2out Balance Order Successful', [
                    'user_id' => $user_id,
                    'amount' => $amount,
                    'payment_url' => $result['payment_url']
                ]);

                // Balance order için dummy order oluştur
                $dummyOrder = new Order();
                $dummyOrder->id = 0; // Balance order için özel ID

                return new Pay2outResponse($dummyOrder, $result);
            } else {
                Log::error('Pay2out Payment URL Missing', [
                    'response_keys' => array_keys($result)
                ]);
                throw new Exception("Pay2out API failed. reason: Payment URL not found in response");
            }
        } else {
            $error_message = isset($result['message']) ? $result['message'] : 'Unknown API error';
            Log::error('Pay2out Balance Order Error', [
                'error' => $error_message,
                'full_response' => $result
            ]);
            throw new Exception("Pay2out API failed. reason: " . $error_message);
        }
    }


    public function complete(Order $order) {

    }
}
