<?php

require_once 'global.php';
require_once 'sql.php';
require_once 'user.php';
require_once 'dom.php';

$user = User::construct_safe(get_session_id());
if ($user === NULL || !$user->has_permission("ADMIN_PANEL")) {
	recover(0);
}

$dump = SQL::dump();
$size = strlen($dump);

// Credits:
// https://stackoverflow.com/questions/8485886/force-file-download-with-php-using-header

// Removed some unneeded headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="skoj-dump.sql"'); 
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $size);
echo "$dump";

?>