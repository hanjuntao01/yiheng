<?php

return [
    'HTML_CACHE_ON' => true, // 开启静态缓存
    'HTML_CACHE_TIME' => 3600 * 24,   // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX' => '.shtml', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES' => array(  // 定义静态缓存规则
        '*' => array('{$_SERVER.REQUEST_URI|md5}'),
    )
];
