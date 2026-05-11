<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
echo "Raw Git Hex (first 200 bytes):\n";
echo bin2hex(substr($data, 0, 200)) . "\n";
?>
