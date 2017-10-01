<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerList
 */
class PartnerList extends Model
{
    protected $table = 'partner_list';

    protected $primaryKey = 'link_id';

    public $timestamps = false;

    protected $fillable = [
        'link_name',
        'link_url',
        'link_logo',
        'show_order'
    ];

    protected $guarded = [];
}
