<?php
// index.php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>VulnApp - Home</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>
  <h1>VulnApp - Application pédagogique</h1>

  <?php if (is_logged()): ?>
    <p>Connecté en tant que <strong><?php echo htmlentities($_SESSION['username']); ?></strong></p>
    <p><a href="dashboard.php">Aller au dashboard</a> | <a href="login.php?action=logout">Déconnexion</a></p>
  <?php else: ?>
    <p>
      <a href="login.php">Se connecter</a> | 
      <a href="register.php">S'inscrire</a> | 
      <a href="reset_password.php">Réinitialiser le mot de passe</a>
    </p>
  <?php endif; ?>

  <hr>

  <h2>Bienvenue sur votre plateforme de suivi des vulnérabilités</h2>
  <p>
    Connectez-vous pour accéder au suivi.
  </p>

  <hr>
  <p>Liens utiles:</p>
  <ul>
    <li><a href="README.md">README</a></li>
    <li><a href="templates/twig_demo.php">Démonstration Template Twig</a>(pour la future version du site)</li>
  </ul>
</body>
</html>
