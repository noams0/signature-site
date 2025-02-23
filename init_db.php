<?php

if (php_sapi_name() !== "cli") {
    die("Ce script ne peut être exécuté qu'en ligne de commande.");
}

$db = new SQLite3('db.sqlite');

$db->exec("CREATE TABLE IF NOT EXISTS signatures (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

echo "Base de données initialisée !";
?>
