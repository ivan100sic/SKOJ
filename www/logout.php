<?php

require_once 'global.php';
require_once 'logger.php';

Logger::notice('User logging out');
set_session_id(0);
recover(0);

?>