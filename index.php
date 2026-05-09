<?php
include_once __DIR__ . '/includes/head.html';
include_once __DIR__ . '/includes/header.php';
?>

<h1 class="titol">MUR D'IMATGES</h1>

<script src="script/cercaVol.js"></script>

<form action="api/cercaVol.php" method="POST" id="cerca-form">
    <textarea name="cerca" id="cerca" placeholder="🔍 Comença a buscar..."></textarea>
    <ul id="suggestions"></ul>
    <button type="submit">Enviar</button>
</form>
<main>
    <div id="mur" class="mur"></div>

    <div id="loading" class="loading">
        Carregant...
    </div>
</main>

<script>
    const input = document.getElementById("cerca");

    input.addEventListener("input", async () => {
        const consulta = input.value;

        const res = await fetch(`/api/cercaVol.php?busqueda=${consulta}`);
        const data = await res.json();

        await mostrarSugerencies(data);
    });

    async function mostrarSugerencies(items) {
        const ul = document.getElementById("suggestions");
        ul.innerHTML = "";

        items.forEach(item => {
            const li = document.createElement("li");
            li.textContent = item;

            ul.appendChild(li);
        });
}
</script>
<script src="script/mur.js"></script>

<?php require_once __DIR__ . '/includes/footer.html'; ?>