<?php
$data = shell_exec('git show backup_before_reset:local_backup.sql');
echo "Hex: " . bin2hex(substr($data, 0, 20)) . "\n";
echo "Length: " . strlen($data) . "\n";
?>
