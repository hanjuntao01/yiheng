<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatRuleKeywords
 */
class WechatRuleKeywords extends Model
{
    protected $table = 'wechat_rule_keywords';

    public $timestamps = false;

    protected $fillable = [
        'wechat_id',
        'rid',
        'rule_keywords'
    ];

    protected $guarded = [];
}
