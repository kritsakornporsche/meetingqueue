<?php
require_once 'api/config.php';

/**
 * Database Setup & Initial Sync Script
 */

try {
    // 0. Create Database if not exists
    echo "<h1>⚙️ System Initializing...</h1>";
    echo "<h3>0. Creating Database...</h3>";
    
    // Connect to MySQL server without selecting a database
    $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
    $tmpPdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $tmpPdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color: green;'>✅ Database '" . DB_NAME . "' is ready.</p>";

    // Now connect to the actual database
    $pdo = getLocalDB();
    
    // 1. Create Tables
    echo "<h3>1. Creating Tables...</h3>";
    $sql = file_get_contents('schema.sql');
    if (!$sql) throw new Exception("Cannot find schema.sql");
    
    // Clean schema.sql: remove database creation since we already did it
    $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS.*;/i', '', $sql);
    $sql = preg_replace('/USE .*; /i', '', $sql);

    $queries = explode(';', $sql);
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) $pdo->exec($query);
    }
    echo "<p style='color: green;'>✅ Tables created successfully.</p>";

    // 2. Initial Sync from ZK BioTime
    echo "<h3>2. Syncing Users from ZK BioTime...</h3>";
    try {
        $zkPdo = getZKDB();
        
        $zkQuery = "
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
            SELECT first_name, cid, position_name, dept_name, emp_code, photo
            FROM cte WHERE rn = 1 
            ORDER BY first_name ASC
        ";

        $stmt = $zkPdo->query($zkQuery);
        $zkUsers = $stmt->fetchAll();

        $upsertSql = "
            INSERT INTO users (emp_code, username, password, first_name, last_name, position_name, dept_name, photo)
            VALUES (:emp_code, :username, :password, :first_name, :last_name, :position_name, :dept_name, :photo)
            ON DUPLICATE KEY UPDATE
                username = VALUES(username),
                password = VALUES(password),
                first_name = VALUES(first_name),
                last_name = VALUES(last_name),
                position_name = VALUES(position_name),
                dept_name = VALUES(dept_name),
                photo = VALUES(photo)
        ";
        $upsertStmt = $pdo->prepare($upsertSql);

        $count = 0;
        foreach ($zkUsers as $user) {
            $upsertStmt->execute([
                ':emp_code' => $user['emp_code'],
                ':username' => $user['first_name'],
                ':password' => $user['cid'],
                ':first_name' => $user['first_name'],
                ':last_name' => '', // Don't show 13-digit CID as last_name
                ':position_name' => $user['position_name'],
                ':dept_name' => $user['dept_name'],
                ':photo' => $user['photo']
            ]);
            $count++;
        }
        echo "<p style='color: green;'>✅ Sync completed: <b>$count</b> users imported.</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ Could not sync with ZK BioTime: " . $e->getMessage() . "</p>";
        echo "<p><i>System will continue with existing data (if any). You can create an admin manually using create_admin.php</i></p>";
    }

    echo "<hr>";
    echo "<h2>🎉 System is Ready!</h2>";
    echo "<p>You can now login with your First Name and CID.</p>";
    echo "<a href='index.php' style='display: inline-block; padding: 12px 25px; background: #4f46e5; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;'>Go to Login Page</a>";

} catch (Exception $e) {
    echo "<h1>❌ Setup Failed</h1>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
