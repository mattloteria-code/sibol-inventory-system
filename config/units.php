<?php

return [
    'types' => [
        'weight' => 'Weight',
        'volume' => 'Volume',
        'count' => 'Count',
    ],

    'units' => [
        'g'     => ['label' => 'Grams (g)',      'type' => 'weight', 'to_base' => 1],
        'kg'    => ['label' => 'Kilograms (kg)', 'type' => 'weight', 'to_base' => 1000],
        'ml'    => ['label' => 'Milliliters (ml)', 'type' => 'volume', 'to_base' => 1],
        'l'     => ['label' => 'Liters (l)',      'type' => 'volume', 'to_base' => 1000],
        'piece' => ['label' => 'Piece',           'type' => 'count',  'to_base' => 1],
        'dozen' => ['label' => 'Dozen',           'type' => 'count',  'to_base' => 12],
    ],

    'base_unit_for_type' => [
        'weight' => 'g',
        'volume' => 'ml',
        'count' => 'piece',
    ],
];