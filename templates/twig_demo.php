<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$input = $_GET['input'] ?? 'Bienvenue sur votre dashboard !';

// Interprétation de l'input comme un mini template Twig 
try {
    $rendered_input = $twig->createTemplate($input)->render([]);
} catch (Exception $e) {
    $rendered_input = "Erreur dans le template : " . htmlspecialchars($e->getMessage());
}

echo $twig->render('demo.twig', ['user_input' => $rendered_input]);
