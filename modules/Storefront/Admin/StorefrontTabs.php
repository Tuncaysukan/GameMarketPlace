<?php

namespace Modules\Storefront\Admin;

use Modules\Admin\Ui\Tab;
use Modules\Admin\Ui\Tabs;
use Modules\Tag\Entities\Tag;
use Modules\Storefront\Banner;
use Modules\Menu\Entities\Menu;
use Modules\Page\Entities\Page;
use Modules\Media\Entities\File;
use Modules\Brand\Entities\Brand;
use Modules\Slider\Entities\Slider;
use Illuminate\Support\Facades\Cache;
use Modules\FlashSale\Entities\FlashSale;
use Modules\Product\Repositories\ProductRepository;

class StorefrontTabs extends Tabs
{
    /**
     * Make new tabs with groups.
     *
     * @return void
     */
    public function make()
    {
        $this->group('general_settings', trans('storefront::storefront.tabs.group.general_settings'))
            ->active()
            ->add($this->general())
            ->add($this->logo())
            ->add($this->menus())
            ->add($this->footer())
            ->add($this->newsletter())
            ->add($this->features())
            ->add($this->productPage())
            ->add($this->socialLinks());


        $this->group('home_page_sections', trans('storefront::storefront.tabs.group.home_page_sections'))
            ->add($this->sliderBanners())
            ->add($this->threeColumnFullWidthBanners())
            ->add($this->featuredCategories())
            ->add($this->productTabsOne())
            ->add($this->productTabsTwo())
            ->add($this->productTabsThree())
            ->add($this->productTabsFour())
            ->add($this->productTabsFive())
            ->add($this->productTabsSix())
            ->add($this->productTabsSeven())
            ->add($this->productTabsEight())
            ->add($this->productTabsNine())
            ->add($this->productTabsTen())
            ->add($this->topBrands())
            ->add($this->flashSaleAndVerticalProducts())
            ->add($this->twoColumnBanners())
            ->add($this->productGrid())
            ->add($this->threeColumnBanners())
            ->add($this->oneColumnBanner())
            ->add($this->blogs());
    }

 
    private function general()
    {
        return tap(new Tab('general', trans('storefront::storefront.tabs.general')), function (Tab $tab) {
            $tab->active();
            $tab->weight(5);
            $tab->fields(['storefront_slider', 'storefront_copyright_text']);
            $tab->view('storefront::admin.storefront.tabs.general', [
                'display_fonts' => [
                    'Poppins' => 'Poppins',
                    'Rubik' => 'Rubik',
                    'Roboto' => 'Roboto',
                    'Open Sans' => 'Open Sans',
                    'Montserrat' => 'Montserrat',
                    'Nunito' => 'Nunito',
                    'Raleway' => 'Raleway',
                    'Oswald' => 'Oswald',
                    'Quicksand' => 'Quicksand',
                    'Hind' => 'Hind',
                    'Fira Sans' => 'Fira Sans',
                    'Mukta' => 'Mukta',
                    'Karla' => 'Karla',
                    'Barlow' => 'Barlow',
                    'Source Sans 3' => 'Source Sans 3',
                    'IBM Plex Sans' => 'IBM Plex Sans',
                    'Work Sans' => 'Work Sans',
                    'Outfit' => 'Outfit',
                    'Inter' => 'Inter',
                    'Manrope' => 'Manrope',
                    'Ubuntu' => 'Ubuntu',
                    'Lato' => 'Lato',
                    'Noto Sans' => 'Noto Sans',
                ],
                'pages' => $this->getPages(),
                'sliders' => $this->getSliders(),
            ]);
        });
    }


    private function getPages()
    {
        return Page::all()->pluck('name', 'id')
            ->prepend(trans('storefront::storefront.form.please_select'), '');
    }


    private function getSliders()
    {
        return Slider::all()->sortBy('name')->pluck('name', 'id')
            ->prepend(trans('storefront::storefront.form.please_select'), '');
    }


    private function logo()
    {
        return tap(new Tab('logo', trans('storefront::storefront.tabs.logo')), function (Tab $tab) {
            $tab->weight(10);
            $tab->view('storefront::admin.storefront.tabs.logo', [
                'favicon' => $this->getMedia(setting('storefront_favicon')),
                'headerLogo' => $this->getMedia(setting('storefront_header_logo')),
                'footerLogo' => $this->getMedia(setting('storefront_footer_logo')),
                'mailLogo' => $this->getMedia(setting('storefront_mail_logo')),
            ]);
        });
    }


    private function getMedia($fileId)
    {
        return Cache::rememberForever(md5("files.{$fileId}"), function () use ($fileId) {
            return File::findOrNew($fileId);
        });
    }


