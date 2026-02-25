<?php

namespace App\Services;

use App\Misc\Enums\BookingPaymentStatus;
use App\Misc\Enums\BookingStatus;
use App\Models\Booking;
use App\Types\MResponse;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentService
{
    public function createStripeSession(Booking $booking): MResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $booking->vehicle->name,
                    ],
                    'unit_amount' => $booking->total_amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.page.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.page.cancel'),
        ]);

        $booking->update([
            'stripe_session_id' => $session->id
        ]);

        return MResponse::create([
            'checkout_url' => $session->url
        ], 200);
    }

    public function confirmStripePayment(string $sessionId): MResponse
    {
        $booking = Booking::where('stripe_session_id', $sessionId)->first();

        if (!$booking) return MResponse::create([
            "message" => "Booking not found!"
        ], 404);

        $booking->update([
            'payment_status' => BookingPaymentStatus::paid->name,
            'status' => BookingStatus::confirmed->name,
        ]);

        return MResponse::create([
            "message" => "Payment confirmed successfully!"
        ], 200);
    }
}
