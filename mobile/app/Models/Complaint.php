<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Complaint
 */
class Complaint extends Model
{
    protected $table = 'complaint';

    protected $primaryKey = 'complaint_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_sn',
        'user_id',
        'user_name',
        'ru_id',
        'shop_name',
        'title_id',
        'complaint_content',
        'add_time',
        'complaint_handle_time',
        'admin_id',
        'appeal_messg',
        'appeal_time',
        'end_handle_time',
        'end_admin_id',
        'end_handle_messg',
        'complaint_state',
        'complaint_active'
    ];

    protected $guarded = [];
}
