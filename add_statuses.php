<?php

require 'public/index.php';

$db = \Config\Database::connect();

$statuses = [
    'Title Defense',
    'Pre Oral Defense',
    'Final Defense'
];

foreach ($statuses as $status) {
    $existing = $db->table('statuses')->where('name', $status)->get()->getRow();
    
    if (!$existing) {
        $db->table('statuses')->insert([
            'name' => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "Added: " . $status . "\n";
    } else {
        echo "Already exists: " . $status . "\n";
    }
}

echo "\nDone!\n";
