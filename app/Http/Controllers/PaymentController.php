<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayOS\PayOS;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $payOS = new PayOS(
            config('services.payos.client_id'),
            config('services.payos.api_key'),
            config('services.payos.checksum_key')
        );

        $orderCode = time();

        $data = [
            "orderCode" => $orderCode,
            "amount" => 100000,
            "description" => "Thanh toan don hang",
            "returnUrl" => route('payment.success'),
            "cancelUrl" => route('payment.cancel')
        ];

        $response = $payOS->createPaymentLink($data);

        return redirect($response['checkoutUrl']);
    }

    public function success()
    {
        return response("
            <h1>Thanh toán thành công</h1>

            <script>
                setTimeout(() => {
                    window.location.href='/orders';
                },2000);
            </script>
        ");
    }

    public function cancel()
    {
        return "Thanh toán đã hủy";
    }
}