<?php
require_once 'api/config.php';

try {
    $db = \App\Core\Database::getInstance()->getConnection();
    
    // Add images column to rooms
    $db->exec("ALTER TABLE rooms ADD COLUMN images TEXT DEFAULT NULL AFTER image_url");
    echo "Column 'images' added successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
