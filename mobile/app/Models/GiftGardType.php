<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GiftGardType
 */
class GiftGardType extends Model
{
    protected $table = 'gift_gard_type';

    protected $primaryKey = 'gift_id';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'gift_name',
        'gift_menory',
        'gift_min_menory',
        'gift_start_date',
        'gift_end_date',
        'gift_number',
        'review_status',
        'review_content'
    ];

    protected $guarded = [];
}
