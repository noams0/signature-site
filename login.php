<?php
session_start();
$db = new SQLite3('db.sqlite');

$error = '';
$max_attempts = 5;
$lockout_time = 300;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

if ($_SESSION['login_attempts'] >= $max_attempts && time() - $_SESSION['last_attempt_time'] < $lockout_time) {
    $error = "Trop de tentatives. Réessayez dans " . ($lockout_time - (time() - $_SESSION['last_attempt_time'])) . " secondes.";
} else {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // Réinitialiser les tentatives après une connexion réussie
            $_SESSION['login_attempts'] = 0;
            $_SESSION['admin'] = $username;
            header('Location: admin.php');
            exit();
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = "Identifiants incorrects. Tentative " . $_SESSION['login_attempts'] . "/$max_attempts.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
</head>
<body>
<h2>Connexion Admin</h2>
<form method="POST">
    <label>Nom d'utilisateur :</label>
    <input type="text" name="username" required>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <button type="submit" <?= ($_SESSION['login_attempts'] >= $max_attempts && time() - $_SESSION['last_attempt_time'] < $lockout_time) ? 'disabled' : ''; ?>>
        Se connecter
    </button>
</form>
<p style="color:red;"><?= $error ?></p>
</body>
</html>
