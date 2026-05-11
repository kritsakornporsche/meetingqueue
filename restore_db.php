<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');

// Check for UTF-16LE BOM
if (substr($data, 0, 2) === "\xff\xfe") {
    echo "UTF-16LE BOM detected\n";
    $data = substr($data, 2);
    $data = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');
} elseif (strpos($data, "\x00") !== false) {
    echo "Null bytes detected, assuming UTF-16LE without BOM\n";
    $data = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');
} else {
    echo "No UTF-16 detected, assuming UTF-8\n";
}

// Check for any remaining garbage
if (strpos($data, "เธซเน") !== false) {
    echo "Warning: Detected common mojibake pattern in converted data\n";
}

file_put_contents('restored_clean.sql', $data);
echo "File written to restored_clean.sql. Length: " . strlen($data) . "\n";
?>
