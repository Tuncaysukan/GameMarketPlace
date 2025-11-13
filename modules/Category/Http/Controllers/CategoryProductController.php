<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Response;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Http\Controllers\ProductSearch;

class CategoryProductController
{
    use ProductSearch;

    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @param Product $model
     * @param ProductFilter $productFilter
     *
     * @return Response
     */
    public function index($slug, Product $model, ProductFilter $productFilter)
    {
        request()->merge(['category' => $slug]);

        if (request()->expectsJson()) {
            return $this->searchProducts($model, $productFilter);
        }

        $category = Category::findBySlug($slug);
        
        // Alt kategorileri al
        $subCategories = Category::with(['files', 'products'])
            ->where('parent_id', $category->id)
            ->get();
        
        // Alt kategoriler var mı kontrol et
        if ($subCategories->isNotEmpty()) {
            // Alt kategoriler varsa kategori listesi göster
            return view('storefront::public.categories.show', [
                'category' => $category,
                'subCategories' => $subCategories,
            ]);
        }
        
        // Alt kategori yoksa ürün listesi göster
        return view('storefront::public.products.index', [
            'categoryName' => $category->name,
            'categoryBanner' => $category->banner->path,
            'categoryImage' => $category->category_image->path,
        ]);
    }
}
