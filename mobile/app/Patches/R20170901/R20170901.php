<?php

namespace App\Patches\R20170901;

use App\Patches\Factory\PatchInterface;

class R20170901 implements PatchInterface
{

    /**
     * @var array
     */
    private $convert = [];

    /**
     * 提供给控制器的 接口 函数。每个版本类必须有该函数。
     */
    public function updateDatabaseOptionally()
    {
        return false;
    }

    /**
     * 提供给控制器的 接口 函数。每个版本类必须有该函数。
     */
    public function updateFiles()
    {
        $root_path = ROOT_PATH;

        /**
         * Unix 环境检测
         */
        if (!stristr(PHP_OS, 'WIN') && !APP_DEBUG) {
            /**
             * 新建备份目录
             */
            $backup_path = $root_path . 'storage/backup';
            if (!is_dir($backup_path)) {
                mkdir($backup_path);
            }

            /**
             * 新建备份版本目录
             */
            $version_path = $root_path . 'storage/backup/' . VERSION;
            if (!is_dir($version_path)) {
                mkdir($version_path);
            }

            /**
             * 原始文件目录
             */
            $old_list = [
                'app/api',
                'app/behavior',
                'app/classes',
                'app/console',
                'app/contracts',
                'app/custom',
                'app/events',
                'app/exceptions',
                'app/extensions',
                'app/helpers',
                'app/http',
                'app/jobs',
                'app/libraries',
                'app/listeners',
                'app/models',
                'app/modules',
                'app/notifications',
                'app/notify',
                'app/presenters',
                'app/providers',
                'app/repositories',
                'app/services',
                'app/support',
                'app/transformer',
            ];

            foreach ($old_list as $item) {
                if (is_dir($root_path . $item)) {
                    if (!is_dir($version_path . '/' . $item)) {
                        mkdir($version_path . '/' . $item, 0777, true);
                    }
                    copy_dir($root_path . $item, $version_path . '/' . $item);
                    del_dir($root_path . $item);
                }
            }
        }

        /**
         * WIN 环境检测
         */
        if (stristr(PHP_OS, 'WIN')) {
            $list = [
                'app/api' => true,
                'app/contracts' => true,
                'app/custom' => true,
                'app/http' => true,
                'app/repositories' => true,
                'app' => false,
            ];

            foreach ($list as $key => $value) {
                $this->getConvert($root_path . $key, $value);
            }
            $this->transform($this->convert);

            /**
             * 恢复语言包目录
             */
            $convert = glob($root_path . 'app/Http/*/Language/*');
            $this->transform($convert, false);
        }

        /**
         * 移除前端文件目录
         */
        if (!APP_DEBUG) {
            $list = [
                'resources/electron',
                'resources/program',
                'resources/vuejs',
            ];
            foreach ($list as $item) {
                if (is_dir($root_path . $item)) {
                    del_dir($root_path . $item);
                }
            }
        }

    }

    /**
     * 获取待转换目录
     * @param $item
     * @param bool $recursion
     */
    private function getConvert($item, $recursion = false)
    {
        $list = glob($item . '/*');
        foreach ($list as $vo) {
            if (is_dir($vo)) {
                if ($recursion) {
                    $this->getConvert($vo, $recursion);
                }
                $this->convert[] = $vo;
            }
        }
    }

    /**
     * 转换名称
     * @param $convertList
     * @param bool $ucfirst
     */
    private function transform($convertList, $ucfirst = true)
    {
        foreach ($convertList as $item) {
            if ($ucfirst) {
                $name = dirname($item) . '/' . ucfirst(basename($item));
            } else {
                $name = dirname($item) . '/' . strtolower(basename($item));
            }
            rename($item, $name);
        }
    }

}