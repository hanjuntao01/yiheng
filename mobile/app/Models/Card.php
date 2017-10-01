<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 */
class Card extends Model
{
    protected $table = 'card';

    protected $primaryKey = 'card_id';

    public $timestamps = false;

    protected $fillable = [
        'card_name',
        'user_id',
        'card_img',
        'card_fee',
        'free_money',
        'card_desc'
    ];

    protected $guarded = [];
}
