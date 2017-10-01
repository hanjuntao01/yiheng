<?php

namespace App\Http\Console\Controllers;

use App\Http\Base\Controllers\Backend;

class Index extends Backend
{
    /**
     * 编辑控制台
     */
    public function actionIndex()
    {
        $this->display();
    }
}
