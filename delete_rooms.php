<?php
require 'api/config.php';
$pdo = getLocalDB();
// Delete duplicate rooms (ID >= 7)
$stmt = $pdo->prepare("DELETE FROM rooms WHERE id >= 7");
$stmt->execute();
echo "Deleted duplicate rooms count: " . $stmt->rowCount();
