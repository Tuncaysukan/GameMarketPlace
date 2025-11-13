<?php

namespace Modules\Listing\Services;

use Modules\Listing\Entities\Listing;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Notification;

class ManualDeliveryService
{
    /**
     * Process manual delivery notification for an order.
     *
     * @param Listing $listing
     * @param Order $order
     * @param int $quantity
     * @return array
     */
    public function processDelivery(Listing $listing, Order $order, $quantity = 1)
    {
        if (!$listing->isManualDelivery()) {
            throw new \Exception('Bu ilan manuel teslimat için yapılandırılmamış.');
        }

        $vendor = $listing->vendor;

        // Satıcıya bildirim gönder
        $this->notifyVendor($vendor, $listing, $order, $quantity);

        // Müşteriye bilgi ver
        $this->notifyCustomer($order, $listing);

        return [
            'success' => true,
            'message' => 'Satıcı bilgilendirildi. En kısa sürede sizinle iletişime geçecektir.',
            'estimated_delivery' => now()->addDays($listing->processing_days)->format('d.m.Y'),
        ];
    }

    /**
     * Notify vendor about new order.
     *
     * @param $vendor
     * @param Listing $listing
     * @param Order $order
     * @param int $quantity
     * @return void
     */
    protected function notifyVendor($vendor, Listing $listing, Order $order, $quantity)
    {
        // TODO: Email notification
        // if ($vendor->settings->email_notifications && $vendor->settings->new_order_notification) {
        //     Mail::to($vendor->user->email)->send(new NewOrderNotification($order, $listing, $quantity));
        // }

        // TODO: SMS notification
        // if ($vendor->settings->sms_notifications && $vendor->settings->new_order_notification) {
        //     SMS::send($vendor->phone, 'Yeni siparişiniz var: ' . $listing->title);
        // }

        // TODO: Database notification
        // $vendor->user->notify(new NewOrderNotification($order, $listing));
    }

    /**
     * Notify customer about manual delivery.
     *
     * @param Order $order
     * @param Listing $listing
     * @return void
     */
    protected function notifyCustomer(Order $order, Listing $listing)
    {
        // TODO: Email to customer
        // Mail::to($order->customer_email)->send(new ManualDeliveryInfo($order, $listing));
    }

    /**
     * Update delivery status.
     *
     * @param Order $order
     * @param string $status
     * @param string|null $note
     * @return void
     */
    public function updateDeliveryStatus(Order $order, $status, $note = null)
    {
        // Sipariş durumunu güncelle
        $order->update([
            'status' => $status,
            'note' => $note,
        ]);

        // Müşteriye bildirim gönder
        // TODO: Send status update notification
    }

    /**
     * Get estimated delivery date.
     *
     * @param Listing $listing
     * @return string
     */
    public function getEstimatedDeliveryDate(Listing $listing)
    {
        $processingDays = $listing->processing_days ?? 3;
        return now()->addDays($processingDays)->format('d.m.Y');
    }
}

