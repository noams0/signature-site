<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$db = new SQLite3('db.sqlite');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer le nom
    $data = json_decode(file_get_contents("php://input"), true);
    $name = trim($data['name'] ?? '');

    if ($name === '' || strlen($name) > 100) {
        echo json_encode(["error" => "Nom invalide"]);
        exit;
    }

    // Vérification anti-doublon (optionnelle)
    $stmt = $db->prepare("SELECT COUNT(*) FROM signatures WHERE name = :name");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray()[0];

    if ($result > 0) {
        echo json_encode(["error" => "Déjà signé"]);
        exit;
    }

    // Insérer dans la base
    $stmt = $db->prepare("INSERT INTO signatures (name) VALUES (:name)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupérer les signatures
    $result = $db->query("SELECT name, created_at FROM signatures ORDER BY created_at DESC");
    $signatures = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $signatures[] = $row;
    }

    echo json_encode($signatures);
    exit;
}

echo json_encode(["error" => "Méthode non supportée"]);
?>
