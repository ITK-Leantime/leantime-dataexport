<?php

namespace Leantime\Plugins\Dataexport\Services;

/**
 * Dataexport plugin.
 */
class Dataexport
{
    /**
     * Install plugin.
     *
     * @return void
     */
    public function install(): void
    {
        // @TODO Find a (good) way to get the Leantime application root.
        symlink(
            __DIR__ . '/../assets/Dataexport.js',
            __DIR__ . '/../../../../public/dist/js/plugin-Dataexport.js'
        );
    }

    /**
     * Uninstall plugin.
     *
     * @return void
     */
    public function uninstall(): void
    {
        $files = [
            __DIR__ . '/../../../../public/dist/js/plugin-Dataexport.js',
        ];

        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
