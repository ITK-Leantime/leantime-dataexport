<?php

use Leantime\Core\Events;

Events::add_event_listener(
    "leantime.core.template.tpl.*.afterScriptLibTags",
    function (array $context) {
        if (($_SESSION['userdata']['id']) && 'timesheets.showAll' === ($context['current_route'] ?? null)) {
            echo '<script src="/dist/js/plugin-Dataexport.js"></script>';
        }
    },
    5
);
