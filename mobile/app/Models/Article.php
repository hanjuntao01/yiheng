<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\common_helper;

/**
 * Class Article
 */
class Article extends Model
{
    /**
     * @var string
     */
    protected $table = 'article';

    /**
     * @var string
     */
    protected $primaryKey = 'article_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $hidden = ['article_id', 'cat_id', 'is_open', 'open_type', 'author_email', 'article_type'];

    /**
     * @var array
     */
    protected $visible = [];

    /**
     * @var array
     */
    protected $appends = ['id', 'url', 'album', 'amity_time'];

    /**
     * @var array
     */
    protected $fillable = [
        'cat_id',
        'title',
        'content',
        'author',
        'author_email',
        'keywords',
        'article_type',
        'is_open',
        'add_time',
        'file_url',
        'open_type',
        'link',
        'description'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function extend()
    {
        return $this->hasOne('App\Models\ArticleExtend', 'article_id', 'article_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany('App\Models\Comment', 'id_value', 'article_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function goods()
    {
        return $this->belongsToMany('App\Models\Goods', 'goods_article', 'article_id', 'goods_id');
    }

    /**
     * @return string
     */
    public function getAddTimeAttribute()
    {
        return local_date('Y-m-d', $this->attributes['add_time']);
    }

    /**
     * @return string
     */
    public function getAmityTimeAttribute()
    {
        return friendlyDate(strtotime(local_date('Y-m-d H:i:s',$this->attributes['add_time'])), 'moremohu');
    }

    /**
     * @return mixed
     */
    public function getIdAttribute()
    {
        return $this->attributes['article_id'];
    }

    /**
     * @return array
     */
    public function getAlbumAttribute()
    {
        $pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp|\.jpeg]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern, $this->attributes['content'], $match);
        $album = array();
        if (count($match[1]) > 0) {
            foreach ($match[1] as $img) {
                if (strtolower(substr($img, 0, 4)) != 'http') {
                    $realpath = mb_substr($img, stripos($img, 'images/'));
                    $album[] = get_image_path($realpath);
                }
            }
        }
        // 超过三图则取前三
        if (count($album) > 3) {
            $album = array_slice($album, 0, 3);
        }
        return $album;
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('article/index/detail', array('id' => $this->attributes['article_id']));
    }
}
