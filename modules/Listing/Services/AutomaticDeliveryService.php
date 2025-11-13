<?php

namespace Modules\Listing\Services;

use Modules\Listing\Entities\Listing;
use Modules\Listing\Entities\ListingStockItem;
use Modules\Order\Entities\Order;

class AutomaticDeliveryService
{
    /**
     * Process automatic delivery for an order.
     *
     * @param Listing $listing
     * @param Order $order
     * @param int $quantity
     * @return array
     * @throws \Exception
     */
    public function processDelivery(Listing $listing, Order $order, $quantity = 1)
    {
        if (!$listing->isAutomaticDelivery()) {
            throw new \Exception('Bu ilan otomatik teslimat için yapılandırılmamış.');
        }

        // Stok kontrolü
        $availableStock = $listing->availableStockItems()->count();
        
        if ($availableStock < $quantity) {
            throw new \Exception('Yetersiz stok. Mevcut stok: ' . $availableStock);
        }

        // Stok öğelerini al
        $stockItems = $listing->availableStockItems()
            ->take($quantity)
            ->get();

        $deliveredItems = [];

        foreach ($stockItems as $stockItem) {
            // Stok öğesini satıldı olarak işaretle
            $stockItem->markAsSold($order->id);

            $deliveredItems[] = [
                'id' => $stockItem->id,
                'data' => $stockItem->stock_data,
            ];
        }

        // Listing stok miktarını güncelle
        $listing->updateStats($quantity, $listing->price->amount() * $quantity);

        return [
            'success' => true,
            'items' => $deliveredItems,
            'message' => 'Ürün bilgileri başarıyla teslim edildi.',
        ];
    }

    /**
     * Reserve stock items for an order.
     *
     * @param Listing $listing
     * @param int $quantity
     * @return array
     * @throws \Exception
     */
    public function reserveStock(Listing $listing, $quantity = 1)
    {
        $availableStock = $listing->availableStockItems()->count();
        
        if ($availableStock < $quantity) {
            throw new \Exception('Yetersiz stok.');
        }

        $stockItems = $listing->availableStockItems()
            ->take($quantity)
            ->get();

        $reservedItems = [];

        foreach ($stockItems as $stockItem) {
            $stockItem->reserve();
            $reservedItems[] = $stockItem->id;
        }

        return [
            'success' => true,
            'reserved_items' => $reservedItems,
        ];
    }

    /**
     * Release reserved stock items.
     *
     * @param array $stockItemIds
     * @return void
     */
    public function releaseReservedStock(array $stockItemIds)
    {
        ListingStockItem::whereIn('id', $stockItemIds)
            ->where('status', 'reserved')
            ->update(['status' => 'available']);
    }

    /**
     * Add stock items to listing.
     *
     * @param Listing $listing
     * @param array $stockData
     * @return int
     */
    public function addStock(Listing $listing, array $stockData)
    {
        $count = 0;

        foreach ($stockData as $data) {
            if (!empty(trim($data))) {
                $listing->stockItems()->create([
                    'stock_data' => trim($data),
                    'status' => 'available',
                ]);
                $count++;
            }
        }

        // Listing stok sayısını güncelle
        $listing->update([
            'stock_qty' => $listing->availableStockItems()->count(),
            'in_stock' => $listing->availableStockItems()->count() > 0,
        ]);

        return $count;
    }

    /**
     * Check if listing has sufficient stock.
     *
     * @param Listing $listing
     * @param int $quantity
     * @return bool
     */
    public function hasStock(Listing $listing, $quantity = 1)
    {
        return $listing->availableStockItems()->count() >= $quantity;
    }
}

