<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $payments = Payment::whereHas('childTutor', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('event')->where('status', 'pending')->get();

        return view('tutor.payment.index', ['payments' => $payments]);
    }

    public function pay(Request $request, Payment $payment)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $payment->event->title,
                        'description' => 'Paiement pour l\'événement ' . $payment->event->title . ' pour ' . $payment->childTutor->child->firstname . ' ' . $payment->childTutor->child->lastname,
                    ],
                    'unit_amount' => $payment->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('tutor.payment.success', ['payment' => $payment->id]),
            'cancel_url' => route('tutor.payment.cancel', ['payment' => $payment->id]),
        ]);

        return redirect($session->url);
    }

    public function success($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        // Marquer ce paiement comme payé
        $payment->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        // Récupère tous les paiements pour les autres tuteurs de cet enfant pour cet événement
        $childTutorIds = Payment::where('event_id', $payment->event_id)
            ->whereHas('childTutor', function ($query) use ($payment) {
                $query->where('child_id', $payment->childTutor->child_id);
            })
            ->pluck('id');

        // Met à jour le statut de paiement pour tous ces paiements
        Payment::whereIn('id', $childTutorIds)->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return view('tutor.payment.success', ['payment' => $payment]);
    }

    public function cancel(Request $request, Payment $payment)
    {
        return redirect()->route('tutor.payment.cancel', ['payment' => $payment->id]);
    }
}
