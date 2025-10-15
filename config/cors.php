<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
'allowed_origins' => [
    'http://localhost',
    'http://127.0.0.1:8000',
    'http://127.0.0.1:61172',
    'https://1b690f1d6b1a.ngrok-free.app', 
    'https://sandeep9354.github.io'
],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Authorization'],
    'max_age' => 0,
    'supports_credentials' => false, // true if you want cookies; false for token-based
];

