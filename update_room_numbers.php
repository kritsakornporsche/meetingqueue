<?php
require 'api/config.php';
$pdo = getLocalDB();
$rooms = $pdo->query('SELECT id FROM rooms ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
$count = 1;
foreach($rooms as $room) {
    $stmt = $pdo->prepare("UPDATE rooms SET room_number = :rn WHERE id = :id");
    $stmt->execute([':rn' => $count, ':id' => $room['id']]);
    $count++;
}
echo "Updated room numbers.";
