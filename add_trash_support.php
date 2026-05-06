<?php
require_once 'api/config.php';

try {
    $pdo = \App\Core\Database::getInstance()->getConnection();
    
    // Add deleted_at column if it doesn't exist
    $pdo->exec("ALTER TABLE bookings ADD COLUMN deleted_at DATETIME DEFAULT NULL");
    
    echo "Successfully added deleted_at column to bookings table.\n";
} catch (Exception $e) {
    echo "Error or already exists: " . $e->getMessage() . "\n";
}
