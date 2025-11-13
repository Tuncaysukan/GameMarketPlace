<?php

namespace Modules\Payment\Gateways;

use Exception;
use Iyzipay\Options;
use Iyzipay\Model\Buyer;
use Modules\User\Entities\User;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Address;
use Illuminate\Http\Request;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\PaymentGroup;
use Modules\Order\Entities\Order;
use Iyzipay\Model\BasketItemType;
use Modules\Payment\GatewayInterface;
use Modules\Checkout\Events\OrderPlaced;
use Iyzipay\Model\CheckoutFormInitialize;
use Modules\Payment\Responses\IyzicoResponse;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

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

        $order_id = $this->order->id;
        $amount = $order["sub_total"]->toArray()["amount"];
        $merchant_oid = $account->id."ORDER".$order_id;
        $merchant_key   = setting('pay2out_merchant_salt');

        $account = auth()->user();

        if (setting("pay2out_test_mode") == NULL)
            $test_mode = 0;
        else
            $test_mode = 1;



            $currency = "TRY";

            $user_address = "İstanbul";
      
            $post_vals = array(
                'amount' => $amount,
                'currency' => $currency,
                'customer_name' => $user_name,
                'customer_email' => $email,
                'customer_phone' => $user_phone,
                'customer_address' => $user_address,
                'order_number' => $merchant_oid
            );
    
            $post_vals['signature_secret'] = hash_hmac('sha256', json_encode($post_vals), $merchant_key);
    
            $ch = curl_init();
    
            if(!$test_mode){
                curl_setopt_array($ch, array(
                    CURLOPT_URL => 'https://www.pay2out.com/api/payment/create',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => json_encode($post_vals),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
            } else {
                $post_vals = array(
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
                    'expires_at' => "2025-11-01T15:00:00Z"
                );
                $post_vals['signature_secret'] = hash_hmac('sha256', json_encode($post_vals), $merchant_key);
                curl_setopt_array($ch, array(
                    CURLOPT_URL => 'https://www.pay2out.com/api/payment/create',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => json_encode($post_vals),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
            }
    
            $result = @curl_exec($ch);
    
            if(curl_errno($ch))
                die("Pay2out IFRAME connection error. err:".curl_error($ch));
    
            curl_close($ch);
    
            $result=json_decode($result,1);
       
            if($result['success']=== true)
                $p2o_paymentUrl=$result['data']['payment_url'];
            else
                die("Pay2out IFRAME failed. reason:".$result['message']);
    
            return header("location:$p2o_paymentUrl");

    }

    public function callback($data) {

        $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
        $secretKey = setting('pay2out_merchant_salt');

        $_sign = hash_hmac('sha256', json_encode($data), $secretKey);

        if( $_sign != $signature ){
            //http_response_code(403);
            //echo json_encode(['error' => 'Invalid signature']);
            //exit;
        }

        if( $data['status'] == 'completed' ) {

            $merchant_oid = $data['order_number'];

            if (strstr($merchant_oid, "BALANCE")) {

                $order = explode("BALANCE", $merchant_oid);

                $user_id = $order[1];

                auth()->loginUsingId($user_id);

                $account = auth()->user();

                $account->balance += $data["amount"];

                $account->save();

                exit("OK");

            } else {

                $order_id = explode("ORDER", $merchant_oid);

                $order_id = $order_id[1];
                $user_id = $order_id[0];

                $order = Order::findOrFail($order_id);

                $user = DB::table('users')
                    ->where('id', $user_id)
                    ->first();

                if($user->reference != 0){
                    $referenceAmount = $data['amount'] * ($reference->reference_percentage / 100);
                    $references_logs = DB::table('references_logs')->insert(
                        [
                            'user_id' => $user->id,
                            'reference_id' => $reference->id,
                            'order_id' => $order_id,
                            'amount' => $referenceAmount
                        ]
                    );
                }

                event(new OrderPlaced($order));

            }

            return [
                "order_id" => $order_id,
                "status" => "success"
            ];
        } else {

            $error_message = $data['message'] ?? 'Beklenmeyen bir hata oluştu';

            return [
                "error_message" => $error_message,
                "status" => "failed"
            ];
        }



    }

    public function balance_order($data) {
        $account = auth()->user();

        $amount = $data['amount'];


        if (setting("pay2out_test_mode") == NULL)
            $test_mode = 0;
        else
            $test_mode = 1;

        $merchant_key   = setting("pay2out_merchant_salt");

        $user_id = $account->id;

        $email = $account->email;

        $merchant_oid = uniqid()."BALANCE".$user_id;
        $user_name = $account->first_name." ".$account->last_name;
        $user_phone = $account->phone;

        $currency = "TRY";

        $user_address = "İstanbul";
  
        $post_vals = array(
            'amount' => $amount,
            'currency' => $currency,
            'customer_name' => $user_name,
            'customer_email' => $email,
            'customer_phone' => $user_phone,
            'customer_address' => $user_address,
            'order_number' => $merchant_oid
        );

        $post_vals['signature_secret'] = hash_hmac('sha256', json_encode($post_vals), $merchant_key);

        $ch = curl_init();

        if(!$test_mode){
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://www.pay2out.com/api/payment/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => json_encode($post_vals),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        } else {
            $post_vals = array(
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
                'expires_at' => "2025-11-01T15:00:00Z"
            );
            $post_vals['signature_secret'] = hash_hmac('sha256', json_encode($post_vals), $merchant_key);
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://www.pay2out.com/api/payment/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => json_encode($post_vals),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }

        $result = @curl_exec($ch);

        if(curl_errno($ch))
            die("Pay2out IFRAME connection error. err:".curl_error($ch));

        curl_close($ch);

        $result=json_decode($result,1);
   
        if($result['success']=== true)
            $p2o_paymentUrl=$result['data']['payment_url'];
        else
            die("Pay2out IFRAME failed. reason:".$result['message']);

        return header("location:$p2o_paymentUrl");
    }


    public function complete(Order $order) {

    }
}