    private function menus()
    {
        return tap(new Tab('menus', trans('storefront::storefront.tabs.menus')), function (Tab $tab) {
            $tab->weight(15);

            $tab->fields([
                'storefront_primary_menu',
                'storefront_category_menu',
                'storefront_top_nav_menu',
                'storefront_footer_menu',
                'storefront_footer_menu_title',
            ]);

            $tab->view('storefront::admin.storefront.tabs.menus', [
                'menus' => $this->getMenus(),
            ]);
        });
    }


    private function getMenus()
    {
        return Menu::all()->pluck('name', 'id')
            ->prepend(trans('storefront::storefront.form.please_select'), '');
    }


    private function footer()
    {
        return tap(new Tab('footer', trans('storefront::storefront.tabs.footer')), function (Tab $tab) {
            $tab->weight(17);
            $tab->view('storefront::admin.storefront.tabs.footer', [
                'tags' => Tag::list(),
                'acceptedPaymentMethodsImage' => $this->getMedia(setting('storefront_accepted_payment_methods_image')),
            ]);
        });
    }


    private function newsletter()
    {
        if (!setting('newsletter_enabled')) {
            return;
        }

        return tap(new Tab('newsletter', trans('storefront::storefront.tabs.newsletter')), function (Tab $tab) {
            $tab->weight(18);
            $tab->view('storefront::admin.storefront.tabs.newsletter', [
                'newsletterBgImage' => $this->getMedia(setting('storefront_newsletter_bg_image')),
            ]);
        });
    }


    private function features()
    {
        return tap(new Tab('features', trans('storefront::storefront.tabs.features')), function (Tab $tab) {
            $tab->weight(20);
            $tab->view('storefront::admin.storefront.tabs.features');
        });
    }


    private function productPage()
    {
        return tap(new Tab('product_page', trans('storefront::storefront.tabs.product_page')), function (Tab $tab) {
            $tab->weight(22);
            $tab->view('storefront::admin.storefront.tabs.product_page', [
                'banner' => Banner::getProductPageBanner(),
            ]);
        });
    }


    private function socialLinks()
    {
        return tap(new Tab('social_links', trans('storefront::storefront.tabs.social_links')), function (Tab $tab) {
            $tab->weight(25);

            $tab->fields([
                'storefront_fb_link',
                'storefront_twitter_link',
                'storefront_instagram_link',
                'storefront_linkedin_link',
                'storefront_pinterest_link',
                'storefront_gplus_link',
                'storefront_youtube_link',
            ]);

            $tab->view('storefront::admin.storefront.tabs.social_links');
        });
    }


    private function sliderBanners()
    {
        return tap(new Tab('slider_banners', trans('storefront::storefront.tabs.slider_banners')), function (Tab $tab) {
            $tab->weight(30);
            $tab->view('storefront::admin.storefront.tabs.slider_banners', [
                'banners' => Banner::getSliderBanners(),
            ]);
        });
    }


    private function threeColumnFullWidthBanners()
    {
        return tap(new Tab('three_column_full_width_banners', trans('storefront::storefront.tabs.three_column_full_width_banners')), function (Tab $tab) {
            $tab->weight(35);
            $tab->view('storefront::admin.storefront.tabs.three_column_full_width_banners', [
                'banners' => Banner::getThreeColumnFullWidthBanners(),
            ]);
        });
    }


    private function featuredCategories()
    {
        return tap(new Tab('featured_categories', trans('storefront::storefront.tabs.featured_categories')), function (Tab $tab) {
            $tab->weight(40);
            $categoryProducts = [];
            for ($i = 1; $i <= 25; $i++) {
                $categoryProducts[$i] = $this->getProductListFromSetting("storefront_featured_categories_section_category_{$i}_products");
            }

            $tab->view('storefront::admin.storefront.tabs.featured_categories', [
                'categoryProducts' => $categoryProducts,
            ]);
        });
    }


    private function getProductListFromSetting($key)
    {
        return ProductRepository::list(setting($key, []));
    }


