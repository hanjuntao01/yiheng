<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeamCategory
 */
class TeamCategory extends Model
{
    protected $table = 'team_category';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'parent_id',
        'content',
        'tc_img',
        'sort_order',
        'status'
    ];

    protected $guarded = [];
}
