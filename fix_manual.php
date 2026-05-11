<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
if (substr($data, 0, 2) === "\xff\xfe") $data = substr($data, 2);
$string = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');

$fixed_bytes = "";
$len = mb_strlen($string);
for ($i = 0; $i < $len; $i++) {
    $char = mb_substr($string, $i, 1);
    $cp = mb_ord($char);
    if ($cp >= 0x0E01 && $cp <= 0x0E5B) {
        // Thai characters in UTF-8 were likely Windows-874 bytes interpreted as Unicode
        // Map U+0E01..U+0E5B back to 0xA1..0xFB
        $fixed_bytes .= chr($cp - 0x0D60);
    } else {
        // For non-Thai chars, just take the byte if it's ASCII
        if ($cp < 128) {
            $fixed_bytes .= chr($cp);
        } else {
            // This part is tricky. If there are other mojibake chars like '¸' (U+00B8)
            // they might be part of the original UTF-8 bytes too.
            // Let's assume they are mapped directly to their byte values.
            $fixed_bytes .= chr($cp & 0xFF);
        }
    }
}

if (strpos($fixed_bytes, "ห้องประชุม") !== false) {
    echo "Success! Found 'ห้องประชุม' after manual Thai-to-Byte reversal.\n";
} else {
    echo "Failed. Still mojibake. Hex of snippet in fixed_bytes: " . bin2hex(substr($fixed_bytes, strpos($fixed_bytes, "VALUES") ?: 0, 100)) . "\n";
}

file_put_contents('restored_fixed_final.sql', $fixed_bytes);
?>
