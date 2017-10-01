<?php

namespace App\Patches\Factory;

/**
 * Interface PatchInterface
 * @package App\Patchs
 */
interface PatchInterface
{
    /**
     * @return mixed
     */
    public function updateDatabaseOptionally();

    /**
     * @return mixed
     */
    public function updateFiles();
}