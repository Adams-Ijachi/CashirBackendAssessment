<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Service\PaymentService;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function create (OrderCreateRequest $request, PaymentService  $paymentService )
    {
        try {

            $paymentService->createOrder($request);
            return response()->json([
                'message' => 'Order created successfully'
            ], 201);

        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request,  PaymentService  $paymentService )
    {
        try {
            return $paymentService->webhook($request);
        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }



}
