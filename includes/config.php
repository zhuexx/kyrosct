<?php
// Use Railway's environment variables
define('DB_HOST', getenv('MYSQLHOST'));
define('DB_USER', getenv('MYSQLUSER'));
define('DB_PASS', getenv('MYSQLPASSWORD')); 
define('DB_NAME', getenv('MYSQLDATABASE'));
define('ADMIN_TOKEN', getenv('ADMIN_TOKEN')); // Set in Railway dashboard
?>
