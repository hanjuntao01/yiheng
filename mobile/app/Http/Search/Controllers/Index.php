<?php

namespace App\Http\Search\Controllers;

use App\Http\Base\Controllers\Frontend;

class Index extends Frontend
{

    /**
     * 首页信息
     */
    public function actionIndex()
    {
        $this->assign('page_title', L('search'));
        $this->display();
    }
}
