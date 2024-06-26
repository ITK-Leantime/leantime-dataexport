<?php

namespace Leantime\Plugins\DataExport\Services;

/**
 * DataExport plugin.
 */
final class DataExport
{
    /**
     * @var array<string, string>
     */
    private static array $assets = [
        // source => target
        __DIR__ . '/../assets/MyTimesheetDataExport.js' => APP_ROOT . '/public/dist/js/plugin-MyTimesheetDataExport.js',
        __DIR__ . '/../assets/AllTimesheetsDataExport.js' => APP_ROOT . '/public/dist/js/plugin-AllTimesheetsDataExport.js',
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
