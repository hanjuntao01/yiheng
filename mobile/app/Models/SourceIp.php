<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SourceIp
 */
class SourceIp extends Model
{
    protected $table = 'source_ip';

    protected $primaryKey = 'ipid';

    public $timestamps = false;

    protected $fillable = [
        'storeid',
        'ipdata',
        'iptime'
    ];

    protected $guarded = [];
}
