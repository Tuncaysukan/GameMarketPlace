<?php

namespace Modules\Payment;

use ArrayAccess;
use JsonSerializable;

abstract class GatewayResponse implements JsonSerializable, ArrayAccess
{
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }


    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }


    public function toArray()
    {
        $data = ['orderId' => $this->getOrderId()];

        if ($this instanceof ShouldRedirect) {
            $data['redirectUrl'] = $this->getRedirectUrl();
        }

        return $data;
    }

    /**
     * ArrayAccess implementation
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    public function offsetGet($offset): mixed
    {
        $data = $this->toArray();
        return $data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        // Gateway responses are read-only
        throw new \BadMethodCallException('Gateway responses are read-only');
    }

    public function offsetUnset($offset): void
    {
        // Gateway responses are read-only
        throw new \BadMethodCallException('Gateway responses are read-only');
    }

    abstract public function getOrderId();
}
