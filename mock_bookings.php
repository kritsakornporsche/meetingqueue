<?php
require_once __DIR__ . '/api/config.php';

try {
    $pdo = getLocalDB();
    
    // Clear old mock data if needed (optional, let's just append for now to not destroy user's own tests)
    // $pdo->exec("DELETE FROM bookings WHERE title LIKE 'MOCK:%'");
    
    // Get all users
    $stmt = $pdo->query("SELECT id, dept_name FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($users)) {
        die("No users found. Please add users first.\n");
    }
    
    // Get all rooms
    $stmt = $pdo->query("SELECT id, capacity FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rooms)) {
        die("No rooms found.\n");
    }
    
    $titles = [
        'ประชุมประจำเดือน', 'อบรมวิจัย', 'ประชุมกลุ่มหัวหน้างาน ฝ่ายการพยาบาล',
        'ตรวจเท้าเบาหวาน', 'ประชุม กกบ.', 'ปฐมนิเทศพยาบาลใหม่',
        'ประชุมชี้แจงแนวทาง Palliative Care', 'ประชุมคณะกรรมการตรวจรับงานจ้าง',
        'ประชุม HA', 'กิจกรรม วันข้าราชการพลเรือน', 'พิจารณาผลการประกวดราคา',
        'กายภาพบำบัดฟื้นฟูสมรรถภาพปอด', 'ประชุมองค์กรแพทย์', 'ประชุม RLU',
        'ประชุม PCT med', 'กิจกรรมกลุ่มผู้สูงอายุ', 'ประชุม ENV'
    ];
    
    $statuses = ['approved', 'completed', 'completed', 'completed', 'pending', 'rejected'];
    
    $currentYear = date('Y');
    
    echo "Starting to generate mock data for year {$currentYear}...\n";
    
    $count = 0;
    
    // Generate ~100 bookings across the year
    for ($i = 0; $i < 150; $i++) {
        $user = $users[array_rand($users)];
        $room = $rooms[array_rand($rooms)];
        $title = $titles[array_rand($titles)];
        $status = $statuses[array_rand($statuses)];
        
        // Random date in this year
        $month = rand(1, 12);
        $day = rand(1, 28);
        
        // Random time
        $startHour = rand(8, 15);
        $durationHours = rand(1, 4);
        $endHour = min(17, $startHour + $durationHours);
        
        $start_time = sprintf("%04d-%02d-%02d %02d:00:00", $currentYear, $month, $day, $startHour);
        $end_time = sprintf("%04d-%02d-%02d %02d:00:00", $currentYear, $month, $day, $endHour);
        
        $participants = rand(5, $room['capacity']);
        $dept = $user['dept_name'] ?: 'ทั่วไป';
        
        $sql = "INSERT INTO bookings (room_id, user_id, title, description, start_time, end_time, participants_count, department_name, status) 
                VALUES (:room_id, :user_id, :title, :description, :start_time, :end_time, :participants_count, :department_name, :status)";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':room_id' => $room['id'],
            ':user_id' => $user['id'],
            ':title' => $title,
            ':description' => 'จำลองข้อมูลระบบอัตโนมัติ',
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':participants_count' => $participants,
            ':department_name' => $dept,
            ':status' => $status
        ]);
        
        $count++;
    }
    
    echo "Successfully inserted {$count} mock bookings!\n";
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
