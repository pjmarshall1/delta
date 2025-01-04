<?php

return [
    'inertiajs/inertia-laravel' => [
        'providers' => [
            0 => 'Inertia\\ServiceProvider',
        ],
    ],
    'jerome/filterable' => [
        'providers' => [
            0 => 'Filterable\\Providers\\FilterableServiceProvider',
        ],
    ],
    'laravel/breeze' => [
        'providers' => [
            0 => 'Laravel\\Breeze\\BreezeServiceProvider',
        ],
    ],
    'laravel/pail' => [
        'providers' => [
            0 => 'Laravel\\Pail\\PailServiceProvider',
        ],
    ],
    'laravel/reverb' => [
        'providers' => [
            0 => 'Laravel\\Reverb\\ApplicationManagerServiceProvider',
            1 => 'Laravel\\Reverb\\ReverbServiceProvider',
        ],
        'aliases' => [
            'Output' => 'Laravel\\Reverb\\Output',
        ],
    ],
    'laravel/sail' => [
        'providers' => [
            0 => 'Laravel\\Sail\\SailServiceProvider',
        ],
    ],
    'laravel/sanctum' => [
        'providers' => [
            0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
        ],
    ],
    'laravel/telescope' => [
        'providers' => [
            0 => 'Laravel\\Telescope\\TelescopeServiceProvider',
        ],
    ],
    'laravel/tinker' => [
        'providers' => [
            0 => 'Laravel\\Tinker\\TinkerServiceProvider',
        ],
    ],
    'nesbot/carbon' => [
        'providers' => [
            0 => 'Carbon\\Laravel\\ServiceProvider',
        ],
    ],
    'nunomaduro/collision' => [
        'providers' => [
            0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
        ],
    ],
    'nunomaduro/termwind' => [
        'providers' => [
            0 => 'Termwind\\Laravel\\TermwindServiceProvider',
        ],
    ],
    'pestphp/pest-plugin-laravel' => [
        'providers' => [
            0 => 'Pest\\Laravel\\PestServiceProvider',
        ],
    ],
    'tightenco/ziggy' => [
        'providers' => [
            0 => 'Tighten\\Ziggy\\ZiggyServiceProvider',
        ],
    ],
];
