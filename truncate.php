<?php
$lines = file('views/reports.php');
file_put_contents('views/reports.php', implode('', array_slice($lines, 0, 466)));
