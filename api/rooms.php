<?php
require_once 'config.php';

/**
 * Rooms API
 * Method: GET
 */

try {
    $pdo = getLocalDB();
    $stmt = $pdo->query("SELECT * FROM rooms WHERE status = 'available' ORDER BY name ASC");
    $rooms = $stmt->fetchAll();

    jsonResponse([
        'success' => true,
        'rooms' => $rooms
    ]);

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
}
?>
