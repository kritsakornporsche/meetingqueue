<?php
require_once __DIR__ . '/api/config.php';

try {
    $pdo = getLocalDB();
    
    // Clear old data
    echo "Cleaning up old data...\n";
    $pdo->exec("DELETE FROM bookings");
    $pdo->exec("ALTER TABLE bookings AUTO_INCREMENT = 1");
    
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
        'ประชุม PCT med', 'กิจกรรมกลุ่มผู้สูงอายุ', 'ประชุม ENV',
        'ประชุมคณะกรรมการบริหาร รพ.', 'วางแผนกลยุทธ์ 2569', 'อบรมป้องกันอัคคีภัย',
        'ประชุมคุณภาพความปลอดภัย', 'อบรมความปลอดภัยด้านข้อมูล'
    ];
    
    $currentYear = 2026; // Setting to current year as per environment
    $today = date('Y-m-d');
    
    echo "Generating mock data for Year 2026...\n";
    
    // Insert past bookings (Jan 2026 - April 2026 before today)
    for ($month = 1; $month <= 4; $month++) {
        $bookingsPerMonth = rand(15, 25);
        for ($i = 0; $i < $bookingsPerMonth; $i++) {
            $day = rand(1, 28);
            $date = sprintf("%04d-%02d-%02d", $currentYear, $month, $day);
            
            // Skip if it's today or future
            if ($date >= $today) continue;

            $hour = rand(8, 15);
            $duration = rand(1, 4);
            $start = "$date " . sprintf("%02d:00:00", $hour);
            $end = "$date " . sprintf("%02d:00:00", $hour + $duration);
            
            $room = $rooms[array_rand($rooms)];
            $user = $users[array_rand($users)];
            $status = (rand(0, 10) > 2) ? 'completed' : 'rejected';

            $pdo->prepare("INSERT INTO bookings (room_id, user_id, title, start_time, end_time, participants_count, department_name, status) 
                    VALUES (:room_id, :user_id, :title, :start_time, :end_time, :participants_count, :department_name, :status)")
                ->execute([
                    ':room_id' => $room['id'],
                    ':user_id' => $user['id'],
                    ':title' => $titles[array_rand($titles)],
                    ':start_time' => $start,
                    ':end_time' => $end,
                    ':participants_count' => rand(5, $room['capacity']),
                    ':department_name' => $user['dept_name'] ?: 'ทั่วไป',
                    ':status' => $status
                ]);
        }
    }

    // Insert TODAY's special bookings for demo
    echo "Generating special mock data for TODAY...\n";
    foreach ($rooms as $room) {
        // Active Meeting
        $start_active = date('Y-m-d H:i:s', strtotime('-45 minutes'));
        $end_active = date('Y-m-d H:i:s', strtotime('+75 minutes'));
        $user = $users[array_rand($users)];
        
        $pdo->prepare("INSERT INTO bookings (room_id, user_id, title, start_time, end_time, participants_count, department_name, status) 
                VALUES (:room_id, :user_id, :title, :start_time, :end_time, :participants_count, :department_name, 'approved')")
            ->execute([
                ':room_id' => $room['id'],
                ':user_id' => $user['id'],
                ':title' => "กำลังดำเนินอยู่: " . $titles[array_rand($titles)],
                ':start_time' => $start_active,
                ':end_time' => $end_active,
                ':participants_count' => rand(5, $room['capacity']),
                ':department_name' => $user['dept_name'] ?: 'ทั่วไป'
            ]);

        // Upcoming Meeting today
        $start_next = date('Y-m-d H:i:s', strtotime('+2 hours'));
        $end_next = date('Y-m-d H:i:s', strtotime('+4 hours'));
        $user = $users[array_rand($users)];

        $pdo->prepare("INSERT INTO bookings (room_id, user_id, title, start_time, end_time, participants_count, department_name, status) 
                VALUES (:room_id, :user_id, :title, :start_time, :end_time, :participants_count, :department_name, 'approved')")
            ->execute([
                ':room_id' => $room['id'],
                ':user_id' => $user['id'],
                ':title' => "รายการถัดไป: " . $titles[array_rand($titles)],
                ':start_time' => $start_next,
                ':end_time' => $end_next,
                ':participants_count' => rand(5, $room['capacity']),
                ':department_name' => $user['dept_name'] ?: 'ทั่วไป'
            ]);
    }

    // Insert FUTURE bookings (May 2026 - Dec 2026)
    for ($month = 5; $month <= 12; $month++) {
        $bookingsPerMonth = rand(10, 20);
        for ($i = 0; $i < $bookingsPerMonth; $i++) {
            $day = rand(1, 28);
            $date = sprintf("%04d-%02d-%02d", $currentYear, $month, $day);
            $hour = rand(8, 15);
            $duration = rand(1, 4);
            $start = "$date " . sprintf("%02d:00:00", $hour);
            $end = "$date " . sprintf("%02d:00:00", $hour + $duration);
            
            $room = $rooms[array_rand($rooms)];
            $user = $users[array_rand($users)];
            $status = (rand(0, 10) > 4) ? 'approved' : 'pending';

            $pdo->prepare("INSERT INTO bookings (room_id, user_id, title, start_time, end_time, participants_count, department_name, status) 
                    VALUES (:room_id, :user_id, :title, :start_time, :end_time, :participants_count, :department_name, :status)")
                ->execute([
                    ':room_id' => $room['id'],
                    ':user_id' => $user['id'],
                    ':title' => $titles[array_rand($titles)],
                    ':start_time' => $start,
                    ':end_time' => $end,
                    ':participants_count' => rand(5, $room['capacity']),
                    ':department_name' => $user['dept_name'] ?: 'ทั่วไป',
                    ':status' => $status
                ]);
        }
    }
    
    echo "Successfully generated full mock dataset for 2026!\n";
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
