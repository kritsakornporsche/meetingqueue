<?php
require_once 'config.php';

/**
 * Bookings API
 * GET: Fetch bookings
 * POST: Create booking (Standard or External)
 */

use App\Repository\BookingRepository;

$method = $_SERVER['REQUEST_METHOD'];
$repo = new BookingRepository();

if (!isset($_SESSION['user_id'])) {
    jsonResponse(['success' => false, 'message' => 'ไม่มีสิทธิ์เข้าถึง'], 401);
}

try {
    if ($method === 'GET') {
        $filters = [
            'user_id' => $_GET['user_id'] ?? null,
            'booking_id' => $_GET['booking_id'] ?? null,
            'status' => $_GET['status'] ?? null
        ];
        
        $bookings = $repo->getAll($filters);

        jsonResponse([
            'success' => true,
            'bookings' => $bookings
        ]);

    } elseif ($method === 'POST') {
        $title = $_POST['title'] ?? null;
        $roomId = $_POST['room_id'] ?? null;
        $meetingDate = $_POST['meeting_date'] ?? null;
        $startTime = $_POST['start_time'] ?? null;
        $endTime = $_POST['end_time'] ?? null;
        $participants = $_POST['participants_count'] ?? 0;
        $phone = $_POST['phone'] ?? null;
        $description = $_POST['description'] ?? '';
        $equipments = $_POST['equipments'] ?? '';
        if (!empty($equipments)) {
            $description .= ($description ? "\n\n" : "") . "อุปกรณ์ที่ต้องการ: " . $equipments;
        }
        $isExternal = isset($_POST['is_external']) ? (bool)$_POST['is_external'] : false;
        $externalOrg = $_POST['external_org'] ?? null;
        $department = $_SESSION['user_data']['dept_name'] ?? null;

        if (!$title || !$startTime || !$endTime) {
            jsonResponse(['success' => false, 'message' => "กรุณากรอกข้อมูลให้ครบถ้วน"], 400);
        }

        // Full start/end times
        $fullStart = $meetingDate . ' ' . $startTime;
        $fullEnd = $meetingDate . ' ' . $endTime;

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

        $success = $repo->create([
            'room_id' => $isExternal ? null : $roomId,
            'user_id' => $_SESSION['user_id'],
            'title' => $title,
            'description' => $description,
            'start_time' => $fullStart,
            'end_time' => $fullEnd,
            'participants_count' => $participants,
            'phone' => $phone,
            'attachment_path' => $attachmentPath,
            'department_name' => $department,
            'is_external' => $isExternal ? 1 : 0,
            'external_org' => $externalOrg
        ]);

        if ($success) {
            jsonResponse([
                'success' => true,
                'message' => 'ส่งคำขอจองห้องประชุมสำเร็จ รอเจ้าหน้าที่อนุมัติ',
                'id' => \App\Core\Database::getInstance()->getConnection()->lastInsertId()
            ]);
        } else {
            jsonResponse(['success' => false, 'message' => 'ไม่สามารถบันทึกข้อมูลได้'], 500);
        }
    } elseif ($method === 'PATCH') {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['booking_id'] ?? null;
        $status = $input['status'] ?? null;

        if (!$id || !$status) {
            jsonResponse(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน'], 400);
        }

        // Only admins can approve/reject
        if (($_SESSION['user_data']['role'] ?? 'user') !== 'admin') {
            jsonResponse(['success' => false, 'message' => 'ไม่มีสิทธิ์ดำเนินการ'], 403);
        }

        $success = $repo->updateStatus($id, $status);
        
        if ($success) {
            jsonResponse(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
        } else {
            jsonResponse(['success' => false, 'message' => 'ไม่สามารถอัปเดตสถานะได้'], 500);
        }
    }
} catch (Exception $e) {
    $code = $e->getCode();
    if ($code < 400 || $code >= 600) $code = 500;
    jsonResponse(['success' => false, 'message' => 'ข้อผิดพลาด: ' . $e->getMessage()], $code);
}
?>
