<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [

        "transaction_id",
        "tx_ref",
        "status",
        "amount",
        "charged_amount",
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    
}
