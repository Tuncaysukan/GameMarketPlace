<div class="order-details-middle">
    <div class="table-responsive">
        <table class="table table-borderless order-details-table">
            <thead>
                <tr>
                    <th>{{ trans('storefront::account.product_name') }}</th>
                    <th>{{ trans('storefront::account.view_order.unit_price') }}</th>
                    <th>{{ trans('storefront::account.view_order.quantity') }}</th>
                    <th>{{ trans('storefront::account.view_order.line_total') }}</th>
                    <th>Ürün Teslimatı</th>
                </tr>
            </thead>

            <tbody>
                <?php
                
                    $codes = $order->stock_code;

                    if ($codes == NULL) {
                        $codes = [];
                    } else {
                        $codes = json_decode($codes, true);
                    }

                    $id = 0;
                
                ?>
                @foreach ($order->products as $product)
                    <tr>
                        <td>
                            <a href="{{ $product->url() }}" class="product-name">
                                {{ $product->name }}
                            </a>

                            @if ($product->hasAnyVariation())
                                <ul class="list-inline product-options">
                                    @foreach ($product->variations as $variation)
                                        <li>
                                            <label>{{ $variation->name }}:</label>
                                            {{ $variation->values()->first()?->label }}{{ $loop->last ? "" : "," }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($product->hasAnyOption())
                                <ul class="list-inline product-options">
                                    @foreach ($product->options as $option)
                                        <li>
                                            @if ($option->isFieldType())
                                                <label>{{ $option->name }}:</label> {{ $option->value }}
                                            @else
                                                <label>{{ $option->name }}:</label> {{ $option->values->implode('label', ', ') }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>

                        <td>
                            <label>{{ trans('storefront::account.view_order.unit_price') }}</label>

                            <span class="product-price">
                                {{ $product->unit_price->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                            </span>
                        </td>

                        <td>
                            <label>{{ trans('storefront::account.view_order.quantity') }}</label>

                            <span class="quantity">
                                {{ $product->qty }}
                            </span>
                        </td>

                        <td>
                            <label>{{ trans('storefront::account.view_order.line_total') }}</label>

                            <span class="product-price">
                                {{ $product->line_total->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                            </span>
                        </td>
                        <td>
                            @if (isset($codes[$id]))
                                <span class="product-price">
                                    <input class="form-control" value="{{ $codes[$id] }}" disabled>
                                </span>
                            @else
                                <span class="product-price">
                                    <input class="form-control" value="Teslim Edilmedi" disabled>
                                </span>
                            @endif
                        </td>
                    </tr>

                    <?php $id++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
