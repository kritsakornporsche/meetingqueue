<?php
$blob_id = 'fd9def484b52c5d39ef087107a52368cced66efc';
$handle = popen("git cat-file blob $blob_id", "rb");
$data = stream_get_contents($handle);
pclose($handle);

file_put_contents('raw_blob.sql', $data);
echo "Written " . strlen($data) . " bytes to raw_blob.sql\n";
?>
