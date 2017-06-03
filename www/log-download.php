<?php

require_once 'logger.php';

// Credits:
// https://stackoverflow.com/questions/8485886/force-file-download-with-php-using-header

$data = file_get_contents(Logger::location());
$size = strlen($data);

// Removed some unneeded headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="skoj-log.txt"'); 
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $size);
echo $data;

?>

