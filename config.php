<?php
// config.php
// Configuration globale (intentionnellement simple / sensible exposée)

define('DB_NAME', 'vulnapp.sqlite');
define('DB_USER', 'root');
define('DB_PASS', 'root'); 

define('BASE_URL', '/vulnapp'); // adapter selon déploiement

// Mode debug activé (ne pas faire en prod)
ini_set('display_errors', 1);
error_reporting(E_ALL);
