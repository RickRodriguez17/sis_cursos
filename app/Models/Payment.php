<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'gateway', 'external_id', 'amount', 'status', 'payload', 'qr_url'];

    protected $casts = ['amount' => 'decimal:2', 'payload' => 'array'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
