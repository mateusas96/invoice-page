<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    public $timestamps = false;

    protected $attributes = [
        'is_paid' => false,
        'invoice_paid_at' => null
    ];

    protected $fillable = [
        'is_paid', 'invoice_number', 'invoice_payment_date', 'invoice_paid_at', 'senders_company', 'senders_info', 'client_id', 'client_info', 'invoice_currency', 'subtotal', 'discount', 'discount_in_percentage', 'tax', 'tax_in_percentage', 'grand_total', 'notes', 'terms'
    ];
}
