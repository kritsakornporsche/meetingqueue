<?php
require 'api/config.php';
$pdo = getLocalDB();
$rooms = $pdo->query('SELECT * FROM rooms')->fetchAll(PDO::FETCH_ASSOC);
print_r($rooms);
