<?php

namespace Modules\Listing\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.marketplace'), function (Group $group) {
            $group->weight(30);
            
            $group->item(trans('listing::listings.listings'), function (Item $item) {
                $item->icon('fa fa-list');
                $item->weight(3);
                $item->url('admin/listings');
                $item->isActiveWhen(url('admin/listings*'));

                // Bekleyen ilan sayısını göster
                try {
                    if (class_exists('\Modules\Listing\Entities\Listing')) {
                        $pendingCount = \Modules\Listing\Entities\Listing::pending()->count();
                        if ($pendingCount > 0) {
                            $item->badge($pendingCount, 'warning');
                        }
                    }
                } catch (\Exception $e) {
                    // Hata vermesin
                }
            });
        });

        return $menu;
    }
}
