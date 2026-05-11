<?php
$data = file_get_contents('restored_clean_v3.sql');
$pos = strpos($data, "ห้องประชุม");
if ($pos !== false) {
    $snippet = substr($data, $pos, 100);
    echo "Found snippet: " . $snippet . "\n";
    echo "Snippet Hex: " . bin2hex($snippet) . "\n";
}
?>
