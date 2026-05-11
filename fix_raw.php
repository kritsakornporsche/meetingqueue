<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');

// Remove UTF-16LE BOM
if (substr($data, 0, 2) === "\xff\xfe") {
    $data = substr($data, 2);
}

// In PowerShell 5.1, ">" encodes each byte of output as a UTF-16LE character.
// So byte 0xE0 becomes 0xE0 0x00 in the file.
// To restore, we just need every even byte.
$fixed_bytes = "";
for ($i = 0; $i < strlen($data); $i += 2) {
    $fixed_bytes .= $data[$i];
}

if (strpos($fixed_bytes, "ห้องประชุม") !== false) {
    echo "Success! Found 'ห้องประชุม' after raw low-byte extraction.\n";
} else {
    echo "Failed. Let's check hex of snippet.\n";
    echo "Hex: " . bin2hex(substr($fixed_bytes, 0, 100)) . "\n";
}

file_put_contents('restored_final_raw.sql', $fixed_bytes);
?>
