<?php

namespace Modules\Storefront\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Storefront\Banner;
use Modules\Storefront\Feature;
use Modules\Brand\Entities\Brand;
use Illuminate\Support\Collection;
use Modules\Blog\Entities\BlogPost;
use Modules\Slider\Entities\Slider;
use Illuminate\Support\Facades\Cache;
use Modules\Category\Entities\Category;

class HomePageComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose($view)
    {
        $view->with([
            'slider' => Slider::findWithSlides(setting('storefront_slider')),
            'sliderBanners' => Banner::getSliderBanners(),
            'features' => Feature::all(),
            'featuredCategories' => $this->featuredCategoriesSection(),
            'threeColumnFullWidthBanners' => $this->threeColumnFullWidthBanners(),
            'productTabsOne' => $this->productTabsOne(),
            'productTabsTwo' => $this->productTabsTwo(),
            'productTabsThree' => $this->productTabsThree(),
            'productTabsFour' => $this->productTabsFour(),
            'productTabsFive' => $this->productTabsFive(),
            'productTabsSix' => $this->productTabsSix(),
            'productTabsSeven' => $this->productTabsSeven(),
            'productTabsEight' => $this->productTabsEight(),
            'productTabsNine' => $this->productTabsNine(),
            'productTabsTen' => $this->productTabsTen(),
            'topBrands' => $this->topBrands(),
            'flashSale' => $this->flashSale(),
            'twoColumnBanners' => $this->twoColumnBanners(),
            'gridProducts' => $this->gridProducts(),
            'threeColumnBanners' => $this->threeColumnBanners(),
            'oneColumnBanner' => $this->oneColumnBanner(),
            'blog' => $this->blog(),
        ]);
    }


    private function featuredCategoriesSection()
    {
        if (!setting('storefront_featured_categories_section_enabled')) {
            return;
        }

        return [
            'title' => setting('storefront_featured_categories_section_title'),
            'subtitle' => setting('storefront_featured_categories_section_subtitle'),
            'categories' => $this->getFeaturedCategories(),
        ];
    }


    private function getFeaturedCategories()
    {
        $categoryIds = Collection::times(25, function ($number) {
            if (!is_null(setting("storefront_featured_categories_section_category_{$number}_product_type"))) {
                return setting("storefront_featured_categories_section_category_{$number}_category_id");
            }
        })->filter();

        return Category::with('files')
            ->whereIn('id', $categoryIds)
            ->when($categoryIds->isNotEmpty(), function ($query) use ($categoryIds) {
                $query->orderByRaw("FIELD(id, {$categoryIds->filter()->implode(',')})");
            })
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'logo' => $category->logo,
                ];
            });
    }


    private function threeColumnFullWidthBanners()
    {
        if (setting('storefront_three_column_full_width_banners_enabled')) {
            return Banner::getThreeColumnFullWidthBanners();
        }
    }


    private function productTabsOne()
    {
        if (!setting('storefront_product_tabs_1_section_enabled')) {
            return;
        }

        return Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_1_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_1_section_tab_{$number}_title");
            }
        })->filter();
    }


    private function topBrands()
    {
        if (!setting('storefront_top_brands_section_enabled')) {
            return collect();
        }

        $topBrandIds = setting('storefront_top_brands', []);

        return Cache::rememberForever(md5('storefront_top_brands:' . serialize($topBrandIds)), function () use ($topBrandIds) {
            return Brand::with('files')
                ->whereIn('id', $topBrandIds)
                ->when(!empty($topBrandIds), function ($query) use ($topBrandIds) {
                    $topBrandIdsString = collect($topBrandIds)->filter()->implode(',');

                    $query->orderByRaw("FIELD(id, {$topBrandIdsString})");
                })
                ->get()
                ->map(function (Brand $brand) {
                    return [
                        'url' => $brand->url(),
                        'logo' => $brand->getLogoAttribute(),
                    ];
                });
        });
    }


    private function flashSale()
    {
        return [
            'title' => setting('storefront_flash_sale_title'),
            'vertical_products_1_title' => setting('storefront_vertical_products_1_title'),
            'vertical_products_2_title' => setting('storefront_vertical_products_2_title'),
            'vertical_products_3_title' => setting('storefront_vertical_products_3_title'),
        ];
    }


    private function twoColumnBanners()
    {
        if (setting('storefront_two_column_banners_enabled')) {
            return Banner::getTwoColumnBanners();
        }
    }


    private function gridProducts()
    {
        if (!setting('storefront_product_grid_section_enabled')) {
            return;
        }

        return Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_grid_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_grid_section_tab_{$number}_title");
            }
        })->filter();
    }


    private function threeColumnBanners()
    {
        if (setting('storefront_three_column_banners_enabled')) {
            return Banner::getThreeColumnBanners();
        }
    }


    private function productTabsTwo()
    {
        if (!setting('storefront_product_tabs_2_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_2_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_2_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_2_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function oneColumnBanner()
    {
        if (setting('storefront_one_column_banner_enabled')) {
            return Banner::getOneColumnBanner();
        }
    }


    private function blog()
    {
        if (setting('storefront_blogs_section_enabled')) {
            $blogPosts = BlogPost::published()
                ->latest()
                ->take(setting('storefront_recent_blogs') ?? 10)
                ->get();

            return [
                'title' => setting('storefront_blogs_section_title'),
                'blogPosts' => $blogPosts,
            ];
        }
    }


    private function productTabsThree()
    {
        if (!setting('storefront_product_tabs_3_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_3_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_3_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_3_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsFour()
    {
        if (!setting('storefront_product_tabs_4_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_4_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_4_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_4_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsFive()
    {
        if (!setting('storefront_product_tabs_5_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_5_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_5_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_5_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsSix()
    {
        if (!setting('storefront_product_tabs_6_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_6_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_6_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_6_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsSeven()
    {
        if (!setting('storefront_product_tabs_7_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_7_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_7_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_7_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsEight()
    {
        if (!setting('storefront_product_tabs_8_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_8_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_8_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_8_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsNine()
    {
        if (!setting('storefront_product_tabs_9_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_9_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_9_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_9_section_title'),
            'tabs' => $tabs,
        ];
    }


    private function productTabsTen()
    {
        if (!setting('storefront_product_tabs_10_section_enabled')) {
            return;
        }

        $tabs = Collection::times(4, function ($number) {
            if (!is_null(setting("storefront_product_tabs_10_section_tab_{$number}_product_type"))) {
                return setting("storefront_product_tabs_10_section_tab_{$number}_title");
            }
        })->filter();

        return [
            'title' => setting('storefront_product_tabs_10_section_title'),
            'tabs' => $tabs,
        ];
    }
}
