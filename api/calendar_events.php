<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

try {
    $pdo = getLocalDB();
    
    $start = $_GET['start'] ?? null;
    $end = $_GET['end'] ?? null;
    $room_id = $_GET['room_id'] ?? null;
    
    $sql = "SELECT b.id, b.title, b.start_time as start, b.end_time as end, b.status, b.participants_count, r.name as room_name, r.id as room_id_val, u.first_name, u.last_name
            FROM bookings b 
            LEFT JOIN rooms r ON b.room_id = r.id 
            JOIN users u ON b.user_id = u.id
            WHERE b.status != 'cancelled'";
    $params = [];
    
    if ($start && $end) {
        $sql .= " AND b.start_time >= :start AND b.end_time <= :end";
        $params[':start'] = $start;
        $params[':end'] = $end;
    }
    
    if ($room_id && $room_id !== 'all') {
        $sql .= " AND b.room_id = :room_id";
        $params[':room_id'] = $room_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll();
    
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
            'start' => $e['start'],
            'end' => $e['end'],
            'backgroundColor' => $colors[$e['status']] ?? '#94a3b8',
            'borderColor' => $colors[$e['status']] ?? '#94a3b8',
            'extendedProps' => [
                'status' => $e['status'],
                'room' => $e['room_name'] ?? 'ภายนอกสถานที่',
                'user' => $e['first_name'] . ' ' . $e['last_name'],
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
