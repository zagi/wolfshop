<?php

return [
    'Apple AirPods' => [
        'quality_change' => +1,
        'quality_on_expire' => +1,
        'max_quality' => 50,
    ],
    'Apple iPad Air' => [
        'quality_change' => +1,
        'max_quality' => 50,
        'sellIn_thresholds' => [
            10 => +1,
            5 => +1, 
        ],
        'quality_on_expire' => 0,
    ],
    'Samsung Galaxy S23' => [
        'quality_fixed' => 80,
        'ignore_sellIn' => true,
    ],
    'Xiaomi Redmi Note 13' => [
        'quality_change' => -2,
        'quality_on_expire' => -2,
    ],
    'default' => [
        'quality_change' => -1,
        'quality_on_expire' => -2,
        'max_quality' => 50,
        'min_quality' => 0,
    ]
];
