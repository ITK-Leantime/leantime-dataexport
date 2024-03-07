<?php

namespace Leantime\Plugins\Dataexport\Services;

/**
 * Dataexport plugin.
 */
class Dataexport
{
    private static $assets = [
        // source => target
        __DIR__ . '/../assets/Dataexport.js' => APP_ROOT . '/public/dist/js/plugin-Dataexport.js',
    ];

    /**
     * Install plugin.
     *
     * @return void
     */
    public function install(): void
    {
        foreach (static::$assets as $source => $target) {
            if (file_exists($target)) {
                unlink($target);
            }
            symlink($source, $target);
        }
    }

    /**
     * Uninstall plugin.
     *
     * @return void
     */
    public function uninstall(): void
    {
        foreach (static::$assets as $target) {
            if (file_exists($target)) {
                unlink($target);
            }
        }
    }
}
