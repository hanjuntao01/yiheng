<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ComplainTitle
 */
class ComplainTitle extends Model
{
    protected $table = 'complain_title';

    protected $primaryKey = 'title_id';

    public $timestamps = false;

    protected $fillable = [
        'title_name',
        'title_desc',
        'is_show'
    ];

    protected $guarded = [];
}
