<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TemplatesLeft
 */
class TemplatesLeft extends Model
{
    protected $table = 'templates_left';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'seller_templates',
        'bg_color',
        'img_file',
        'if_show',
        'bgrepeat',
        'align',
        'type',
        'theme'
    ];

    protected $guarded = [];
}
