<?php

namespace App\Http\Controllers;

use App\Misc\Enums\BookingPaymentStatus;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

use function App\Helpers\appResponse;

class PaymentController extends Controller
{

    // POST "/payment/webhook"
    public function webhook(PaymentService $ps, Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $mresponse = $ps->confirmStripePayment($session->id);
            if (!$mresponse->success()) {
                return response()->json($mresponse->data, $mresponse->status);
            }
        }

        return response()->json(['message' => 'Payment confirmed successfully!', 'status' => 'success']);
    }

    // GET "/payment/start/{booking}"
    public function start(PaymentService $ps, Request $req, Booking $booking): Response
    {
        if ($booking->payment_status === BookingPaymentStatus::paid->name) {
            return appResponse($req, ["message" => "Booking already paid!"], 400, ["redirect", "customer.page.bookings_list"]);
        }

        $mresponse = $ps->createStripeSession($booking);

        return redirect($mresponse->data['checkout_url']);
    }

    // GET "/payment/success"
    public function successPage(Request $req): Response
    {
        return appResponse($req, [], 200, ["view", "payment.page.success_page"]);
    }
}
