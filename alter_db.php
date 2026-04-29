<?php
require 'api/config.php';
$pdo = getLocalDB();

try {
    $pdo->exec("ALTER TABLE rooms ADD COLUMN room_number VARCHAR(50) AFTER name");
    echo "Added room_number to rooms\n";
} catch (Exception $e) { echo $e->getMessage() . "\n"; }

try {
    $pdo->exec("ALTER TABLE bookings ADD COLUMN equipments VARCHAR(255) AFTER end_time");
    echo "Added equipments to bookings\n";
} catch (Exception $e) { echo $e->getMessage() . "\n"; }
