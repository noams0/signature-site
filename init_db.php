<?php
$db = new SQLite3('db.sqlite');

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    status TEXT DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
)");

$check = $db->querySingle("SELECT COUNT(*) FROM admins");
if ($check == 0) {
    $password = password_hash('LeCanalIlEstPourri1360', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO admins (username, password) VALUES ('admin', '$password')");
}

echo "Base de données initialisée.";
?>
