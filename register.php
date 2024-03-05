<?php

use Leantime\Core\Events;

Events::add_event_listener(
    "leantime.core.template.tpl.*.afterScriptLibTags",
    function (array $context) {
        if (isset($_SESSION['userdata']['id']) && 'timesheets.showAll' === ($context['current_route'] ?? null)) {
            $options = [
              'labels' => [
                  'export_csv' => __('label.export_csv'),
                  'export_excel' => __('label.export_excel'),
              ],
            ];
            echo '<script src="/dist/js/plugin-Dataexport.js"></script>';
        }
    },
    5
);
