<?php

namespace Modules\Listing\Http\Controllers\Admin;

use Modules\Listing\Entities\Listing;
use Modules\Listing\Admin\ListingTable;
use Modules\Admin\Traits\HasCrudActions;
use Illuminate\Http\Request;

class ListingController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = Listing::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'listing::listings.listing';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'listing::admin.listings';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['vendor', 'category', 'images'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->getModel()
                ->with($this->with)
                ->latest()
                ->paginate(25);
        }

        // İstatistikler
        $stats = [
            'total' => Listing::count(),
            'pending' => Listing::pending()->count(),
            'active' => Listing::active()->count(),
            'featured' => Listing::featured()->count(),
        ];

        return view("{$this->viewPath}.index", compact('stats'));
    }

    /**
     * Display pending listings for approval.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $listings = Listing::with('vendor', 'category')
            ->pending()
            ->latest()
            ->paginate(25);

        return view('listing::admin.listings.pending', compact('listings'));
    }

    /**
     * Approve the listing.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $listing = Listing::findOrFail($id);
        
        if (!$listing->isPending()) {
            return redirect()->back()
                ->with('error', 'Bu ilan zaten işleme alınmış.');
        }

        $listing->approve(auth()->id());

        // TODO: Event fire et - ListingApproved
        // event(new ListingApproved($listing));

        return redirect()->back()
            ->with('success', trans('listing::listings.listing_approved'));
    }

    /**
     * Reject the listing.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $listing = Listing::findOrFail($id);
        
        if (!$listing->isPending()) {
            return redirect()->back()
                ->with('error', 'Bu ilan zaten işleme alınmış.');
        }

        $listing->reject($request->input('rejection_reason'));

        // TODO: Event fire et - ListingRejected
        // event(new ListingRejected($listing));

        return redirect()->back()
            ->with('success', trans('listing::listings.listing_rejected'));
    }

    /**
     * Toggle featured status.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFeatured($id)
    {
        $listing = Listing::findOrFail($id);
        
        $listing->update([
            'is_featured' => !$listing->is_featured,
            'featured_expires_at' => $listing->is_featured ? null : now()->addDays(30),
        ]);

        return redirect()->back()
            ->with('success', 'İlan vitrin durumu güncellendi.');
    }

    /**
     * Toggle active status.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive($id)
    {
        $listing = Listing::findOrFail($id);
        
        $listing->update(['is_active' => !$listing->is_active]);

        return redirect()->back()
            ->with('success', 'İlan durumu güncellendi.');
    }
}

