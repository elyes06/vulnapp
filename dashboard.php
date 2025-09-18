<?php
// dashboard.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (!is_logged()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // On récupère l'id utilisateur connecté
$username = $_SESSION['username'];

$table_id = isset($_GET['table_id']) ? $_GET['table_id'] : null;

// Création d’un nouveau tableau
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_table_name']) && trim($_POST['new_table_name']) !== '') {
    $new_table_name = $_POST['new_table_name'];
    
    $sql = "INSERT INTO vulnerability_tables (name, owner_id) VALUES ('$new_table_name', $user_id)"; //LA ENTREES NON VERIFIEES
    try {
        $conn->exec($sql);
    } catch (PDOException $e) {
        // Gérer erreur si besoin
    }
    header('Location: dashboard.php');
    exit;
}

// Ajout d'une vulnérabilité existante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['add_title']) && !empty($_POST['add_description']) 
    && isset($_POST['add_cvss']) && isset($_POST['add_status']) && isset($_POST['table_id'])) {
    $t = $_POST['add_title'];
    $d = $_POST['add_description'];
    $cvss = floatval($_POST['add_cvss']);
    $status = $_POST['add_status'];
    $target_table = intval($_POST['table_id']);

    $q = "INSERT INTO vulnerabilities (table_id, title, description, cvss, status) 
          VALUES ($target_table, '$t', '$d', $cvss, '$status')";
    try {
        $conn->exec($q);
    } catch (PDOException $e) {
        // gérer erreur si besoin
    }
    header("Location: dashboard.php?table_id=$target_table");
    exit;
}

// Récupération des tableaux appartenant à l’utilisateur connecté uniquement
$tables = [];
try {
    $stmt = $conn->query("SELECT * FROM vulnerability_tables WHERE owner_id = $user_id");
    if ($stmt) {
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // gérer erreur si besoin
}

// Récupération des vulnérabilités pour le tableau sélectionné (vulnérable à l'injection SQL)
$vulns = [];
if ($table_id) {
    try {
        $stmt = $conn->query("SELECT * FROM vulnerabilities WHERE table_id = $table_id");
        if ($stmt) {
            $vulns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        // gérer erreur si besoin
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Dashboard - <?php echo htmlentities($username); ?></h1>
<p><a href="index.php">Accueil</a> | <a href="login.php?action=logout">Déconnexion</a></p>

<h2>Mes tableaux</h2>
<ul>
  <?php foreach ($tables as $t): ?>
    <li>
      <a href="?table_id=<?php echo $t['id']; ?>"><?php echo ($t['name']); ?></a>
      (owner: <?php echo $t['owner_id']; ?>)
    </li>
  <?php endforeach; ?>
</ul>

<h3>Créer un nouveau tableau</h3>
<form method="post">
  <label>Nom du tableau: <input name="new_table_name" required></label>
  <button>Créer</button>
</form>

<?php if ($table_id): ?>
  <h2>Vulnérabilités du tableau #<?php echo htmlentities($table_id); ?></h2>
  <table border="1" cellpadding="4">
    <tr><th>ID</th><th>Titre</th><th>Description</th><th>CVSS</th><th>Status</th><th>Actions</th></tr>
    <?php foreach ($vulns as $v): ?>
      <tr>
        <form method="post" action="edit_vulnerability.php">
          <td><?php echo $v['id']; ?><input type="hidden" name="id" value="<?php echo $v['id']; ?>"></td>
          <td><input type="text" name="title" value="<?php echo htmlentities($v['title']); ?>"></td>
          <td><textarea name="description"><?php echo $v['description']; ?></textarea></td>
          <td><input type="number" step="0.1" min="0" max="10" name="cvss" value="<?php echo $v['cvss']; ?>"></td>
          <td>
            <select name="status">
              <option value="open" <?php if($v['status'] === 'open') echo 'selected'; ?>>Open</option>
              <option value="in_progress" <?php if($v['status'] === 'in_progress') echo 'selected'; ?>>In Progress</option>
              <option value="closed" <?php if($v['status'] === 'closed') echo 'selected'; ?>>Closed</option>
            </select>
          </td>
          <td>
            <input type="hidden" name="table_id" value="<?php echo htmlentities($table_id); ?>">
            <button type="submit">Modifier</button>
          </td>
        </form>
      </tr>
    <?php endforeach; ?>
  </table>

  <h3>Ajouter une vulnérabilité (pas de CSRF / pas d'escape)</h3>
  <form method="post">
    <input type="hidden" name="table_id" value="<?php echo htmlentities($table_id); ?>">
    <label>Titre: <input name="add_title" required></label><br>
    <label>Description: <textarea name="add_description" required></textarea></label><br>
    <label>CVSS (ex: 5.0): <input name="add_cvss" type="number" step="0.1" min="0" max="10" required></label><br>
    <label>Status: 
      <select name="add_status" required>
        <option value="open">Open</option>
        <option value="in_progress">In Progress</option>
        <option value="closed">Closed</option>
      </select>
    </label><br>
    <button>Ajouter</button>
  </form>
<?php endif; ?>

</body>
</html>
