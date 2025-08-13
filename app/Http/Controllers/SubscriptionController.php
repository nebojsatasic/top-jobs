<?php

namespace App\Http\Controllers;

use App\Http\Middleware\donotAllowUserToMakePayment;
use App\Http\Middleware\isEmployer;
use App\Mail\PurchaseMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    const WEEKLY_AMOUNT = 20;
    const MONTHLY_AMOUNT = 80;
    const YEARLY_AMOUNT = 200;
    const CURRENCY = 'USD';

    /**
     * SubscriptionController Constructor
     */
    public function __construct()
    {
        $this->middleware(['auth', isEmployer::class]);
        $this->middleware(donotAllowUserToMakePayment::class)->except('subscribe');
    }

    /**
     * Display the subscription page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subscribe()
    {
        return view('subscription.index');
    }

    /**
     * Pay for the selected plan via Stripe.
     *
     * @ param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function initiatePayment(Request $request)
    {
        $plans = [
            'weekly' => [
                'name' => 'weekly',
                'description' => 'weekly payment',
                'amount' => self::WEEKLY_AMOUNT,
                'currency' => self::CURRENCY,
                'quantity' => 1
            ],
            'monthly' => [
                'name' => 'monthly',
                'description' => 'monthly payment',
                'amount' => self::MONTHLY_AMOUNT,
                'currency' => self::CURRENCY,
                'quantity' => 1
            ],
            'yearly' => [
                'name' => 'yearly',
                'description' => 'yearly payment',
                'amount' => self::YEARLY_AMOUNT,
                'currency' => self::CURRENCY,
                'quantity' => 1
            ]
        ];

        Stripe::setApiKey(config('services.stripe.secret'));

        try
        {
            $selectPlan = null;
            if ($request->is('pay/weekly')) {
                $selectPlan = $plans['weekly'];
                $billingEnds = now()->addWeek()->startOfDay()->toDateString();
            } elseif ($request->is('pay/monthly')) {
                $selectPlan = $plans['monthly'];
                $billingEnds = now()->addMonth()->startOfDay()->toDateString();
            } elseif ($request->is('pay/yearly')) {
                $selectPlan = $plans['yearly'];
                $billingEnds = now()->addYear()->startOfDay()->toDateString();
            }
            if ($selectPlan) {
                $successUrl = URL::signedRoute('payment.success', [
                    'plan' => $selectPlan['name'],
                    'billing_ends' => $billingEnds
                ]);

                $session = Session::create([
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => $selectPlan['currency'],
                                'product_data' => ['name' => $selectPlan['name'], 'description' => $selectPlan['description']],
                                'unit_amount' => $selectPlan['amount'] * 100,
                            ],
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    // Remove the payment_method_types parameter to manage payment methods in the Dashboard
                    'payment_method_types' => ['card'],
                    'success_url' => $successUrl,
                    'cancel_url' => route('payment.cancel')
                ]);

                return redirect($session->url);
            }

        } catch (\Exception $e)
        {
            return response()->json($e);
        }
    }

    /**
     * Update the authenticated user's status and membership after successful payment.
     * Display page that loads if payment is successful.
     * Send an email to notify the user that payment is successful
     *
     * @ param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function paymentSuccess(Request $request)
    {
        $plan = $request->plan;
        $billingEnds = $request->billing_ends;
        User::where('id', auth()->user()->id)->update([
            'plan' => $plan,
            'billing_ends' => $billingEnds,
            'status' => 'paid'
        ]);

        try
        {
            Mail::to(auth()->user()->email)->queue(new PurchaseMail($plan, $billingEnds));

        } catch (Exception $e)
        {
            return response()->json($e);
        }

        return redirect()->route('dashboard')->with('success', 'Payment was successfully processed');
    }

    /**
     * Redirect to the page that loads if payment is canceled.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        return redirect()->route('dashboard')->with('error', 'Payment was unsuccessful!');
    }
}
