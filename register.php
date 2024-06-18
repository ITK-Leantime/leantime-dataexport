<?php

use Leantime\Core\Events;

Events::add_event_listener(
    'leantime.core.template.tpl.timesheets.showAll.afterScriptLibTags',
    function (array $context) {
        if ('timesheets.showAll' === ($context['current_route'] ?? null)) {
            $url = '/dist/js/plugin-AllTimesheetsDataExport.js?' . http_build_query(['v' => '%%VERSION%%']);
            echo '<script src="' . htmlspecialchars($url) . '"></script>';
        }
    }
);

Events::add_filter_listener(
    'leantime.core.language.readIni.language_files',
    function (array $payload, array $context): array {
        $language = $context['language'];
        $languageFile = __DIR__ . '/Language/' . $language . '.ini';
        if (file_exists($languageFile)) {
            $payload[$languageFile] = 'en-US' !== $language;
        }

        return $payload;
    }
);
