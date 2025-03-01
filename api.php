<?php
header("Content-Type: application/json");
$db = new SQLite3('db.sqlite');

$action = $_GET['action'] ?? '';

if ($action === 'request_signature') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Données invalides"]);
        exit;
    }

    try {
        $stmt = $db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->execute();
        echo json_encode(["success" => "Demande envoyée"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Cet email a déjà fait une demande."]);
    }
}

if ($action === 'get_signatures') {
    $result = $db->query("SELECT name FROM users WHERE status = 'approved'");
    $signatures = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $signatures[] = $row;
    }

    echo json_encode($signatures);
}

// Gestion admin (accepter ou refuser une signature)
if ($action === 'update_status' && $_POST['admin_key'] === 'SECRET_KEY') {
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] === 'approved' ? 'approved' : 'rejected';

    $stmt = $db->prepare("UPDATE users SET status = :status WHERE id = :id");
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    echo json_encode(["success" => "Mise à jour effectuée"]);
}

?>
