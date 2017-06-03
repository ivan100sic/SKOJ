<?php

require_once 'logger.php';
require_once 'global.php';
require_once 'user.php';

// Credits:
// https://stackoverflow.com/questions/8485886/force-file-download-with-php-using-header

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	Logger::notice('Attempted unauthorized access to log-download.php');
	recover(0);
}

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

