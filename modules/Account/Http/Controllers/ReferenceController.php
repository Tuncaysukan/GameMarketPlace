<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $references_users = DB::table('users')
            ->where('reference', Auth()->user()->id)
            ->orderByRaw('id DESC')
            ->get();

        $references_orders = DB::table('references_logs')
            ->join('users', 'users.id', 'references_logs.user_id')
            ->select('references_logs.*', 'users.first_name as user_first_name', 'users.last_name as user_last_name')
            ->where('references_logs.reference_id', Auth()->user()->id)
            ->orderByRaw('references_logs.id DESC')
            ->get();

        return view('storefront::public.account.reference.index', compact('references_users', 'references_orders'));
    }
}
