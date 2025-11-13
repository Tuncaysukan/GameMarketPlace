<section x-data="GridProducts({{ $gridProducts }})" class="grid-products-wrap">
    <div class="container">
        <div class="grid-products-wrap-inner">
            <div class="tab-products-header">
                <ul class="tabs">
                    @foreach ($gridProducts as $key => $tab)
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
