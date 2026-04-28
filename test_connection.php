<?php
require_once 'api/config.php';

$results = [
    'local_db' => ['status' => 'pending', 'message' => ''],
    'zk_db' => ['status' => 'pending', 'message' => ''],
    'zk_query' => ['status' => 'pending', 'data' => [], 'count' => 0]
];

// Test Local DB
try {
    $localPdo = getLocalDB();
    $results['local_db']['status'] = 'success';
    $results['local_db']['message'] = 'เชื่อมต่อกับ 192.168.9.234 สำเร็จ';
} catch (Exception $e) {
    $results['local_db']['status'] = 'error';
    $results['local_db']['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

// Test ZK DB
try {
    $zkPdo = getZKDB();
    $results['zk_db']['status'] = 'success';
    $results['zk_db']['message'] = 'เชื่อมต่อกับ 192.168.9.7 สำเร็จ';

    // Run the specific query provided by user
    $query = "
        WITH cte AS (
            SELECT
                pe.first_name,        
                pe.last_name AS cid, 
                pp.position_name,          
                pd.dept_name,           
                i.emp_code,             
                pe.photo,                          
                ROW_NUMBER() OVER (
                    PARTITION BY i.emp_code
                    ORDER BY i.punch_time DESC         
                ) AS rn
            FROM iclock_transaction AS i    
            LEFT JOIN personnel_employee AS pe ON i.emp_code = pe.emp_code
            LEFT JOIN personnel_department AS pd ON pe.department_id = pd.dept_code
            LEFT JOIN personnel_position AS pp ON pe.position_id = pp.id 
            WHERE pe.`status` = 0
              AND pe.last_name IS NOT NULL 
              AND pe.last_name != ''
              AND (
                    ( i.punch_time >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                      AND i.punch_time <  DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
                    )
                    OR i.punch_time = DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 DAY)
                  )
        )
        SELECT  
            first_name,
            cid,
            position_name,
            dept_name,
            emp_code,
            photo
        FROM cte
        WHERE rn = 1 
        ORDER BY first_name ASC
    ";

    $stmt = $zkPdo->query($query);
    $data = $stmt->fetchAll();
    $results['zk_query']['status'] = 'success';
    $results['zk_query']['data'] = array_slice($data, 0, 5); // Show first 5 for testing
    $results['zk_query']['count'] = count($data);

} catch (Exception $e) {
    $results['zk_db']['status'] = 'error';
    $results['zk_db']['message'] = $e->getMessage();
    $results['zk_query']['status'] = 'error';
    $results['zk_query']['message'] = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทดสอบการเชื่อมต่อ | MeetQueue</title>
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #f9fafb;
            --card: #ffffff;
            --text: #111827;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            max-width: 900px;
            width: 100%;
        }
        .card {
            background: var(--card);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            margin-bottom: 1.5rem;
        }
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-success { background: #d1fae5; color: #065f46; }
        .status-error { background: #fee2e2; color: #991b1b; }
        pre {
            background: #1f2937;
            color: #f3f4f6;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 0.875rem;
        }
        h1 { margin-bottom: 2rem; color: var(--primary); }
        h2 { margin-top: 0; font-size: 1.25rem; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .photo-preview {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ตรวจสอบสถานะการเชื่อมต่อระบบ</h1>

        <div class="grid">
            <div class="card">
                <h2>ฐานข้อมูลในเครื่อง (Meeting Queue)</h2>
                <p>IP: 192.168.9.234</p>
                <span class="status status-<?php echo $results['local_db']['status']; ?>">
                    <?php echo $results['local_db']['status'] === 'success' ? 'สำเร็จ' : 'ล้มเหลว'; ?>
                </span>
                <p><?php echo $results['local_db']['message']; ?></p>
            </div>

            <div class="card">
                <h2>Authentication API (ZK BioTime)</h2>
                <p>IP: 192.168.9.7</p>
                <span class="status status-<?php echo $results['zk_db']['status']; ?>">
                    <?php echo $results['zk_db']['status'] === 'success' ? 'สำเร็จ' : 'ล้มเหลว'; ?>
                </span>
                <p><?php echo $results['zk_db']['message']; ?></p>
            </div>
        </div>

        <div class="card">
            <h2>ทดสอบดึงข้อมูลผู้ใช้ (5 รายการแรก)</h2>
            <p>พบผู้ใช้งานทั้งหมด: <strong><?php echo $results['zk_query']['count']; ?></strong> ท่าน</p>
            
            <?php if ($results['zk_query']['status'] === 'success'): ?>
                <table>
                    <thead>
                        <tr>
                            <th>รูปภาพ</th>
                            <th>ชื่อ (Username)</th>
                            <th>รหัสผ่าน (Pass/CID)</th>
                            <th>ตำแหน่ง</th>
                            <th>แผนก</th>
                            <th>รหัสพนักงาน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results['zk_query']['data'] as $row): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $photo_url = "https://192.168.9.7/auth_files/photo/" . $row['emp_code'] . ".jpg";
                                    ?>
                                    <img src="<?php echo $photo_url; ?>" 
                                         onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($row['first_name']); ?>&background=e5e7eb&color=666'" 
                                         class="photo-preview">
                                </td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['cid']); ?></td>
                                <td><?php echo htmlspecialchars($row['position_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_code']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="status status-error">ข้อผิดพลาด</div>
                <p><?php echo $results['zk_query']['message'] ?? 'Query ล้มเหลว'; ?></p>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="index.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">← กลับสู่หน้าหลัก</a>
        </div>
    </div>
</body>
</html>
