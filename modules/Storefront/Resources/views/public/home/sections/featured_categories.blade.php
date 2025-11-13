<section x-data="FeaturedCategories({{ $featuredCategories['categories'] }})" class="featured-categories-wrap">
    <div class="container">
        <div class="featured-categories-header">

            <div x-ref="featuredCategoriesSlider" class="featured-categories-slider swiper">
                <div class="swiper-wrapper">
                    @foreach ($featuredCategories['categories'] as $key => $tab)
                        <div class="swiper-slide">
                            <div
                                class="tab-item"
                                :class="classes({{ $key }})"
                                @click="changeTab({{ $key }})"
                            >
                                <div class="featured-category-image">
                                    @if ($tab['logo']->path)
                                        <img
                                            src="{{ $tab['logo']->path }}"
                                            alt="{{ $tab['name'] }}"
                                            loading="lazy"
                                        />
                                    @else
                                        <img
                                            src="{{ asset('build/assets/image-placeholder.png') }}"
                                            class="image-placeholder"
                                            alt="{{ $tab['name'] }}"
                                            loading="lazy"
                                        />
                                    @endif
                                </div>
                                
                                <span class="featured-category-name">
                                    {{ $tab['name'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
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
</section>
