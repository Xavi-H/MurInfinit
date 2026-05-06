<?php
// Valida el token JWT emmagatzemat en la cookie
function validarToken() {
    // Comprova si existeix la cookie
    if (!isset($_COOKIE['token'])) {
        return false;
    }

    $token = $_COOKIE['token'];
    // Divideix el token en les seves parts
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    // Assigna header, payload i signatura
    list($header64, $payload64, $signature64) = $parts;
    $secret = "clauSuperSecreta123";

    // Genera la signatura
    $expectedSignature = base64_encode(hash_hmac("sha256", "$header64.$payload64", $secret, true));

    // Comprova que la signatura coincideix
    if ($signature64 !== $expectedSignature) return false;

    // Decodifica el payload
    $payload = json_decode(base64_decode($payload64), true);
    if (!$payload || time() > $payload['exp']) {
        // Si el Token ha sigut expirat, s'esborra la cookie
        setcookie('token', '', time() - 3600, '/');
        return false;
    }
    return $payload;
}
?>