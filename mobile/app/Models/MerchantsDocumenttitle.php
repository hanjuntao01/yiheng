<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsDocumenttitle
 */
class MerchantsDocumenttitle extends Model
{
    protected $table = 'merchants_documenttitle';

    protected $primaryKey = 'dt_id';

    public $timestamps = false;

    protected $fillable = [
        'dt_title',
        'cat_id'
    ];

    protected $guarded = [];
}
