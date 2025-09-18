<?php
// register.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';

    // Version volontairement vulnérable à l'injection SQL
    $q = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    try {
        if ($conn->exec($q) !== false) {
            $message = "Compte créé pour $username. Vous pouvez vous connecter.";
        } else {
            $message = "Erreur: Impossible de créer l'utilisateur.";
        }
    } catch (PDOException $e) {
        $message = "Erreur: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<h1>Créer un compte</h1>
<?php if ($message): ?><p><?php echo $message; ?></p><?php endif; ?>
<form method="post">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input name="password" type="password"></label><br>
  <label>Email: <input name="email"></label><br>
  <button>Register</button>
</form>
<p><a href="index.php">Retour</a></p>
</body>
</html>
