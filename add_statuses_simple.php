<?php

$host = 'localhost';
$dbname = 'smart_research_office';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statuses = [
        'Title Defense',
        'Pre Oral Defense',
        'Final Defense'
    ];

    foreach ($statuses as $status) {
        $stmt = $pdo->prepare("SELECT id FROM statuses WHERE name = ?");
        $stmt->execute([$status]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO statuses (name, created_at, updated_at) VALUES (?, NOW(), NOW())");
            $stmt->execute([$status]);
            echo "Added: " . $status . "\n";
        } else {
            echo "Already exists: " . $status . "\n";
        }
    }

    echo "\nDone!\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
