<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CollectStore
 */
class CollectStore extends Model
{
    protected $table = 'collect_store';

    protected $primaryKey = 'rec_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ru_id',
        'add_time',
        'is_attention'
    ];

    protected $guarded = [];
}
