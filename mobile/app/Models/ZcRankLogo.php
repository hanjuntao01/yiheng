<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcRankLogo
 */
class ZcRankLogo extends Model
{
    protected $table = 'zc_rank_logo';

    public $timestamps = false;

    protected $fillable = [
        'logo_name',
        'img',
        'logo_intro'
    ];

    protected $guarded = [];
}
