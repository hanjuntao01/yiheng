<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreAction
 */
class StoreAction extends Model
{
    protected $table = 'store_action';

    protected $primaryKey = 'action_id';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'action_code',
        'relevance',
        'action_name'
    ];

    protected $guarded = [];
}
