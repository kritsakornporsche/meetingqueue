<?php
$data = file_get_contents('raw_blob.sql');
if (substr($data, 0, 2) === "\xff\xfe") $data = substr($data, 2);

$fixed_bytes = "";
$len = strlen($data);
for ($i = 0; $i < $len; $i += 2) {
    // Read 2 bytes as Little Endian word
    $cp = ord($data[$i]) | (ord($data[$i+1]) << 8);
    
    if ($cp >= 0x0E00 && $cp <= 0x0E5B) {
        // Map Thai Unicode back to Windows-874 bytes
        $fixed_bytes .= chr($cp - 0x0D60);
    } elseif ($cp < 256) {
        // Map Latin1/control chars back to their byte values
        $fixed_bytes .= chr($cp);
    } else {
        // For other characters, this shouldn't happen if it was a simple byte-to-char conversion
        // But we'll try to handle it.
        $fixed_bytes .= "?"; 
    }
}

if (strpos($fixed_bytes, "ห้องประชุม") !== false) {
    echo "Success! Found 'ห้องประชุม' after refined reversal.\n";
} else {
    echo "Failed. Found snippet: " . substr($fixed_bytes, strpos($fixed_bytes, "VALUES") ?: 0, 50) . "\n";
}

file_put_contents('restored_clean_v3.sql', $fixed_bytes);
?>
