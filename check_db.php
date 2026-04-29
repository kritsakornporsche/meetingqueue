<?php
require 'api/config.php';
$pdo = getLocalDB();

echo "ROOMS:\n";
$stmt = $pdo->query('SHOW COLUMNS FROM rooms');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));

echo "BOOKINGS:\n";
$stmt = $pdo->query('SHOW COLUMNS FROM bookings');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));

echo "USERS:\n";
$stmt = $pdo->query('SHOW COLUMNS FROM users');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
