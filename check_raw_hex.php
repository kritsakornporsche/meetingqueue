<?php
$data = file_get_contents('restored_final_raw.sql');
$pos = strpos($data, "INSERT INTO `rooms` VALUES");
if ($pos !== false) {
    echo "Found INSERT at $pos\n";
    $snippet = substr($data, $pos, 200);
    echo "Hex snippet: " . bin2hex($snippet) . "\n";
} else {
    echo "INSERT not found\n";
}
?>
