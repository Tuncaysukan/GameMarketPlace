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

class PayTR implements GatewayInterface
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
        $this->label = setting('paytr_label');
        $this->description = setting('paytr_description');
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






        if (setting("paytr_test_mode") == NULL)
            $test_mode = 0;
        else
            $test_mode = 1;



        $merchant_id    = setting("paytr_merchant_id");
        $merchant_key   = setting("paytr_merchant_key");
        $merchant_salt  = setting("paytr_merchant_salt");

        $user_id = $account->id;

        $email = $account->email;;
        $payment_amount = $amount*100;
        $merchant_oid = $account->id."ORDER".$order_id;
        $user_name = $account->first_name." ".$account->last_name;
        $user_address = "İstanbul";
        $user_phone = $account->phone;

        $merchant_ok_url = route("account.orders.show", ["id" => $order_id])."?status=1";
        $merchant_fail_url = route("account.orders.show", ["id" => $order_id])."?status=0";

        $user_basket = base64_encode(json_encode(array(
            array("Sipariş No: ".$order_id, $amount, 1)
        )));

        if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $user_ip=$ip;
        $timeout_limit = "30";
        $debug_on = 1;
        $test_mode = $test_mode;

        $no_installment = 0;
        $max_installment = 0;

        $currency = "TL";

        $hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
        $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
        $post_vals=array(
                'merchant_id'=>$merchant_id,
                'user_ip'=>$user_ip,
                'merchant_oid'=>$merchant_oid,
                'email'=>$email,
                'payment_amount'=>$payment_amount,
                'paytr_token'=>$paytr_token,
                'user_basket'=>$user_basket,
                'debug_on'=>$debug_on,
                'no_installment'=>$no_installment,
                'max_installment'=>$max_installment,
                'user_name'=>$user_name,
                'user_address'=>$user_address,
                'user_phone'=>$user_phone,
                'merchant_ok_url'=>$merchant_ok_url,
                'merchant_fail_url'=>$merchant_fail_url,
                'timeout_limit'=>$timeout_limit,
                'currency'=>$currency,
                'test_mode'=>$test_mode
            );

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);


        $result = @curl_exec($ch);

        if(curl_errno($ch))
            die("PAYTR IFRAME connection error. err:".curl_error($ch));

        curl_close($ch);



        $result=json_decode($result,1);

        if($result['status']=='success')
            $token=$result['token'];
        else
            die("PAYTR IFRAME failed. reason:".$result['reason']);






        return [
            'orderId' => $order_id,
            'redirectUrl' => "https://www.paytr.com/odeme/guvenli/$token",
        ];

    }

    public function callback($data) {

        $merchant_key   = setting('paytr_merchant_key');
        $merchant_salt  = setting('paytr_merchant_salt');

        $hash = base64_encode( hash_hmac('sha256', $data['merchant_oid'].$merchant_salt.$data['status'].$data['total_amount'], $merchant_key, true) );

        if( $hash != $data['hash'] )
            die('PAYTR notification failed: bad hash');

    
        if( $data['status'] == 'success' ) {
    
            $merchant_oid = $data['merchant_oid'];

            if (strstr($merchant_oid, "BALANCE")) {



                $order = explode("BALANCE", $merchant_oid);


                $user_id = $order[1];

                auth()->loginUsingId($user_id);

                $account = auth()->user();

                $account->balance += $data["total_amount"]/100;

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
                    $referenceAmount = $data['total_amount'] * ($reference->reference_percentage / 100);
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
    
            $error_message = $data['failed_reason_msg'];
    
            return [
                "error_message" => $error_message,
                "status" => "failed"
            ];
        }
     
    
        
    }

    public function balance_order($data) {
        $account = auth()->user();

        $amount = $data['amount'];


        if (setting("paytr_test_mode") == NULL)
            $test_mode = 0;
        else
            $test_mode = 1;



        $merchant_id    = setting("paytr_merchant_id");
        $merchant_key   = setting("paytr_merchant_key");
        $merchant_salt  = setting("paytr_merchant_salt");

        $user_id = $account->id;

        $email = $account->email;;
        $payment_amount = $amount*100;
        $merchant_oid = uniqid()."BALANCE".$user_id;
        $user_name = $account->first_name." ".$account->last_name;
        $user_address = "İstanbul";
        $user_phone = $account->phone;

        $merchant_ok_url = route("account.dashboard.balance")."?status=1";
        $merchant_fail_url = route("account.dashboard.balance")."?status=0";

        $user_basket = base64_encode(json_encode(array(
            array("Bakiye Yükleme ".$amount, $amount, 1)
        )));

        if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $user_ip=$ip;
        $timeout_limit = "30";
        $debug_on = 1;
        $test_mode = $test_mode;

        $no_installment = 0;
        $max_installment = 0;

        $currency = "TL";

        $hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
        $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
        $post_vals=array(
            'merchant_id'=>$merchant_id,
            'user_ip'=>$user_ip,
            'merchant_oid'=>$merchant_oid,
            'email'=>$email,
            'payment_amount'=>$payment_amount,
            'paytr_token'=>$paytr_token,
            'user_basket'=>$user_basket,
            'debug_on'=>$debug_on,
            'no_installment'=>$no_installment,
            'max_installment'=>$max_installment,
            'user_name'=>$user_name,
            'user_address'=>$user_address,
            'user_phone'=>$user_phone,
            'merchant_ok_url'=>$merchant_ok_url,
            'merchant_fail_url'=>$merchant_fail_url,
            'timeout_limit'=>$timeout_limit,
            'currency'=>$currency,
            'test_mode'=>$test_mode
        );

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);


        $result = @curl_exec($ch);

        if(curl_errno($ch))
            die("PAYTR IFRAME connection error. err:".curl_error($ch));

        curl_close($ch);



        $result=json_decode($result,1);

        if($result['status']=='success')
            $token=$result['token'];
        else
            die("PAYTR IFRAME failed. reason:".$result['reason']);


        return header("location:https://www.paytr.com/odeme/guvenli/$token");
    }


    public function complete(Order $order) {

    }
}
