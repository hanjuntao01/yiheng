<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderInvoice
 */
class OrderInvoice extends Model
{
    protected $table = 'order_invoice';

    protected $primaryKey = 'invoice_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'inv_payee',
        'tax_id'
    ];

    protected $guarded = [];
}
