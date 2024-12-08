<?php

return [
    'default' => env('MARKET_DATA_PROVIDER', 'polygon'),
    'providers' => [
        'polygon' => [
            'api_key' => env('POLYGON_API_KEY'),
        ],
    ],
];
