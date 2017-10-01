<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PicAlbum
 */
class PicAlbum extends Model
{
    protected $table = 'pic_album';

    protected $primaryKey = 'pic_id';

    public $timestamps = false;

    protected $fillable = [
        'pic_name',
        'album_id',
        'pic_file',
        'pic_thumb',
        'pic_image',
        'pic_size',
        'pic_spec',
        'ru_id',
        'add_time'
    ];

    protected $guarded = [];
}
