<?php

namespace App\Custom\Site\Controllers;

use App\Http\Site\Controllers\Index as Foundation;

class Index extends Foundation
{
    /**
     * URL路由访问地址: mobile/index.php?m=site&c=index&a=about
     */
    public function actionAbout()
    {
        $this->display();
    }

    public function actionPhpinfo()
    {
        // phpinfo();
    }
}
