<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retourne true si l'utilisateur est loggé
function is_logged() {
    return !empty($_SESSION['user_id']);
}

// Récupère l'ID utilisateur loggé
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// Login basique
function do_login($user_id, $username) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
}

// Logout
function do_logout() {
    session_destroy();
}
