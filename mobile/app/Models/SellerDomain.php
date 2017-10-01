<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerDomain
 */
class SellerDomain extends Model
{
    protected $table = 'seller_domain';

    public $timestamps = false;

    protected $fillable = [
        'domain_name',
        'ru_id',
        'is_enable',
        'validity_time'
    ];

    protected $guarded = [];
}
