<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index() {

        $intent = auth()->user()->createSetupIntent();

        return response()->json(['intent' => $intent]);
    }

    public function store(Request $request)
    {
        dd($request->all());

    }
}
