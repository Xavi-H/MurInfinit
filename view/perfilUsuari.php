<?php
require_once __DIR__ . '/../includes/check_auth.php'; // Comprova si està loguejat
include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php';
?>

<div class="historial-wrapper">
    <h1>El teu historial de Likes</h1>
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?>!</p>
    <p>Aquí pots veure totes les fotos que t'han agradat.</p>

    <div class="loader">Carregant els teus favorits...</div>
</div>

<?php include_once __DIR__ . '/../includes/footer.html'; ?>