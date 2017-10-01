<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ComplaintImg
 */
class ComplaintImg extends Model
{
    protected $table = 'complaint_img';

    protected $primaryKey = 'img_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'complaint_id',
        'user_id',
        'img_file'
    ];

    protected $guarded = [];
}
