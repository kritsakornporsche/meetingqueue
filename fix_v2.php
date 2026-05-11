<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
$string = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');

// Reconstruct original UTF-8 bytes by converting mojibake chars back to their TIS-620 byte values
$original_bytes = iconv('UTF-8', 'TIS-620//IGNORE', $string);

if (strpos($original_bytes, "ห้องประชุม") !== false) {
    echo "Success! Found 'ห้องประชุม' after TIS-620 reversal.\n";
} else {
    echo "Failed. Trying Windows-1252 as fallback...\n";
    $original_bytes = iconv('UTF-8', 'Windows-1252//IGNORE', $string);
    if (strpos($original_bytes, "ห้องประชุม") !== false) {
        echo "Success! Found 'ห้องประชุม' after Windows-1252 reversal.\n";
    }
}

file_put_contents('restored_clean_v2.sql', $original_bytes);
?>
