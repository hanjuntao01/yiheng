<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminUser
 */
class AdminUser extends Model
{
    protected $table = 'admin_user';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'user_name',
        'parent_id',
        'ru_id',
        'email',
        'password',
        'ec_salt',
        'add_time',
        'last_login',
        'last_ip',
        'action_list',
        'nav_list',
        'lang_type',
        'agency_id',
        'suppliers_id',
        'todolist',
        'role_id',
        'major_brand',
        'admin_user_img'
    ];

    protected $guarded = [];

    public function Service()
    {
        return $this->hasOne('App\Models\ImService', 'user_id', 'user_id');
    }
}
