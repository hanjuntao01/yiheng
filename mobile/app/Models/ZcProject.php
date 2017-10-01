<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcProject
 */
class ZcProject extends Model
{
    protected $table = 'zc_project';

    public $timestamps = false;

    protected $fillable = [
        'cat_id',
        'title',
        'init_id',
        'start_time',
        'end_time',
        'amount',
        'join_money',
        'join_num',
        'focus_num',
        'prais_num',
        'title_img',
        'details',
        'describe',
        'risk_instruction',
        'img',
        'is_best'
    ];

    protected $guarded = [];
}
