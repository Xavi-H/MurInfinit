<?php
include("../includes/db_connect.php");

// Defineix que la resposta serà JSON
header("Content-Type: application/json");

// Llegeix i decodifica el cos de la petició
$input = json_decode(file_get_contents("php://input"), true);

// Obté les credencials o assigna buit si no existeixen
$nom = $input["nom"]?? "";
$contrasenya = $input["contrassenya"]?? "";

// Comprova que s'han enviat dades
if($nom && $contrasenya) {
    // Consulta l'usuari per nom
    $stmt = $db->prepare("SELECT * FROM usuaris WHERE nom = :nom");
    $stmt->bindValue(":nom", $nom, SQLITE3_TEXT);
    $result = $stmt->execute();
    $usuari = $result->fetchArray(SQLITE3_ASSOC);

    // Verifica usuari i contrasenya
    if ($usuari && $usuari["contrassenya"] === md5($contrasenya)) {
        // Es crea el header del token JWT
        $header = base64_encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));

        // Es crea el payload amb dades i expiració
        $payload = base64_encode(json_encode([
            "id" => $usuari["id"],
            "nom" => $usuari["nom"],
            "exp" => time() + 3600 // caduca en 1 hora
        ]));
        // Clau secreta per signar el token
        $clau_secreta = "clauSuperSecreta123";
        // Genera la signatura
        $signatura = base64_encode(hash_hmac("sha256", "$header.$payload", $clau_secreta, true));

        // Construeix el token final
        $token = "$header.$payload.$signatura";

        // Guarda el token en una cookie
        setcookie("token", $token, time() + 3600, "/");
        echo json_encode(["token" => $token]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Usuari o contrasenya incorrectes"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Falten dades"]);
}