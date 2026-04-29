<?php
require 'api/config.php';
$pdo = getLocalDB();
// Delete duplicate rooms
$stmt = $pdo->prepare("DELETE FROM rooms WHERE id >= 7");
$stmt->execute();
echo "Deleted duplicate rooms.";
