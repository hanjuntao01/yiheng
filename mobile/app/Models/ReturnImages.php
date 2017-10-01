<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReturnImages
 */
class ReturnImages extends Model
{
    protected $table = 'return_images';

    public $timestamps = false;

    protected $fillable = [
        'rg_id',
        'rec_id',
        'user_id',
        'img_file',
        'add_time'
    ];

    protected $guarded = [];
}
