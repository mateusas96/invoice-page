<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    protected $table = 'invoices_items';

    public $timestamps = false;

    protected $fillable = [
        'invoice_number', 'item_name', 'price_per_unit', 'quantity', 'total'
    ];
}
