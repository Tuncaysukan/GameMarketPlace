<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MoneyCheckController
{
    public function index()
    {
        $withdrawal_requests = DB::table('withdrawal_requests')
            ->join('users', 'users.id', 'withdrawal_requests.user_id')
            ->select('withdrawal_requests.*', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'users.balance as user_balance')
            ->orderByRaw('id ASC')
            ->get();

        return view('admin::money-check.index', compact('withdrawal_requests'));
    }

    public function status(Request $request, $id)
    {
        $withdrawal_requests = DB::table('withdrawal_requests')
            ->where('id', $id)
            ->first();
        
        if($withdrawal_requests)
        {
            $store = DB::table('withdrawal_requests')->where('id', $id)->where('status', 0)->update(
                [
                    "status" => $request->status
                ]
            );

            if($request->status == 1)
            {
                $user = DB::table('users')
                    ->where('id', $withdrawal_requests->user_id)
                    ->first();

                $usersStore = DB::table('users')->where('id', $user->id)->update(
                    [
                        "balance" => $user->balance-$withdrawal_requests->amount
                    ]
                );
            }

            if($store)
            {
                return redirect()->back()->with('success', 'Başarılı şekilde güncellendi.');
            }else{
                return redirect()->back()->with('error', 'Bir şeyler ters gitti, tekrar deneyiniz.');
            }            
        }else{
            return redirect()->back()->with('error', 'Bir şeyler ters gitti, tekrar deneyiniz.');
        }
    }
}
