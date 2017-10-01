<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AttributeImg
 */
class AttributeImg extends Model
{
    protected $table = 'attribute_img';

    public $timestamps = false;

    protected $fillable = [
        'attr_id',
        'attr_values',
        'attr_img',
        'attr_site'
    ];

    protected $guarded = [];
}
