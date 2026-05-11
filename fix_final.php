<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
if (substr($data, 0, 2) === "\xff\xfe") $data = substr($data, 2);
$string = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');

// Reversing mojibake:
// The original UTF-8 bytes were treated as Windows-1252 characters.
// We convert them back to those bytes.
$raw_bytes = mb_convert_encoding($string, 'Windows-1252', 'UTF-8');

if (strpos($raw_bytes, "ห้องประชุม") !== false) {
    echo "Success! Found 'ห้องประชุม' after Windows-1252 reversal.\n";
} else {
    echo "Failed with Windows-1252. Trying ISO-8859-1...\n";
    $raw_bytes = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    if (strpos($raw_bytes, "ห้องประชุม") !== false) {
        echo "Success! Found 'ห้องประชุม' after ISO-8859-1 reversal.\n";
    } else {
        echo "Still failed. Let's dump a bit of raw_bytes to see what we have.\n";
        echo "Snippet hex: " . bin2hex(substr($raw_bytes, 0, 200)) . "\n";
    }
}

file_put_contents('restored_final.sql', $raw_bytes);
?>
