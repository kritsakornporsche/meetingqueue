<?php
$data = file_get_contents('raw_blob.sql');
if (substr($data, 0, 2) === "\xff\xfe") $data = substr($data, 2);

// Search for "INSERT INTO `rooms`" in UTF-16LE
$search = mb_convert_encoding("INSERT INTO `rooms` VALUES", 'UTF-16LE', 'UTF-8');
$pos = strpos($data, $search);
if ($pos !== false) {
    $snippet = substr($data, $pos + strlen($search), 200);
    echo "Raw UTF-16LE Hex for rooms data: " . bin2hex($snippet) . "\n";
}
?>
