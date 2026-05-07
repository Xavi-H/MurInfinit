<?php
include("../includes/db_connect.php");

// Defineix que la resposta serà JSON
header("Content-Type: application/json");

// Llegeix i decodifica el cos de la petició
$input = json_decode(file_get_contents("php://input"), true);

// Obté les credencials o assigna buit si no existeixen
$nom = $input["nom"]?? "";
$contrasenya = $input["contrasenya"]?? "";

// Comprova que s'han enviat dades
if($nom && $contrasenya) {
    // Consulta l'usuari per nom
    $stmt = $db->prepare("INSERT IF NOT EXISTS INTO usuaris (usuari, contrasenya) VALUES (:nom, :contrasenya)");
    $stmt->bindValue(":nom", $nom, SQLITE3_TEXT);
    $stmt->bindValue(":contrasenya", md5($contrasenya), SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(["missatge" => "Usuari registrat correctament"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error en registrar l'usuari"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Falten dades"]);
}