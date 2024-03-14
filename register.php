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

Events::add_filter_listener(
    'leantime.core.language.readIni.language_files',
    function (array $payload, array $context): array {
        $language = $context['language'];
        $payload[__DIR__ . '/Language/' . $language . '.ini'] = 'en-US' !== $language;

        return $payload;
    }
);
