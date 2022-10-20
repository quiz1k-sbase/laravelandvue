<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class BalanceController extends Controller
{
    public function index() {

        $intent = auth()->user()->createSetupIntent();

        return response()->json(['intent' => $intent]);
    }

    public function store(Request $request)
    {
        $amount = ($request->amount) * 100;
        $paymentMethod = $request->payment_method;

        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $paymentMethod = $user->addPaymentMethod($paymentMethod);

        $response = $user->charge($amount, $paymentMethod->id);

        $balance = Balance::create([
            'user_id' => Auth::user()->id,
            'balance' => User::where('id', '=', Auth::user()->id)->value('balance'),
            'status' => $response->status
        ]);

        if ($response->status === 'succeeded')
        {
            $stripe = new StripeClient(
                'sk_test_51LtqN6Dn7JPIapKM4pr8MYRS0pTBYmDBLawyyYqx3lHKWopcnL0wJxmhA8UhhmwwIFdVUdJkjadY8fw71hsuST6a00YaMoCemi'
            );
            $balanceUser = $stripe->customers->createBalanceTransaction(
                auth()->user()->stripe_id,
                ['amount' => -$amount, 'currency' => 'usd']
            );
            $as = $stripe->invoices->create([
                'customer' => auth()->user()->stripe_id,
            ]);

            Balance::where('id', '=', $balance->id)->update(['balance' => $balanceUser->ending_balance]);

            User::where('id', '=', $user->id)->update(['balance' => $balanceUser->ending_balance]);
            return response()->json(['success' => 200]);
        } else {
            return response()->json(['success' => 500]);
        }

    }
}
