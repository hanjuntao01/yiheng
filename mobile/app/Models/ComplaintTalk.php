<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ComplaintTalk
 */
class ComplaintTalk extends Model
{
    protected $table = 'complaint_talk';

    protected $primaryKey = 'talk_id';

    public $timestamps = false;

    protected $fillable = [
        'complaint_id',
        'talk_member_id',
        'talk_member_name',
        'talk_member_type',
        'talk_content',
        'talk_state',
        'admin_id',
        'talk_time',
        'view_state'
    ];

    protected $guarded = [];
}
