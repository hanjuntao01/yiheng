<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsCategoryTemporarydate
 */
class MerchantsCategoryTemporarydate extends Model
{
    protected $table = 'merchants_category_temporarydate';

    protected $primaryKey = 'ct_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cat_id',
        'parent_id',
        'cat_name',
        'parent_name',
        'is_add'
    ];

    protected $guarded = [];
}
