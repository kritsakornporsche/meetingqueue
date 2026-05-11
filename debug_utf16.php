<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
// Search for "INSERT INTO `rooms`" in UTF-16LE
$search = mb_convert_encoding("INSERT INTO `rooms` VALUES", 'UTF-16LE', 'UTF-8');
$pos = strpos($data, $search);
if ($pos !== false) {
    echo "Found INSERT at $pos\n";
    $snippet = substr($data, $pos, 400);
    echo "Raw UTF-16LE Hex: " . bin2hex($snippet) . "\n";
    
    // Convert to UTF-8 and see
    $utf8 = mb_convert_encoding($snippet, 'UTF-8', 'UTF-16LE');
    echo "Converted UTF-8: " . $utf8 . "\n";
} else {
    echo "Not found\n";
}
?>
