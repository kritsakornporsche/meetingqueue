<?php
require_once 'config.php';

/**
 * Bookings API
 * GET: Fetch bookings
 * POST: Create booking (Standard or External)
 */

$method = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['user_id'])) {
    jsonResponse(['success' => false, 'message' => 'ไม่มีสิทธิ์เข้าถึง'], 401);
}

try {
    $pdo = getLocalDB();

    if ($method === 'GET') {
        $userId = $_GET['user_id'] ?? null;
        $roomId = $_GET['room_id'] ?? null;
        $status = $_GET['status'] ?? null;
        $bookingId = $_GET['booking_id'] ?? null;
        
        $sql = "SELECT b.*, r.name as room_name, u.first_name, u.last_name, u.emp_code 
                FROM bookings b 
                LEFT JOIN rooms r ON b.room_id = r.id 
                JOIN users u ON b.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if ($userId) {
            $sql .= " AND b.user_id = :user_id";
            $params[':user_id'] = $userId;
        }
        if ($roomId) {
            $sql .= " AND b.room_id = :room_id";
            $params[':room_id'] = $roomId;
        }
        if ($status) {
            $sql .= " AND b.status = :status";
            $params[':status'] = $status;
        }
        if ($bookingId) {
            $sql .= " AND b.id = :booking_id";
            $params[':booking_id'] = $bookingId;
        }

        $sql .= " ORDER BY b.start_time DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();

        jsonResponse([
            'success' => true,
            'bookings' => $bookings
        ]);

    } elseif ($method === 'POST') {
        // Handle multipart/form-data for file uploads
        $title = $_POST['title'] ?? null;
        $roomId = $_POST['room_id'] ?? null;
        $startTime = $_POST['start_time'] ?? null;
        $endTime = $_POST['end_time'] ?? null;
        $participants = $_POST['participants_count'] ?? 0;
        $phone = $_POST['phone'] ?? null;
        $description = $_POST['description'] ?? '';
        $isExternal = isset($_POST['is_external']) ? (bool)$_POST['is_external'] : false;
        $externalOrg = $_POST['external_org'] ?? null;
        $department = $_SESSION['user_data']['dept_name'] ?? null;

        // If JSON was sent (fallback)
        if (!$title) {
            $input = json_decode(file_get_contents('php://input'), true);
            if ($input) {
                $title = $input['title'] ?? null;
                $roomId = $input['room_id'] ?? null;
                $startTime = $input['start_time'] ?? null;
                $endTime = $input['end_time'] ?? null;
                $participants = $input['participants_count'] ?? 0;
                $phone = $input['phone'] ?? null;
                $description = $input['description'] ?? '';
                $isExternal = $input['is_external'] ?? false;
                $externalOrg = $input['external_org'] ?? null;
            }
        }
        
        if (!$title || !$startTime || !$endTime) {
            jsonResponse(['success' => false, 'message' => "กรุณากรอกข้อมูลให้ครบถ้วน"], 400);
        }

        // Check for conflicts if it's an internal room
        if (!$isExternal && $roomId) {
            $conflictSql = "SELECT COUNT(*) FROM bookings 
                            WHERE room_id = :room_id 
                            AND status NOT IN ('cancelled', 'rejected')
                            AND (
                                (start_time < :end_time AND end_time > :start_time)
                            )";
            $stmt = $pdo->prepare($conflictSql);
            $stmt->execute([
                ':room_id' => $roomId,
                ':start_time' => $startTime,
                ':end_time' => $endTime
            ]);
            
            if ($stmt->fetchColumn() > 0) {
                jsonResponse(['success' => false, 'message' => 'ห้องนี้ถูกจองไปแล้วในช่วงเวลาดังกล่าว'], 409);
            }
        }

        // Handle File Upload
        $attachmentPath = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/bookings/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $fileExt = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExt;
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $fileName)) {
                $attachmentPath = 'uploads/bookings/' . $fileName;
            }
        }

        $sql = "INSERT INTO bookings (room_id, user_id, title, description, start_time, end_time, participants_count, phone, attachment_path, department_name, is_external, external_org, status) 
                VALUES (:room_id, :user_id, :title, :description, :start_time, :end_time, :participants_count, :phone, :attachment_path, :department_name, :is_external, :external_org, 'pending')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':room_id' => $isExternal ? null : $roomId,
            ':user_id' => $_SESSION['user_id'],
            ':title' => $title,
            ':description' => $description,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
            ':participants_count' => $participants,
            ':phone' => $phone,
            ':attachment_path' => $attachmentPath,
            ':department_name' => $department,
            ':is_external' => $isExternal ? 1 : 0,
            ':external_org' => $externalOrg
        ]);

        jsonResponse([
            'success' => true,
            'message' => 'ส่งคำขอจองห้องประชุมสำเร็จ รอเจ้าหน้าที่อนุมัติ',
            'id' => $pdo->lastInsertId()
        ]);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'ข้อผิดพลาด: ' . $e->getMessage()], 500);
}
?>
