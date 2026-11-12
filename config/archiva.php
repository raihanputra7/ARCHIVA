<?php

return [
    'app_base_url' => rtrim((string) env('APP_BASE_URL', env('APP_URL', 'http://localhost')), '/'),
    'admin_notify_email' => env('ADMIN_NOTIFY_EMAIL'),
    'mail_hourly_limit' => (int) env('MAIL_HOURLY_LIMIT', 50),
    'smtp_configured' => ! empty(env('SMTP_HOST')) || (env('MAIL_MAILER') === 'smtp' && ! empty(env('MAIL_HOST'))),
];
