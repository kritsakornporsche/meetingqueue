<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');

// Extract raw bytes from UTF-16LE (remove BOM)
if (substr($data, 0, 2) === "\xff\xfe") {
    $data = substr($data, 2);
}
// $data is now the raw bytes of the UTF-16LE file.
// Since PowerShell's > operator just takes the string and encodes it as UTF-16LE,
// the "string" it had was already mojibake.

// Let's try to convert it to a string using UTF-16LE
$string = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');

// Now $string contains "เธซเน...".
// This means the original string WAS "เธซเน...".
// This happens if the terminal output of mysqldump was UTF-8 but the shell thought it was something else.

// To fix: convert UTF-8 string to ISO-8859-1 bytes, then treat those bytes as UTF-8.
$fixed = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');

if (strpos($fixed, "ห้องประชุม") !== false) {
    echo "Success! Found 'ห้องประชุม' after conversion.\n";
} else {
    echo "Failed to find 'ห้องประชุม'. Trying Windows-874...\n";
    // Maybe it was Windows-874?
}

file_put_contents('restored_fixed.sql', $fixed);
?>
