<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

use App\Repository\BookingRepository;

$repo = new BookingRepository();

try {
    $filters = [
        'start' => $_GET['start'] ?? null,
        'end' => $_GET['end'] ?? null,
        'room_id' => $_GET['room_id'] ?? null,
        'exclude_status' => 'cancelled'
    ];
    
    $events = $repo->getAll($filters);
    
    // Map status to colors
    $colors = [
        'pending' => '#D4A373',  // Sand
        'approved' => '#5C715E', // Leaf Green
        'rejected' => '#BC6C25', // Autumn Orange
        'completed' => '#6E4B3A' // Root Brown
    ];
    
    $formattedEvents = array_map(function($e) use ($colors) {
        return [
            'id' => $e['id'],
            'title' => ($e['room_name'] ?? 'ภายนอก') . ': ' . $e['title'],
            'start' => $e['start_time'],
            'end' => $e['end_time'],
            'backgroundColor' => $colors[$e['status']] ?? '#94a3b8',
            'borderColor' => $colors[$e['status']] ?? '#94a3b8',
            'extendedProps' => [
                'status' => $e['status'],
                'room' => $e['room_name'] ?? 'ภายนอกสถานที่',
                'user' => $e['first_name'] . ' ' . ($e['last_name'] ?? ''),
                'original_title' => $e['title'],
                'participants' => $e['participants_count'] ?? 0
            ]
        ];
    }, $events);
    
    header('Content-Type: application/json');
    echo json_encode($formattedEvents);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
