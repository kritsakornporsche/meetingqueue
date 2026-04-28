<?php
/**
 * Configuration for Meeting Queue System
 * Database and API connections
 */

// Local Database (Meeting Queue)
define('DB_HOST', '192.168.9.234');
define('DB_USER', 'meetingqueue');
define('DB_PASS', 'Meeting@11190');
define('DB_NAME', 'meetingqueue_db');

// External Database (ZK BioTime Authentication API)
define('ZK_HOST', '192.168.9.7');
define('ZK_USER', 'meeting7');
define('ZK_PASS', 'meeting@11190');
define('ZK_NAME', 'zkbiotime');

/**
 * PDO Connection Factory for Local DB
 */
function getLocalDB(): PDO {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

/**
 * PDO Connection Factory for ZK BioTime DB
 */
function getZKDB(): PDO {
    $dsn = "mysql:host=" . ZK_HOST . ";dbname=" . ZK_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, ZK_USER, ZK_PASS, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Response Helper
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}
?>
