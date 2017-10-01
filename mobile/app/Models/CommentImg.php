<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentImg
 */
class CommentImg extends Model
{
    protected $table = 'comment_img';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'order_id',
        'rec_id',
        'goods_id',
        'comment_id',
        'comment_img',
        'img_thumb',
        'cont_desc'
    ];

    protected $guarded = [];
}
