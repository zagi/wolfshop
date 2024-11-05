<?php

return [
    'Apple AirPods' => [
        'strategy_class' => \App\Services\ItemStrategies\AgingItemStrategy::class,
        'quality_change' => +1,
        'quality_on_expire' => +1,
        'max_quality' => 50,
    ],
    'Apple iPad Air' => [
        'strategy_class' => \App\Services\ItemStrategies\ConcertItemStrategy::class,
        'quality_change' => +1,
        'max_quality' => 50,
        'sellIn_thresholds' => [
            10 => +1,
            5 => +1, 
        ],
        'quality_on_expire' => 0,
    ],
    'Samsung Galaxy S23' => [
        'strategy_class' => \App\Services\ItemStrategies\LegendaryItemStrategy::class,
        'quality_fixed' => 80,
        'ignore_sellIn' => true,
    ],
    'Xiaomi Redmi Note 13' => [
        'strategy_class' => \App\Services\ItemStrategies\ConjuredItemStrategy::class,
        'quality_change' => -2,
        'quality_on_expire' => -2,
    ],
    'default' => [
        'strategy_class' => \App\Services\ItemStrategies\DefaultItemStrategy::class,
        'quality_change' => -1,
        'quality_on_expire' => -2,
        'max_quality' => 50,
        'min_quality' => 0,
    ]
];
