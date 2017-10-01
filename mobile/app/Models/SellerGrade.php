<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerGrade
 */
class SellerGrade extends Model
{
    protected $table = 'seller_grade';

    public $timestamps = false;

    protected $fillable = [
        'grade_name',
        'goods_sun',
        'seller_temp',
        'favorable_rate',
        'give_integral',
        'rank_integral',
        'pay_integral',
        'white_bar',
        'grade_introduce',
        'entry_criteria',
        'grade_img',
        'is_open',
        'is_default'
    ];

    protected $guarded = [];
}
