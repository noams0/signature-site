<?php

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}


$db = new SQLite3('db.sqlite');

$users = $db->query("SELECT * FROM users WHERE status = 'pending'");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Modération</title>
</head>
<body>
<h1>Modération des signatures</h1>
<ul>
    <?php while ($user = $users->fetchArray(SQLITE3_ASSOC)) : ?>
        <li>
            <?= htmlspecialchars($user['name']) ?> - <?= htmlspecialchars($user['email']) ?>
            <form action="api.php?action=update_status" method="post">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="admin_key" value="SECRET_KEY">
                <button name="status" value="approved">✔️ Valider</button>
                <button name="status" value="rejected">❌ Rejeter</button>
            </form>
        </li>
    <?php endwhile; ?>
</ul>
<a href="logout.php">Se déconnecter</a>
</body>
</html>
