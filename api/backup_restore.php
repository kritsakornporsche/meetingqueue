<?php
require_once 'config.php';

// Ensure only admin can access this file
if (!isset($_SESSION['user_data']) || ($_SESSION['user_data']['role'] ?? 'user') !== 'admin') {
    jsonResponse(['success' => false, 'message' => 'Unauthorized Access. Admin only.'], 403);
}

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$backups_dir = __DIR__ . '/../backups/';

if (!is_dir($backups_dir)) {
    mkdir($backups_dir, 0755, true);
}

switch ($action) {
    case 'backup':
        try {
            $db = getLocalDB();
            $tables = [];
            $result = $db->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sqlScript = "-- Database Backup\n";
            $sqlScript .= "-- Host: " . DB_HOST . "\n";
            $sqlScript .= "-- Database: " . DB_NAME . "\n";
            $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                // Table structure
                $query = "SHOW CREATE TABLE `$table`";
                $result = $db->query($query);
                $row = $result->fetch(PDO::FETCH_NUM);
                $sqlScript .= "\nDROP TABLE IF EXISTS `$table`;\n";
                $sqlScript .= $row[1] . ";\n\n";

                // Table data
                $query = "SELECT * FROM `$table`";
                $result = $db->query($query);
                $columnCount = $result->columnCount();

                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $sqlScript .= "INSERT INTO `$table` VALUES(";
                    for ($j = 0; $j < $columnCount; $j++) {
                        $row[$j] = $row[$j] !== null ? addslashes($row[$j]) : null;
                        
                        if ($row[$j] === null) {
                            $sqlScript .= "NULL";
                        } else {
                            // Replace newlines and carriage returns properly
                            $row[$j] = str_replace("\n", "\\n", $row[$j]);
                            $row[$j] = str_replace("\r", "\\r", $row[$j]);
                            $sqlScript .= "'" . $row[$j] . "'";
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
                $sqlScript .= "\n";
            }

            $sqlScript .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

            $backup_file_name = 'backup_' . DB_NAME . '_' . date('Ymd_His') . '.sql';
            $fileHandler = fopen($backups_dir . $backup_file_name, 'w+');
            fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);

            jsonResponse(['success' => true, 'message' => 'สร้างไฟล์สำรองข้อมูลสำเร็จ', 'filename' => $backup_file_name]);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
        break;

    case 'list':
        try {
            $files = [];
            if ($handle = opendir($backups_dir)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) === 'sql') {
                        $files[] = [
                            'name' => $entry,
                            'size' => filesize($backups_dir . $entry),
                            'date' => filemtime($backups_dir . $entry)
                        ];
                    }
                }
                closedir($handle);
            }
            
            // Sort by date descending (newest first)
            usort($files, function($a, $b) {
                return $b['date'] - $a['date'];
            });

            jsonResponse(['success' => true, 'files' => $files]);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการดึงรายการไฟล์: ' . $e->getMessage()], 500);
        }
        break;

    case 'delete':
        $filename = $_POST['filename'] ?? '';
        if (empty($filename) || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            jsonResponse(['success' => false, 'message' => 'ชื่อไฟล์ไม่ถูกต้อง'], 400);
        }

        $filepath = $backups_dir . $filename;
        if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) === 'sql') {
            unlink($filepath);
            jsonResponse(['success' => true, 'message' => 'ลบไฟล์สำเร็จ']);
        } else {
            jsonResponse(['success' => false, 'message' => 'ไม่พบไฟล์ดังกล่าว'], 404);
        }
        break;

    case 'restore':
        $filename = $_POST['filename'] ?? '';
        if (empty($filename) || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            jsonResponse(['success' => false, 'message' => 'ชื่อไฟล์ไม่ถูกต้อง'], 400);
        }

        $filepath = $backups_dir . $filename;
        if (!file_exists($filepath)) {
            jsonResponse(['success' => false, 'message' => 'ไม่พบไฟล์ดังกล่าว'], 404);
        }

        try {
            $db = getLocalDB();
            
            // Basic restore implementation using PDO
            $sql = file_get_contents($filepath);
            
            // Remove comments and empty lines
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/^\s*$/m', '', $sql);
            
            // Split into individual queries based on semicolon, but need to be careful with semicolons inside strings
            // A more robust approach is to just execute the whole file directly if PDO permits multiple statements
            
            // Enable multiple statements in PDO if not already
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            jsonResponse(['success' => true, 'message' => 'กู้คืนฐานข้อมูลเรียบร้อยแล้ว']);
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'message' => 'กู้คืนไม่สำเร็จ: ' . $e->getMessage()], 500);
        }
        break;

    case 'download':
        $filename = $_GET['filename'] ?? '';
        if (empty($filename) || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            die('Invalid filename');
        }

        $filepath = $backups_dir . $filename;
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush();
            readfile($filepath);
            exit;
        } else {
            die('File not found');
        }
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}
