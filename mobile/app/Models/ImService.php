<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImService
 */
class ImService extends Model
{
    protected $table = 'im_service';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'nick_name',
        'post_desc',
        'login_time',
        'chat_status',
        'status'
    ];

    protected $guarded = [];

    public function AdminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'user_id', 'user_id');
    }
}
