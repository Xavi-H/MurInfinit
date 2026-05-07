<?php
require_once __DIR__ . '/../includes/check_auth.php'; // Comprova si està loguejat
include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h3>PERFIL D'USUARI</h3>
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?>!</p>
    <p>Aquí pots veure les imatges a les quals li has donat <strong>M'agrada</strong>.</p>
</div>
