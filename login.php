<?php
// login.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    do_logout();
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $q = "SELECT id, username FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";

    try {
        $res = $conn->query($q);
        $row = $res ? $res->fetch(PDO::FETCH_ASSOC) : null;

        if ($row) {
            do_login($row['id'], $row['username']);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Identifiants incorrects";
        }
    } catch (PDOException $e) {
        $error = "Erreur DB : " . $e->getMessage();
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<h1>Connexion</h1>
<?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
<form method="post">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input name="password" type="password"></label><br>
  <button>Se connecter</button>
</form>
<p><a href="reset_password.php">Mot de passe oublié ?</a></p>
</body>
</html>
