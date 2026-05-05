<?php
$db = new SQLite3("../dataBase/imatges.db");

$limit = $_GET['limit'] ?? 30;
$offset = $_GET['offset'] ?? 0;

$sql = "SELECT * FROM imatges LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
$stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
$stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
$result = $stmt->execute();

$imatges = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $imatges[] = $row;
}

header('Content-Type: application/json');
echo json_encode($imatges);
?>
