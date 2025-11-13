<?php

namespace Modules\Account\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\Payment\Gateways\PayTR;
use Modules\Payment\Gateways\Pay2out;
use Modules\Payment\Gateways\Shopier;

class AccountDashboardController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('storefront::public.account.dashboard.index', [
            'account' => auth()->user(),
            'recentOrders' => auth()->user()->recentOrders(5),
        ]);
    }

    public function balance() {
        return view('storefront::public.account.profile.balance', [
            'account' => auth()->user(),
        ]);
    }

    public function balance_post(Request $request) {

        $post = $request->input();

        Log::info('Balance Post Started', [
            'post_data' => $post,
            'user_id' => auth()->id()
        ]);

        $amount = $post['amount'];
        $method = $post['payment_methods'];

        Log::info('Payment Method Selected', [
            'amount' => $amount,
            'method' => $method
        ]);

        if ($method == "paytr") {
            $gateway = new PayTR();
        } else if ($method == "shopier") {
            $gateway = new Shopier();
        } else if ($method == "pay2out") {
            Log::info('Pay2out Gateway Initialized');
            $gateway = new Pay2out();
        }

        $data = [
            "amount" => $amount
        ];

        Log::info('Calling balance_order', [
            'method' => $method,
            'data' => $data
        ]);

        try {
            // Timeout koruması için max execution time ayarla
            set_time_limit(60);

            $response = $gateway->balance_order($data);

            Log::info('Balance Order Response Received', [
                'method' => $method,
                'response_type' => get_class($response)
            ]);

            // Response null kontrolü
            if (!$response) {
                Log::error('Balance Order Response is null', [
                    'method' => $method
                ]);
                return redirect()->back()->with('error', 'Ödeme yanıtı alınamadı.');
            }

            // Response'da redirectUrl varsa redirect yap
            if (isset($response['redirectUrl'])) {
                Log::info('Redirecting to payment URL', [
                    'redirect_url' => $response['redirectUrl']
                ]);
                return redirect()->away($response['redirectUrl']);
            }

            // Fallback: toArray() metodundan redirect URL'i al
            $responseArray = $response->toArray();
            if (isset($responseArray['redirectUrl'])) {
                Log::info('Redirecting to payment URL (from toArray)', [
                    'redirect_url' => $responseArray['redirectUrl']
                ]);
                return redirect()->away($responseArray['redirectUrl']);
            }

            // Hiçbir redirect URL bulunamadıysa hata
            Log::error('No redirect URL found in balance order response', [
                'response_array' => $responseArray
            ]);
            return redirect()->back()->with('error', 'Ödeme URL\'si bulunamadı.');

        } catch (Exception $e) {
            Log::error('Balance Order Exception', [
                'method' => $method,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Timeout veya bağlantı hatası kontrolü
            if (strpos($e->getMessage(), 'Connection timed out') !== false ||
                strpos($e->getMessage(), 'connection error') !== false) {

                Log::warning('Pay2out Connection Issues Detected', [
                    'method' => $method,
                    'user_id' => auth()->id(),
                    'amount' => $amount
                ]);

                return redirect()->back()->with('error',
                    'Ödeme sağlayıcısına şu anda bağlanılamıyor. Lütfen daha sonra tekrar deneyiniz veya farklı bir ödeme yöntemi seçiniz.');
            }

            // Diğer hatalar için genel mesaj
            return redirect()->back()->with('error', 'Ödeme işlemi başlatılamadı: ' . $e->getMessage());
        }
    }

    public function callback(Request $request) {

        $values = $request->input();


        if ($values["method"] == "paytr") {
            $gateway = new PayTR();
        } else if ($values["method"] == "shopier") {
            $gateway = new Shopier();
        }else if ($values["method"] == "pay2out") {
            $gateway = new Pay2out();
        }

        $get = $gateway->callback($values);


        if ($get["status"] == "success") {

            $order = Order::find($get["order_id"]);
            $order_id = $get["order_id"];

            $delivery = [];

            foreach($order->products as $product) {
                $product = $product->toArray();
                $product_data = Product::findOrFail($product["product_id"]);

                $product = $product_data->toArray();

                if ($product["stock"] == "") {
                    $stock = [];
                } else {
                    $stock = explode("\n", $product["stock"]);
                }


                if ($stock != []) {

                    $code = $stock[0];

                    unset($stock[0]);
                    $new_stock = implode("\n", $stock);

                    $product_data->stock = $new_stock;
                    $product_data->save();

                    $delivery[] = $code;

                }

            }

            if ($delivery != []) {
                $order = Order::find($order_id);
                $order->stock_code = json_encode($delivery);
                $order->status = 'completed';
                $order->save();
            }


        }



        return exit("OK");
    }
}
