<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsGrade
 */
class MerchantsGrade extends Model
{
    protected $table = 'merchants_grade';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'grade_id',
        'add_time',
        'year_num',
        'amount'
    ];

    protected $guarded = [];
}
