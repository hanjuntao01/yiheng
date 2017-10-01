<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsStepsFieldsCentent
 */
class MerchantsStepsFieldsCentent extends Model
{
    protected $table = 'merchants_steps_fields_centent';

    public $timestamps = false;

    protected $fillable = [
        'tid',
        'textFields',
        'fieldsDateType',
        'fieldsLength',
        'fieldsNotnull',
        'fieldsFormName',
        'fieldsCoding',
        'fieldsForm',
        'fields_sort',
        'will_choose'
    ];

    protected $guarded = [];
}
