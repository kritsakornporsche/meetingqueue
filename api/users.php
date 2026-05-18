<?php
require_once 'config.php';

/**
 * Get User List API
 * Method: GET
 */

try {
    $pdo = getLocalDB();
    $stmt = $pdo->query("SELECT id, emp_code, username, first_name, last_name, position_name, dept_name, role FROM users ORDER BY first_name ASC");
    $users = $stmt->fetchAll();
    
    foreach ($users as &$u) {
        if (isset($u['first_name'])) $u['first_name'] = cleanName($u['first_name']);
        if (isset($u['last_name'])) $u['last_name'] = cleanName($u['last_name']);
    }

    jsonResponse([
        'success' => true,
        'data' => $users
    ]);

} catch (Exception $e) {
    jsonResponse([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ], 500);
}
?>
