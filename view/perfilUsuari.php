<?php
require_once __DIR__ . '/../includes/check_auth.php'; // Comprova si està loguejat
include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php';
?>

<div class="historial-wrapper">
    <h1>El teu historial de Likes</h1>
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?>!</p>
    <p>Aquí pots veure totes les fotos que t'han agradat.</p>
</div>

<script>
    const usuariLogat = <?php echo $usuari ? 'true' : 'false'; ?>; // Si té usuari true, sino false
    const username = <?php echo $usuari ? json_encode($usuari['nom']) : 'null'; ?>;

    if (usuariLogat) {
        fetch(`/api/imatgesApi.php?username=${encodeURIComponent(username)}`)
            .then(res => res.json())
            .then(data => {
                const wrapper = document.querySelector('.historial-wrapper');
                wrapper.innerHTML += '<div class="favorits-grid"></div>';
                const grid = wrapper.querySelector('.favorits-grid');

                if (data.length === 0) {
                    grid.innerHTML = '<p>No tens cap foto marcada com a favorita.</p>';
                    return;
                }

                data.forEach(img => {
                    const div = document.createElement('div');
                    div.classList.add('imatge-card');
                    div.innerHTML = `
                        <a href="/view/imatges.php?id=${img.id}">
                            <img src="${img.img_url}" alt="${img.img_titol}" loading="lazy">
                            <h3>${img.img_titol}</h3>
                        </a>
                    `;
                    grid.appendChild(div);
                });
            })
            .catch(err => {
                console.error('Error carregant favorits:', err);
                const wrapper = document.querySelector('.historial-wrapper');
                wrapper.innerHTML += '<p>Hi ha hagut un error carregant els teus favorits. Torna-ho a intentar més tard.</p>';
            });
    }
</script>

<?php include_once __DIR__ . '/../includes/footer.html'; ?>