<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function index()
    {
        $plans = Plan::get();

        return response()->json(['plans' => $plans]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function show(Plan $plan, Request $request)
    {
        $intent = auth()->user()->createSetupIntent();

        return response()->json(['plan' => $plan, 'intent' => $intent]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function subscription(Request $request)
    {
        $plan = Plan::find($request->plan);
        $subscription = $request->user()->newSubscription($request->plan, $plan->stripe_plan)
            ->create($request->token);

        return response()->json(['success' => 200]);
    }

    public function getToken(Request $request)
    {
        $intent = auth()->user()->createSetupIntent();
        $user = User::find(Auth::user()->id);
        $user->asStripeCustomer();
        $user->applyBalance(-500);
  /*      $transactions = $user->balanceTransactions();

        foreach ($transactions as $transaction) {
            // Transaction amount...
            $amount = $transaction->amount(); // $2.31

            // Retrieve the related invoice when available...
            $invoice = $transaction->invoice();
        }*/
        dd($user->balance());

        return response()->json(['intent' => $intent]);
    }

    public function donate(Request $request)
    {
        $plan = Plan::find($request->plan);
        $subscription = $request->user()->newSubscription($request->plan, $plan->stripe_plan)
            ->create($request->token);

        return response()->json(['success' => 200]);
    }
}
