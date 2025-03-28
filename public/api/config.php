<?php
/**
 * Game Configuration File
 * 
 * This file contains all configurable parameters for the strategy game.
 * Edit these values to adjust game balance and mechanics.
 */

// Starting resources
$CONFIG['starting_gold'] = 1000;
$CONFIG['starting_wood'] = 500;
$CONFIG['starting_stone'] = 300;

// Unit definitions
$CONFIG['units'] = [
    'soldier' => [
        'name' => 'Soldier',
        'build_time' => 5, // seconds
        'cost' => 5, // gold
        'survival_rate' => 0.7, // 70% chance to survive war
        'description' => 'Basic infantry unit',
        'attack' => 15,
        'defense' => 15
    ],
    'mercenaire' => [
        'name' => 'Mercenaire',
        'build_time' => 1, // seconds
        'cost' => 10, // gold
        'survival_rate' => 0.6, // 60% chance to survive war
        'description' => 'Fast to build but less durable',
        'attack' => 20,
        'defense' => 10
    ],
    'cavalier' => [
        'name' => 'Cavalier',
        'build_time' => 15, // seconds
        'cost' => 30, // gold
        'survival_rate' => 0.8, // 80% chance to survive war
        'description' => 'Expensive but durable mounted unit',
        'attack' => 35,
        'defense' => 25
    ],
    'arbaletrier' => [
        'name' => 'Arbalétrier',
        'build_time' => 5, // seconds
        'cost' => 10, // gold
        'survival_rate' => 0.8, // 80% chance to survive war
        'description' => 'Ranged unit with strong defense',
        'attack' => 5,
        'defense' => 35
    ],
    'espion' => [
        'name' => 'Espion',
        'build_time' => 15, // seconds
        'cost' => 50, // gold
        'survival_rate' => 1.0, // Not sent to war
        'loot_bonus' => 0.5, // 50% extra loot per espion
        'description' => 'Increases war loot but doesn\'t fight'
    ],
    'mage' => [
        'name' => 'Mage',
        'build_time' => 10, // seconds
        'cost' => 100, // gold
        'survival_rate' => 0.8, // 80% chance to survive war
        'attack' => 100,
        'defense' => 20,
        'description' => 'Powerful magic user with high attack'
    ],
    'serf' => [
        'name' => 'Serf',
        'build_time' => 10, // seconds
        'base_cost' => 1000, // Starting cost
        'cost_increment' => 200, // Cost increases by this amount per serf
        'max_count' => 200, // Maximum number of serfs
        'description' => 'Village worker that generates resources',
        'is_village_unit' => true, // Flag to identify village units
        'income_per_day' => 1000, // Gold income per day per serf
        'income_check_interval' => 10 // Seconds between income updates
    ]
];

// War settings
$CONFIG['war'] = [
    'duration' => 5, // seconds
    'base_loot_min' => 5, // Minimum gold per unit
    'base_loot_max' => 10, // Maximum gold per unit
];

// Castle attack settings
$CONFIG['castle_attack'] = [
    'check_frequency' => 20, // seconds between attack checks
    'attack_probability' => 40, // percentage chance of attack (40%)
];

// Castle settings
$CONFIG['castle'] = [
    'levels' => [
        0 => [
            'name' => 'No protection',
            'defense_bonus' => 0,
            'cost' => 0
        ],
        1 => [
            'name' => 'Tents',
            'defense_bonus' => 5, // 5%
            'cost' => 1000
        ],
        2 => [
            'name' => 'Wooden Palisade',
            'defense_bonus' => 20, // 20%
            'cost' => 5000
        ],
        3 => [
            'name' => 'Oak Wall',
            'defense_bonus' => 30, // 30%
            'cost' => 10000
        ],
        4 => [
            'name' => 'Reinforced Stone Wall',
            'defense_bonus' => 40, // 40%
            'cost' => 100000
        ],
        5 => [
            'name' => 'Double Reinforced Wall',
            'defense_bonus' => 50, // 50%
            'cost' => 500000
        ],
        6 => [
            'name' => 'Fortress',
            'defense_bonus' => 100, // 100%
            'cost' => 1000000
        ],
        7 => [
            'name' => 'High Fortress',
            'defense_bonus' => 150, // 150%
            'cost' => 2000000
        ],
        8 => [
            'name' => 'Strong Castle',
            'defense_bonus' => 200, // 200%
            'cost' => 5000000
        ],
        9 => [
            'name' => 'Magical Castle',
            'defense_bonus' => 250, // 250%
            'cost' => 10000000
        ],
        10 => [
            'name' => 'Citadel',
            'defense_bonus' => 300, // 300%
            'cost' => 20000000
        ],
        11 => [
            'name' => '??? (secret)',
            'defense_bonus' => 500, // 500%
            'cost' => 100000000
        ]
    ]
];

// Game mechanics
$CONFIG['mechanics'] = [
    'queue_limit' => 20, // Maximum number of units in queue
    'auto_refresh_interval' => 1, // Seconds between auto-refreshes
];

// Return the configuration array
return $CONFIG; 