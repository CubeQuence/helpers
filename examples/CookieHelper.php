<?php

declare(strict_types=1);

use CQ\Helpers\CookieHelper;

$time = time();

// Set cookie with following options
CookieHelper::set(
    name: 'test',
    value: (string) $time,
    expires: 0,
    path: '/test',
    domain: '',
    secure: true,
    httponly: true,
    samesite: 'Strict',
    encrypt: false
);

// NOTE: token set and get, in the same request will result in two different values
echo json_encode([
    'time' => $time,
    'cookie' => CookieHelper::get(
        name: 'test',
        encrypted: false
    ),
]);

// CookieHelper::delete(
//     name: 'test'
// );
