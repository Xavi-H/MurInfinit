<?php include_once __DIR__ . '/includes/head.php'; ?>

<h1 class="titol">MUR D'IMATGES</h1>

<script src="script/cercaVol.js"></script>

<form action="api/cercaVol.php" method="POST" id="cerca-form">
    <textarea name="cerca" id="cerca" placeholder="🔍 Comença a buscar..."></textarea>
    <button type="submit">Enviar</button>
</form>
<main>
    <div id="mur" class="mur"></div>

    <div id="loading" class="loading">
        Carregant...
    </div>
</main>

<script src="script/mur.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>