    private function productTabsOne()
    {
        return tap(new Tab('product_tabs_one', trans('storefront::storefront.tabs.product_tabs_one')), function (Tab $tab) {
            $tab->weight(45);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_one', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_1_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_1_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_1_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_1_section_tab_4_products'),
            ]);
        });
    }


    private function topBrands()
    {
        if (!auth()->user()->hasAccess(['admin.brands.index'])) {
            return;
        }

        return tap(new Tab('top_brands', trans('storefront::storefront.tabs.top_brands')), function (Tab $tab) {
            $tab->weight(50);
            $tab->view('storefront::admin.storefront.tabs.top_brands', [
                'brands' => Brand::list(),
            ]);
        });
    }


    private function flashSaleAndVerticalProducts()
    {
        return tap(new Tab('flash_sale_and_vertical_products', trans('storefront::storefront.tabs.flash_sale_and_vertical_products')), function (Tab $tab) {
            $tab->weight(60);
            $tab->view('storefront::admin.storefront.tabs.flash_sale_and_vertical_products', [
                'flashSales' => $this->getFlashSales(),
                'verticalProductsOne' => $this->getProductListFromSetting('storefront_vertical_products_1_products'),
                'verticalProductsTwo' => $this->getProductListFromSetting('storefront_vertical_products_2_products'),
                'verticalProductsThree' => $this->getProductListFromSetting('storefront_vertical_products_3_products'),
            ]);
        });
    }


    private function getFlashSales()
    {
        return FlashSale::all()->pluck('campaign_name', 'id')
            ->prepend(trans('admin::admin.form.please_select'), '');
    }


    private function twoColumnBanners()
    {
        return tap(new Tab('two_column_banners', trans('storefront::storefront.tabs.two_column_banners')), function (Tab $tab) {
            $tab->weight(65);
            $tab->view('storefront::admin.storefront.tabs.two_column_banners', [
                'banners' => Banner::getTwoColumnBanners(),
            ]);
        });
    }


    private function productGrid()
    {
        return tap(new Tab('product_grid', trans('storefront::storefront.tabs.product_grid')), function (Tab $tab) {
            $tab->weight(70);
            $tab->view('storefront::admin.storefront.tabs.product_grid', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_grid_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_grid_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_grid_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_grid_section_tab_4_products'),
            ]);
        });
    }


    private function threeColumnBanners()
    {
        return tap(new Tab('three_column_banners', trans('storefront::storefront.tabs.three_column_banners')), function (Tab $tab) {
            $tab->weight(75);
            $tab->view('storefront::admin.storefront.tabs.three_column_banners', [
                'banners' => Banner::getThreeColumnBanners(),
            ]);
        });
    }


    private function productTabsTwo()
    {
        return tap(new Tab('product_tabs_two', trans('storefront::storefront.tabs.product_tabs_two')), function (Tab $tab) {
            $tab->weight(80);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_two', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_2_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_2_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_2_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_2_section_tab_4_products'),
            ]);
        });
    }


    private function oneColumnBanner()
    {
        return tap(new Tab('one_column_banner', trans('storefront::storefront.tabs.one_column_banner')), function (Tab $tab) {
            $tab->weight(85);
            $tab->view('storefront::admin.storefront.tabs.one_column_banner', [
                'banner' => Banner::getOneColumnBanner(),
            ]);
        });
    }


    private function blogs()
    {
        return tap(new Tab('blogs', trans('storefront::storefront.tabs.blogs')), function (Tab $tab) {
            $tab->weight(85);
            $tab->view('storefront::admin.storefront.tabs.blogs');
        });
    }


    private function productTabsThree()
    {
        return tap(new Tab('product_tabs_three', trans('storefront::storefront.tabs.product_tabs_three')), function (Tab $tab) {
            $tab->weight(46);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_three', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_3_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_3_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_3_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_3_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsFour()
    {
        return tap(new Tab('product_tabs_four', trans('storefront::storefront.tabs.product_tabs_four')), function (Tab $tab) {
            $tab->weight(47);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_four', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_4_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_4_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_4_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_4_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsFive()
    {
        return tap(new Tab('product_tabs_five', trans('storefront::storefront.tabs.product_tabs_five')), function (Tab $tab) {
            $tab->weight(48);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_five', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_5_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_5_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_5_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_5_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsSix()
    {
        return tap(new Tab('product_tabs_six', trans('storefront::storefront.tabs.product_tabs_six')), function (Tab $tab) {
            $tab->weight(49);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_six', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_6_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_6_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_6_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_6_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsSeven()
    {
        return tap(new Tab('product_tabs_seven', trans('storefront::storefront.tabs.product_tabs_seven')), function (Tab $tab) {
            $tab->weight(50);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_seven', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_7_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_7_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_7_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_7_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsEight()
    {
        return tap(new Tab('product_tabs_eight', trans('storefront::storefront.tabs.product_tabs_eight')), function (Tab $tab) {
            $tab->weight(51);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_eight', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_8_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_8_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_8_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_8_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsNine()
    {
        return tap(new Tab('product_tabs_nine', trans('storefront::storefront.tabs.product_tabs_nine')), function (Tab $tab) {
            $tab->weight(52);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_nine', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_9_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_9_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_9_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_9_section_tab_4_products'),
            ]);
        });
    }


    private function productTabsTen()
    {
        return tap(new Tab('product_tabs_ten', trans('storefront::storefront.tabs.product_tabs_ten')), function (Tab $tab) {
            $tab->weight(53);
            $tab->view('storefront::admin.storefront.tabs.product_tabs_ten', [
                'tabOneProducts' => $this->getProductListFromSetting('storefront_product_tabs_10_section_tab_1_products'),
                'tabTwoProducts' => $this->getProductListFromSetting('storefront_product_tabs_10_section_tab_2_products'),
                'tabThreeProducts' => $this->getProductListFromSetting('storefront_product_tabs_10_section_tab_3_products'),
                'tabFourProducts' => $this->getProductListFromSetting('storefront_product_tabs_10_section_tab_4_products'),
            ]);
        });
    }
}
