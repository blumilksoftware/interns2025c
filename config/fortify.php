<?php

declare(strict_types=1);

use Laravel\Fortify\Features;

return [
    "guard" => "web",
    "passwords" => "users",
    "username" => "email",
    "email" => "email",
    "lowercase_usernames" => true,
    "home" => "/dashboard",
    "prefix" => "",
    "domain" => null,
    "middleware" => ["web"],
    "limiters" => [
        "login" => "login",
    ],
    "views" => true,
    "features" => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
    ],
];
