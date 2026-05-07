<?php
// Redirigeix al login si no està autenticat
require_once(__DIR__ . '/../controller/auth.php');

$usuariLoguejat = validarToken();
if (!$usuariLoguejat) {
    header("Location: /view/login.php");
    exit();
}
?>