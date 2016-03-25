<?php
// - DB - //

// - LOCAL - //

DEFINE('API_DB_SERVER', 'localhost');
DEFINE('API_DB_NAME', 'selfius_db');
DEFINE('API_DB_USER', 'selfius_wangping');
DEFINE('API_DB_PASS', 'chengge111');

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

// - SERVER - //
/*
DEFINE('API_DB_SERVER', 'localhost');
DEFINE('API_DB_NAME', 'wli');
DEFINE('API_DB_USER', 'wli');
DEFINE('API_DB_PASS', '');
*/

// -- EMAIL -- //
define("MAIL_USER", "support");
define("MAIL_PASS", "");
define("MAIL_SERVER", "localhost");
define("MAIL_PORT", "26");
define("EMAIL", "wli@planet1107-solutions.net");
define("EMAIL_FROM", "WLI");
	
define("SMTP_AUTH", false);
define("POP3_AUTH", false);

// -- POP3 -- //
/* 
define("POP3_USER", "support@wli.com");
define("POP3_PASS", "");
define("POP3_SERVER", "mail.wli.com");
define("POP3_PORT", "110");
*/

// -- APP SETTINGS -- //
DEFINE('BASE_PATH', '/api');
DEFINE('BASE_URL', 'http://selfius.com/api');
DEFINE('BASE_WEB_URL', 'http://selfius.com');
DEFINE('WEB_API_KEY', '1234567890');
DEFINE('UPLOAD_LOCATION_AVATAR', '/Uploads/Users/Avatars/Original/');
DEFINE('LOCATION_AVATAR_NORMAL', 'Uploads/Users/Avatars/Normal/');
DEFINE('LOCATION_AVATAR_NORMAL_2X', 'Uploads/Users/Avatars/Normal2x/');
DEFINE('LOCATION_AVATAR_SMALL', 'Uploads/Users/Avatars/Small/');
DEFINE('LOCATION_AVATAR_SMALL_2X', 'Uploads/Users/Avatars/Small2x/');
DEFINE('LOG_LOCATION', 'Logs/');

DEFINE('UPLOAD_LOCATION_PICS', '/Uploads/Pictures/');
error_reporting(0);
?>