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
