<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function donate()
    {
        // echo "hi";
        return view("payment.donate");
    }

    public function initiatePayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $order = $api->order->create([
            'receipt' => 'order_rcptid_11',
            'amount' => $request->amount * 100, 
            'currency' => 'INR',
            'payment_capture' => 1, 
        ]);

        return view('payment.donate', ['orderId' => $order['id'], 'amount' => $request->amount, 'razorpayId' => env('RAZORPAY_KEY')]);
    }

    public function paymentSuccess(Request $request)
    {
        // Validate the Razorpay payment
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);

            // If the payment is successful, you can store the details in the database or perform further actions
            return redirect()->back()->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment verification failed!');
        }
    }
}
