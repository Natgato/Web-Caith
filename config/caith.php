<?php

return [
    'demo_enabled' => env('CAITH_DEMO_ENABLED', true),
    'demo_email' => 'demo@caith.test',
    'demo_password' => 'CaithDemo2026!',
    'forms_enabled' => env('CAITH_FORMS_ENABLED', env('APP_ENV') === 'local'),
    'preview_institutional' => env('CAITH_PREVIEW_INSTITUTIONAL', env('APP_ENV') === 'local'),
    'privacy_version' => '2026-11-04',
    'source_report' => 'Avance del proyecto Web CAITH, Semanas 09 a 11 (documento de trabajo, noviembre de 2026)',
];
