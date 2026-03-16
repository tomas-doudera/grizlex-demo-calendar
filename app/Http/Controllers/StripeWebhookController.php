<?php

namespace App\Http\Controllers;

use App\Domain\Finance\Services\StripeCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, StripeCheckoutService $stripeService): Response
    {
        try {
            $stripeService->handleWebhook(
                $request->getContent(),
                $request->header('Stripe-Signature', ''),
            );
        } catch (SignatureVerificationException) {
            return response('Invalid signature', 400);
        }

        return response('OK', 200);
    }
}
