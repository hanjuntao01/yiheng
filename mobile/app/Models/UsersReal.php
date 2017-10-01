<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersReal
 */
class UsersReal extends Model
{
    protected $table = 'users_real';

    protected $primaryKey = 'real_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'real_name',
        'bank_mobile',
        'bank_name',
        'bank_card',
        'self_num',
        'add_time',
        'review_content',
        'review_status',
        'review_time',
        'user_type',
        'front_of_id_card',
        'reverse_of_id_card'
    ];

    protected $guarded = [];
}
