<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OssConfigure
 */
class OssConfigure extends Model
{
    protected $table = 'oss_configure';

    public $timestamps = false;

    protected $fillable = [
        'bucket',
        'keyid',
        'keysecret',
        'is_cname',
        'endpoint',
        'regional',
        'is_use'
    ];

    protected $guarded = [];
}
