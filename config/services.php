<?php

declare(strict_types=1);

return [
    "postmark" => [
        "token" => env("POSTMARK_TOKEN"),
    ],
    "ses" => [
        "key" => env("AWS_ACCESS_KEY_ID"),
        "secret" => env("AWS_SECRET_ACCESS_KEY"),
        "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
    ],
    "slack" => [
        "notifications" => [
            "bot_user_oauth_token" => env("SLACK_BOT_USER_OAUTH_TOKEN"),
            "channel" => env("SLACK_BOT_USER_DEFAULT_CHANNEL"),
        ],
    ],
    "gemini" => [
        "key" => env("GEMINI_API_KEY"),
        "model" => env("GEMINI_MODEL", "gemini-2.5-flash"),
        "endpoint" => env("GEMINI_API_ENDPOINT", "https://generativelanguage.googleapis.com/v1beta/models"),
    ],
];
