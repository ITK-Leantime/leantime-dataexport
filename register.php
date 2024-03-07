<?php

use Leantime\Core\Events;

Events::add_event_listener(
    'leantime.core.template.tpl.timesheets.showAll.afterScriptLibTags',
    function (array $context) {
        if ('timesheets.showAll' === ($context['current_route'] ?? null)) {
            echo '<script src="/dist/js/plugin-DataExport.js"></script>';
        }
    }
);
