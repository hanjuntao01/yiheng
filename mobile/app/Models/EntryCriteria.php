<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EntryCriteria
 */
class EntryCriteria extends Model
{
    protected $table = 'entry_criteria';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'criteria_name',
        'charge',
        'standard_name',
        'type',
        'is_mandatory',
        'option_value'
    ];

    protected $guarded = [];
}
