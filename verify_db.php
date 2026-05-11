<?php
require_once 'api/config.php';
$db = \App\Core\Database::getInstance()->getConnection();
$room = $db->query("SELECT name FROM rooms LIMIT 1")->fetchColumn();
echo "Room Name: " . $room . "\n";
echo "Length: " . strlen($room) . "\n";
echo "Is UTF-8: " . (mb_check_encoding($room, 'UTF-8') ? 'Yes' : 'No') . "\n";
?>
