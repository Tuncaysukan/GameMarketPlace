<?php

namespace Modules\Payment\Gateways;

use Exception;
use Iyzipay\Options;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Address;
use Illuminate\Http\Request;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\PaymentGroup;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Iyzipay\Model\BasketItemType;
use Modules\Payment\GatewayInterface;
use Iyzipay\Model\CheckoutFormInitialize;
use Modules\Payment\Responses\IyzicoResponse;
use Modules\Checkout\Events\OrderPlaced;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Illuminate\Support\Facades\DB;

class Balance implements GatewayInterface
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
        $this->label = setting('balance_label');
        $this->description = setting('balance_description');
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

        $account = auth()->user();

        if ($amount > $account->balance) {
            throw new Exception("Bakiyeniz bu ürün için yeterli değildir.");
        }


        $account->balance -= $amount;
        $account->save();

        

        event(new OrderPlaced($this->order));

        $delivery = [];

        foreach($this->order->products as $product) {
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

        if(Auth()->user()->reference != 0){
            $reference = DB::table('users')
                ->where('id', Auth()->user()->reference)
                ->first();

            $referenceAmount = $amount * ($reference->reference_percentage / 100);
            $references_logs = DB::table('references_logs')->insert(
                [
                    'user_id' => Auth()->user()->id,
                    'reference_id' => $reference->id,
                    'order_id' => $order_id,
                    'amount' => $referenceAmount
                ]
            );

            $references_upd = DB::table('users')->where('id', $reference->id)->update(
                [
                    'balance' => $reference->balance+$referenceAmount
                ]
            );
        }

        if ($delivery != []) {
            $order = Order::find($order_id);
            $order->stock_code = json_encode($delivery);
            $order->status = 'completed';
            $order->save();
        }

        return [
            'orderId' => $order_id,
            'redirectUrl' => route("account.orders.show", ["id" => $order_id])."?status=1",
        ];
    }

    public function complete(Order $order) {
        
    }


}
