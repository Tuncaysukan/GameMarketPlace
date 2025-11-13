<?php

namespace Modules\Listing\Admin;

use Modules\Admin\Ui\AdminTable;

class ListingTable extends AdminTable
{
    /**
     * Make table response for the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function make()
    {
        return $this->newTable()
            ->addColumn('title', function ($listing) {
                return $listing->title;
            })
            ->addColumn('vendor', function ($listing) {
                return $listing->vendor->shop_name ?? '-';
            })
            ->addColumn('category', function ($listing) {
                return $listing->category->name ?? '-';
            })
            ->addColumn('price', function ($listing) {
                return $listing->price->format();
            })
            ->addColumn('status', function ($listing) {
                $labels = [
                    'draft' => '<span class="label label-default">Taslak</span>',
                    'pending' => '<span class="label label-warning">Bekliyor</span>',
                    'approved' => '<span class="label label-success">Onaylandı</span>',
                    'rejected' => '<span class="label label-danger">Reddedildi</span>',
                    'inactive' => '<span class="label label-default">Pasif</span>',
                ];
                
                return $labels[$listing->status] ?? $listing->status;
            })
            ->addColumn('view_count', function ($listing) {
                return $listing->view_count;
            });
    }
}

