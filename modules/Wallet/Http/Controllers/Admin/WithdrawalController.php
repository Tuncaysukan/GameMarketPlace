<?php

namespace Modules\Wallet\Http\Controllers\Admin;

use Modules\Wallet\Entities\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController
{
    /**
     * Display all withdrawals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdrawals = Withdrawal::with('vendor', 'wallet')
            ->latest()
            ->paginate(25);

        return view('wallet::admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Display pending withdrawals.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $withdrawals = Withdrawal::with('vendor', 'wallet')
            ->pending()
            ->latest()
            ->paginate(25);

        return view('wallet::admin.withdrawals.pending', compact('withdrawals'));
    }

    /**
     * Approve withdrawal.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Bu talep zaten işleme alınmış.');
        }

        $withdrawal->update([
            'status' => 'completed',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // Wallet'tan çıkar
        $withdrawal->wallet->increment('total_withdrawn', $withdrawal->amount);

        return redirect()->back()
            ->with('success', 'Çekim talebi onaylandı.');
    }

    /**
     * Reject withdrawal.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Bu talep zaten işleme alınmış.');
        }

        $withdrawal->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_note' => $request->input('admin_note'),
        ]);

        // Bakiyeyi geri ekle
        $withdrawal->wallet->credit(
            $withdrawal->amount, 
            'withdrawal_rejected', 
            'Çekim talebi reddedildi'
        );

        return redirect()->back()
            ->with('success', 'Çekim talebi reddedildi ve bakiye iade edildi.');
    }
}

