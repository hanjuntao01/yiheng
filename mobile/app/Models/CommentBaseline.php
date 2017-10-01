<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentBaseline
 */
class CommentBaseline extends Model
{
    protected $table = 'comment_baseline';

    public $timestamps = false;

    protected $fillable = [
        'goods',
        'service',
        'shipping'
    ];

    protected $guarded = [];
}
