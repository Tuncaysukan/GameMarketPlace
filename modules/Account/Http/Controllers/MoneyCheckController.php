<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoneyCheckController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {        
        $withdrawal_requests = DB::table('withdrawal_requests')
            ->where('user_id', Auth()->user()->id)
            ->orderByRaw('id DESC')
            ->get();

        return view('storefront::public.account.money-check.index', compact('withdrawal_requests'));
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'bank' => ['required'],
            'iban' => ['required'],
            'amount' => ['required'],
        ]);
        
        if($request->amount > Auth()->user()->balance)
        {
            return redirect()->back()->with('error', 'Bakiyenizden yüksek bir tutar girdiniz.');
        }
    
        $withdrawal_requests = DB::table('withdrawal_requests')
            ->where('user_id', Auth()->user()->id)
            ->where('status', 0)
            ->first();

        if($withdrawal_requests)
        {
            return redirect()->back()->with('error', 'Sonuçlanmamış para çekme talebiniz mevcut, yenisini oluşturmak için talebinizin sonuçlanmasını bekleyiniz.');
        }

        $store = DB::table('withdrawal_requests')->insert(
            [
                "user_id" => Auth()->user()->id,
                "bank" => trim($request->bank),
                "iban" => trim($request->iban),
                "amount" => trim($request->amount)
            ]
        );

        if($store)
        {
            return redirect()->back()->with('success', 'Para çekme isteğiniz başarılı şekilde gönderilmiştir.');
        }else{
            return redirect()->back()->with('error', 'Bir şeyler ters gitti, daha sonra tekrar deneyiniz.');
        }
    }
}
