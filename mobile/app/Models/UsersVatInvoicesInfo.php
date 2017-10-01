<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersVatInvoicesInfo
 */
class UsersVatInvoicesInfo extends Model
{
    protected $table = 'users_vat_invoices_info';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'tax_id',
        'company_telephone',
        'bank_of_deposit',
        'bank_account',
        'consignee_name',
        'consignee_mobile_phone',
        'country',
        'province',
        'city',
        'district',
        'consignee_address',
        'audit_status',
        'add_time'
    ];

    protected $guarded = [];
}
