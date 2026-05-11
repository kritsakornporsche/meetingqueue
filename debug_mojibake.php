<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
if (substr($data, 0, 2) === "\xff\xfe") $data = substr($data, 2);
$string = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');

// Find a snippet with Thai characters
$snippet = "";
foreach(explode("\n", $string) as $line) {
    if (strpos($line, "INSERT INTO `rooms` VALUES") !== false) {
        $snippet = $line;
        break;
    }
}
echo "Snippet: " . substr($snippet, 0, 100) . "\n";
echo "Hex: " . bin2hex(substr($snippet, 0, 100)) . "\n";
?>
