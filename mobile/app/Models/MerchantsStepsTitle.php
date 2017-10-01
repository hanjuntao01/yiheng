<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsStepsTitle
 */
class MerchantsStepsTitle extends Model
{
    protected $table = 'merchants_steps_title';

    protected $primaryKey = 'tid';

    public $timestamps = false;

    protected $fillable = [
        'fields_steps',
        'fields_titles',
        'steps_style',
        'titles_annotation',
        'fields_special',
        'special_type'
    ];

    protected $guarded = [];
}
