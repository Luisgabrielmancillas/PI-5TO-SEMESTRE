<?php

return [
    'host'            => env('MQTT_HOST', '127.0.0.1'),
    'port'            => env('MQTT_PORT', 1883),
    'user'            => env('MQTT_USER', null),
    'pass'            => env('MQTT_PASS', null),
    'client_id_prefix'=> env('MQTT_CLIENT_ID_PREFIX', 'hydrobox-web'),
    'base_topic'      => env('MQTT_BASE_TOPIC', 'hydrobox'),
];