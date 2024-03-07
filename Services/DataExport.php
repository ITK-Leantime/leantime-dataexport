<?php

namespace Leantime\Plugins\DataExport\Services;

/**
 * DataExport plugin.
 */
class DataExport
{
    private static $assets = [
        // source => target
        __DIR__ . '/../assets/DataExport.js' => APP_ROOT . '/public/dist/js/plugin-DataExport.js',
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
