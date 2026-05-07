<?php

namespace App\Domain\Billing\Payment\Models;

use App\Domain\Billing\Invoice\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'amount', 'payment_method', 'payment_date', 'transaction_id'
    ];

    protected $casts = ['payment_date' => 'datetime'];

    public function invoice() { return $this->belongsTo(Invoice::class); }
}
