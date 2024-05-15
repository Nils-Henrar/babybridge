<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $payments = Payment::whereHas('tutorChild', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('event')->where('status', 'pending')->get();

        return view('tutor.payments.index', ['payments' => $payments]);
    }

    public function pay(Request $request, Payment $payment)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $payment->event->title,
                    ],
                    'unit_amount' => $payment->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('tutor.payments.success', ['payment' => $payment->id]),
            'cancel_url' => route('tutor.payments.cancel', ['payment' => $payment->id]),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('tutor.payments')->with('success', 'Payment successful.');
    }

    public function cancel(Request $request, Payment $payment)
    {
        return redirect()->route('tutor.payments')->with('error', 'Payment cancelled.');
    }
}
