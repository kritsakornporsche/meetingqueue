<?php
namespace App\Repository;

use App\Core\Database;
use PDO;

class BookingRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(array $filters = []) {
        $sql = "SELECT b.*, r.name as room_name, u.first_name, u.last_name, u.emp_code 
                FROM bookings b 
                LEFT JOIN rooms r ON b.room_id = r.id 
                JOIN users u ON b.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND b.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        if (!empty($filters['booking_id'])) {
            $sql .= " AND b.id = :booking_id";
            $params[':booking_id'] = $filters['booking_id'];
        }
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $sql .= " AND b.status IN ('pending', 'approved')";
            } else {
                $sql .= " AND b.status = :status";
                $params[':status'] = $filters['status'];
            }
        }

        if (!empty($filters['room_id']) && $filters['room_id'] !== 'all') {
            $sql .= " AND b.room_id = :room_id";
            $params[':room_id'] = $filters['room_id'];
        }

        if (!empty($filters['start']) && !empty($filters['end'])) {
            $sql .= " AND b.start_time >= :start AND b.end_time <= :end";
            $params[':start'] = $filters['start'];
            $params[':end'] = $filters['end'];
        }

        if (!empty($filters['month']) && !empty($filters['year'])) {
            $sql .= " AND MONTH(b.start_time) = :month AND YEAR(b.start_time) = :year";
            $params[':month'] = $filters['month'];
            $params[':year'] = $filters['year'];
        }

        if (!empty($filters['exclude_status'])) {
            $sql .= " AND b.status != :exclude_status";
            $params[':exclude_status'] = $filters['exclude_status'];
        }

        $sql .= " ORDER BY b.start_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function checkConflicts($roomId, $startTime, $endTime) {
        $sql = "SELECT COUNT(*) FROM bookings 
                WHERE room_id = :room_id 
                AND status IN ('pending', 'approved')
                AND (start_time < :end_time AND end_time > :start_time)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':room_id' => $roomId,
            ':start_time' => $startTime,
            ':end_time' => $endTime
        ]);
        
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data) {
        // Run conflict check if not external
        if (!($data['is_external'] ?? false) && !empty($data['room_id'])) {
            if ($this->checkConflicts($data['room_id'], $data['start_time'], $data['end_time'])) {
                throw new \Exception('ห้องนี้ถูกจองไปแล้วในช่วงเวลาดังกล่าว', 409);
            }
        }

        $sql = "INSERT INTO bookings (room_id, user_id, title, description, start_time, end_time, participants_count, phone, attachment_path, department_name, is_external, external_org, status) 
                VALUES (:room_id, :user_id, :title, :description, :start_time, :end_time, :participants_count, :phone, :attachment_path, :department_name, :is_external, :external_org, 'pending')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':room_id' => $data['room_id'] ?? null,
            ':user_id' => $data['user_id'],
            ':title' => $data['title'],
            ':description' => $data['description'] ?? '',
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':participants_count' => $data['participants_count'] ?? 0,
            ':phone' => $data['phone'] ?? '',
            ':attachment_path' => $data['attachment_path'] ?? null,
            ':department_name' => $data['department_name'] ?? null,
            ':is_external' => $data['is_external'] ?? 0,
            ':external_org' => $data['external_org'] ?? null
        ]);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    public function getRoomUsageStats() {
        $sql = "SELECT r.name, COUNT(b.id) as total_bookings, SUM(TIMESTAMPDIFF(MINUTE, b.start_time, b.end_time)) / 60 as total_hours 
                FROM rooms r 
                LEFT JOIN bookings b ON r.id = b.room_id AND b.status = 'approved'
                GROUP BY r.id, r.name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDepartmentStats() {
        $sql = "SELECT department_name, COUNT(*) as total_bookings 
                FROM bookings 
                WHERE status = 'approved' AND department_name IS NOT NULL
                GROUP BY department_name";
        return $this->db->query($sql)->fetchAll();
    }
}
