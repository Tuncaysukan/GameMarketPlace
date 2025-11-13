<section x-data="ProductTabsThree({{ $productTabsThree['tabs'] }})" class="landscape-tab-products-wrap">
    <div class="container">
        <div class="landscape-right-tab-products-inner">
            <div class="tab-products-header">
                <h5 class="section-title">{{ $productTabsThree['title'] }}</h5>

                <div class="tab-products-header-overflow">
                    <ul class="tabs">
                        @foreach ($productTabsThree['tabs'] as $key => $tab)
                            <li
                                class="tab-item"
                                :class="classes({{ $key }})"
                                @click="changeTab({{ $key }})"
                            >
                                {{ $tab }}
                            </li>
                        @endforeach
                    </ul>

                    <hr>
                </div>
            </div>

            <div class="tab-content">
                <div class="products-grid">
                    @foreach (range(0, 11) as $skeleton)
                        <div class="product-skeleton">
                            @include('storefront::public.partials.product_card_skeleton')
                        </div>
                    @endforeach

                    <template x-for="product in products" :key="product.id">
                        <div class="product-item">
                            @include('storefront::public.partials.product_card')
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>