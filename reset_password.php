<?php
// reset_password.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$info = '';

if (isset($_GET['request_token']) && !empty($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $token = base64_encode($user_id);
    $info = "Token de réinit : $token (basé sur user id).";
}

if (isset($_GET['use_token']) && !empty($_POST['token']) && !empty($_POST['new_password'])) {
    $token = $_POST['token'];
    $user_id = intval(base64_decode($token));
    if ($user_id > 0) {
        $newpwd = $_POST['new_password'];
        // Vulnérable : mot de passe en clair, injection SQL
        $q = "UPDATE users SET password = '$newpwd' WHERE id = $user_id";
        try {
            if ($conn->exec($q) !== false) {
                $info = "Mot de passe modifié pour user #$user_id";
            } else {
                $info = "Erreur : impossible de mettre à jour.";
            }
        } catch (PDOException $e) {
            $info = "Erreur DB : " . $e->getMessage();
        }
    } else {
        $info = "Token invalide";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset password</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<h1>Réinitialisation de mot de passe (vulnérable volontairement)</h1>
<?php if ($info) echo "<p>$info</p>"; ?>

<h2>Demander un token (affiché)</h2>
<form method="post" action="?request_token=1">
  <label>User ID: <input name="user_id"></label>
  <button>Demander token</button>
</form>

<h2>Utiliser un token</h2>
<form method="post" action="?use_token=1">
  <label>Token: <input name="token"></label><br>
  <label>Nouveau mot de passe: <input name="new_password" type="password"></label><br>
  <button>Changer mot de passe</button>
</form>

<p><a href="index.php">Retour</a></p>
</body>
</html>
