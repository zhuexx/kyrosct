<?php
// Railway automatically provides these via environment variables
define('DB_HOST', getenv('MYSQLHOST') ?: 'localhost');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'tiny_keys');

// Set in Railway Dashboard → Variables
define('ADMIN_TOKEN', getenv('ADMIN_TOKEN') ?: 'default-secret');
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: 'admin123');
?>