<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsStepsProcess
 */
class MerchantsStepsProcess extends Model
{
    protected $table = 'merchants_steps_process';

    public $timestamps = false;

    protected $fillable = [
        'process_steps',
        'process_title',
        'process_article',
        'steps_sort',
        'is_show',
        'fields_next'
    ];

    protected $guarded = [];
}
