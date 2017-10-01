<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GalleryAlbum
 */
class GalleryAlbum extends Model
{
    protected $table = 'gallery_album';

    protected $primaryKey = 'album_id';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'album_mame',
        'album_cover',
        'album_desc',
        'sort_order',
        'add_time'
    ];

    protected $guarded = [];
}
