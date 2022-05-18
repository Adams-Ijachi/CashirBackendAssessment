<?php

namespace App\Http\Service;
use Illuminate\Support\Facades\Http;

use Auth;
use App\Models\{
    Order,
    Transaction
};


class PaymentService
{
    protected $verify_url;

    protected $secret_key;

    protected $public_key;

    protected $secret_hash ;

    protected $expectedCurrency = 'NGN';

    public function __construct()
    {
        $this->verify_url = env('VERIFY_TRANSACTION_URL');
        $this->secret_key = env('FLUTTERWAVE_SECRET_KEY');
        $this->public_key = env('FLUTTERWAVE_PUBLIC_KEY');
        $this->secret_hash = env('SECRET_HASH');
    }
    

    public function verifyPayment($transaction_id, $expectedAmount)
    {
        try {
            $url = $this->verify_url . $transaction_id . '/verify';

            $response = Http::withToken($this->secret_key)->get($url);
            
            
            if (
                $response['data']['status'] === "successful"
                && $response['data']['amount'] === (int)$expectedAmount
                && $response['data']['currency'] === $this->expectedCurrency) {
                   
                
                return $response['data'];
            } else {
    
                $this->createTransaction($response['data']);
            
                throw new \Exception("Transaction verification failed");
    
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }
   
    public function createTransaction($response)
    {
        return  Transaction::updateOrCreate(['transaction_id' => $response['id']],[
            'transaction_id' => $response['id'],
            'tx_ref' => $response['tx_ref'],
            'status' => $response['status'],
            'amount' => $response['amount'],
            'charged_amount' => $response['charged_amount'],
        ]);

    }

    public function createOrder($request)
    {
        try {
            //code...
            $payment =  $this->verifyPayment($request['transaction_id'], $request['amount']);

            $transaction = $this->createTransaction($payment);
    
    
            $order = Order::create([
                "user_id" => Auth::id(),
                "product_id" => $request['product_id'],
                "transaction_id" => $transaction->id,
            ]);
    
            return $order;

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function webhook($request){

        
        if (!$request->hasHeader('verif-hash')  || $request->hasHeader('verif-hash') != $this->secret_hash ) {
            
            throw(401); 
        }

        $transaction = Transaction::where('transaction_id', $request['id'])->first();

        
        if($transaction->status === $request['status'])
        {
            return response()->noContent();
        }

        $payment = $this->verifyPayment($request['id'], $transaction->amount);

        return response()->noContent( );

    }




}
