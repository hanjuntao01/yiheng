<?php

namespace App\Behavior;

use App\Patches\Factory\Store;

class UpgradeBehavior
{

    private $store;

    /*
    |--------------------------------------------------------------------------
    | Update Program
    |--------------------------------------------------------------------------
    */
    public function run()
    {
        $release = ROOT_PATH . 'storage/logs/.' . RELEASE;
        if (!file_exists($release)) {
            $this->store = new Store();
            $this->store->run();

            require ROOT_PATH . 'storage/clean.php';
            file_put_contents($release, VERSION);
        }
    }
}
