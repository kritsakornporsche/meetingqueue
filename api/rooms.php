<?php
require_once 'config.php';

/**
 * Rooms API
 * Method: GET
 */

use App\Repository\RoomRepository;

$repo = new RoomRepository();
$status = $_GET['status'] ?? 'available';

try {
    $rooms = $repo->getAll($status);

    jsonResponse([
        'success' => true,
        'rooms' => $rooms
    ]);

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
}
?>
