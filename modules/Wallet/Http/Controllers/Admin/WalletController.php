<?php

namespace Modules\Wallet\Http\Controllers\Admin;

use Modules\Wallet\Entities\Wallet;
use Illuminate\Http\Request;

class WalletController
{
    /**
     * Display all wallets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = Wallet::with('vendor')
            ->latest()
            ->paginate(25);

        return view('wallet::admin.wallets.index', compact('wallets'));
    }
}

