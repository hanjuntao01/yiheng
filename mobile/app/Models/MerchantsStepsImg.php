<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsStepsImg
 */
class MerchantsStepsImg extends Model
{
    protected $table = 'merchants_steps_img';

    protected $primaryKey = 'gid';

    public $timestamps = false;

    protected $fillable = [
        'tid',
        'steps_img'
    ];

    protected $guarded = [];
}
