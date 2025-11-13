<?php

namespace Modules\Checkout\Listeners;

use Modules\Order\Entities\Order;
use Modules\Checkout\Events\OrderPlaced;

class UpdateOrderStatus
{
    /**
     * Handle the event.
     *
     * @param OrderPlaced $event
     *
     * @return void
     */
    public function handle($event)
    {
        // Veritabanından güncel durumu al
        $event->order->refresh();

        \Log::info('UpdateOrderStatus - Sipariş durumu kontrol ediliyor: ' . $event->order->id . ' - Durum: ' . $event->order->status);

        // Eğer sipariş zaten completed ise, durumu değiştirme
        if ($event->order->status !== Order::COMPLETED) {
            $event->order->update(['status' => Order::PENDING]);
            \Log::info('UpdateOrderStatus - Sipariş PENDING yapıldı: ' . $event->order->id);
        } else {
            \Log::info('UpdateOrderStatus - Sipariş zaten COMPLETED, değiştirilmedi: ' . $event->order->id);
        }
    }
}
