<?php

namespace Modules\Payment\Responses;

use Modules\Order\Entities\Order;
use Modules\Payment\GatewayResponse;
use Modules\Payment\ShouldRedirect;
use Modules\Payment\HasTransactionReference;

class Pay2outResponse extends GatewayResponse implements ShouldRedirect, HasTransactionReference
{
    private $order;
    private $data;

    public function __construct(Order $order, $data)
    {
        $this->order = $order;
        $this->data = $data;
    }

    public function getOrderId()
    {
        return $this->order->id;
    }

    public function getRedirectUrl()
    {
        return $this->data['payment_url'] ?? '';
    }

    public function getTransactionReference()
    {
        return $this->data['order_uuid'] ?? '';
    }

    public function toArray()
    {
        return parent::toArray() + [
            'payment_url' => $this->getRedirectUrl(),
            'order_uuid' => $this->getTransactionReference(),
            'order_number' => $this->data['order_number'] ?? '',
            'amount' => $this->data['amount'] ?? '',
            'currency' => $this->data['currency'] ?? '',
            'expires_at' => $this->data['expires_at'] ?? '',
        ];
    }
}
