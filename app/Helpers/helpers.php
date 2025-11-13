<?php

if (!function_exists('format_price')) {
    /**
     * Format price with currency
     *
     * @param float|Modules\Support\Money $price
     * @param string|null $currency
     * @return string
     */
    function format_price($price, $currency = null)
    {
        // Eğer Money objesi ise
        if ($price instanceof \Modules\Support\Money) {
            $amount = $price->amount();
            $currency = $currency ?? $price->currency();
        } else {
            // Eğer float/int ise
            $amount = $price;
            $currency = $currency ?? 'TL';
        }
        
        return number_format($amount, 2, ',', '.') . ' ' . $currency;
    }
}

