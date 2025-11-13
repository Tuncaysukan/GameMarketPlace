<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
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


        $amount = $post['amount'];
        $method = $post['payment_methods'];

        if ($method == "paytr") {
            $gateway = new PayTR();
        } else if ($method == "shopier") {
            $gateway = new Shopier();
        } else if ($method == "pay2out") {
            $gateway = new Pay2out();
        }

        $data = [
            "amount" => $amount
        ];

        $gateway->balance_order($data);



        exit;
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
