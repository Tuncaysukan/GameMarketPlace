<?php

namespace Modules\Listing\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Listing\Entities\Listing;
use Modules\Listing\Entities\ListingView;
use Modules\Category\Entities\Category;

class ListingController
{
    /**
     * Display a listing of all listings.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Listing::active()
            ->available()
            ->with('vendor', 'images.file', 'category');

        // Kategori filtresi
        if ($request->has('category')) {
            $query->byCategory($request->input('category'));
        }

        // Arama
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Fiyat aralığı
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sıralama
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $listings = $query->paginate(24);

        // Kategoriler
        $categories = Category::tree();

        return view('listing::public.index', compact('listings', 'categories'));
    }

    /**
     * Display the specified listing.
     *
     * @param string $slug
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $id)
    {
        $listing = Listing::active()
            ->with('vendor', 'images.file', 'category', 'stockItems')
            ->findOrFail($id);

        // URL slug kontrolü
        if ($listing->slug !== $slug) {
            return redirect()->route('listings.show', [
                'slug' => $listing->slug,
                'id' => $listing->id
            ], 301);
        }

        // Görüntülenme kaydı
        $this->recordView($listing);

        // İlan sayacını artır
        $listing->incrementViewCount();

        // Benzer ilanlar
        $relatedListings = Listing::active()
            ->available()
            ->where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->with('images.file', 'vendor')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Satıcının diğer ilanları
        $vendorListings = Listing::active()
            ->available()
            ->where('vendor_id', $listing->vendor_id)
            ->where('id', '!=', $listing->id)
            ->with('images.file')
            ->take(4)
            ->get();

        return view('listing::public.show', compact('listing', 'relatedListings', 'vendorListings'));
    }

    /**
     * Record listing view.
     *
     * @param Listing $listing
     * @return void
     */
    protected function recordView(Listing $listing)
    {
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();

        // Aynı IP'den son 1 saat içinde görüntüleme varsa kaydetme
        $recentView = ListingView::where('listing_id', $listing->id)
            ->where('ip_address', $ipAddress)
            ->where('viewed_at', '>', now()->subHour())
            ->first();

        if (!$recentView) {
            ListingView::create([
                'listing_id' => $listing->id,
                'user_id' => auth()->id(),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'viewed_at' => now(),
            ]);
        }
    }
}

