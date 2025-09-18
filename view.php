<?php
// view.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';


$page = $_GET['page'] ?? 'default';

$file = __DIR__ . '/templates/' . $page;
if (file_exists($file)) {
    include $file;
} else {
    echo "<p>Page introuvable: " . htmlentities($page) . "</p>";
}
