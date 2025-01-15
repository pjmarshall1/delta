<?php

dataset('scan filters', [
    [
        'filter' => ['startDate' => '2024-01-01', 'endDate' => '2024-01-01'],
        'passedData' => ['date' => '2024-01-01'],
        'failedData' => ['date' => '2024-01-02'],
    ],
    [
        'filter' => ['session' => 'pre_market'],
        'passedData' => ['p_count' => 1, 'm_count' => 0, 'a_count' => 0],
        'failedData' => ['p_count' => 0, 'm_count' => 1, 'a_count' => 1],
    ],
    [
        'filter' => ['session' => 'open_market'],
        'passedData' => ['p_count' => 0, 'm_count' => 1, 'a_count' => 0],
        'failedData' => ['p_count' => 0, 'm_count' => 0, 'a_count' => 1],
    ],
    [
        'filter' => ['session' => 'after_market'],
        'passedData' => ['p_count' => 0, 'm_count' => 0, 'a_count' => 1],
        'failedData' => ['p_count' => 0, 'm_count' => 1, 'a_count' => 0],
    ],
    [
        'filter' => ['float' => 'low_float'],
        'passedData' => ['float' => 5_000_000],
        'failedData' => ['float' => 15_000_000],
    ],
    [
        'filter' => ['float' => 'medium_float'],
        'passedData' => ['float' => 30_000_000],
        'failedData' => ['float' => 5_000_000],
    ],
    [
        'filter' => ['float' => 'high_float'],
        'passedData' => ['float' => 60_000_000],
        'failedData' => ['float' => 30_000_000],
    ],
    [
        'filter' => ['reviewed' => 'true'],
        'passedData' => ['reviewed' => true],
        'failedData' => ['reviewed' => false],
    ],
    [
        'filter' => ['reviewed' => 'false'],
        'passedData' => ['reviewed' => false],
        'failedData' => ['reviewed' => true],
    ],
    [
        'filter' => ['symbol' => 'AAPL'],
        'passedData' => ['symbol' => 'AAPL'],
        'failedData' => ['symbol' => 'TSLA'],
    ],
]);

dataset('scan sorts', [
    ['sortBy' => 'alerts_count', 'direction' => 'asc'],
    ['sortBy' => 'alerts_count', 'direction' => 'desc'],

    ['sortBy' => 'alerts_count', 'direction' => 'asc', 'filter' => ['session' => 'pre_market']],
    ['sortBy' => 'alerts_count', 'direction' => 'desc', 'filter' => ['session' => 'pre_market']],

    ['sortBy' => 'alerts_count', 'direction' => 'asc', 'filter' => ['session' => 'open_market']],
    ['sortBy' => 'alerts_count', 'direction' => 'desc', 'filter' => ['session' => 'open_market']],

    ['sortBy' => 'alerts_count', 'direction' => 'asc', 'filter' => ['session' => 'after_market']],
    ['sortBy' => 'alerts_count', 'direction' => 'desc', 'filter' => ['session' => 'after_market']],

    ['sortBy' => 'date', 'direction' => 'asc'],
    ['sortBy' => 'date', 'direction' => 'desc'],

    ['sortBy' => 'exchange', 'direction' => 'asc'],
    ['sortBy' => 'exchange', 'direction' => 'desc'],

    ['sortBy' => 'float', 'direction' => 'asc'],
    ['sortBy' => 'float', 'direction' => 'desc'],

    ['sortBy' => 'gap_percent', 'direction' => 'asc'],
    ['sortBy' => 'gap_percent', 'direction' => 'desc'],

    ['sortBy' => 'list_date', 'direction' => 'asc'],
    ['sortBy' => 'list_date', 'direction' => 'desc'],

    ['sortBy' => 'market_cap', 'direction' => 'asc'],
    ['sortBy' => 'market_cap', 'direction' => 'desc'],

    ['sortBy' => 'price', 'direction' => 'asc'],
    ['sortBy' => 'price', 'direction' => 'desc'],

    ['sortBy' => 'short_interest', 'direction' => 'asc'],
    ['sortBy' => 'short_interest', 'direction' => 'desc'],

    ['sortBy' => 'symbol', 'direction' => 'asc'],
    ['sortBy' => 'symbol', 'direction' => 'desc'],

    ['sortBy' => 'reviewed', 'direction' => 'asc'],
    ['sortBy' => 'reviewed', 'direction' => 'desc'],
]);
