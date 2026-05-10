<?php
include_once __DIR__ . '/controller/auth.php';
$usuari = validarToken();

// Extreure el nom del token per donar likes a les imatges
$username = '';
if ($usuari) {    
    $username = $usuari['nom'];
}

include_once __DIR__ . '/includes/head.html';
include_once __DIR__ . '/includes/header.php';
?>

<h1 class="titol">MUR D'IMATGES</h1>

<form action="api/cercaVol.php" method="POST" id="cerca-form">
    <textarea name="cerca" id="cerca" placeholder="🔍 Comença a buscar..."></textarea>
    <ul id="suggestions"></ul>
    <label for="filtre">Filtra per:</label>
    <select id="filtre" name="filtre">
        <option value="senseFiltre">Sense filtre</option>
        <option value="naturaleza">Naturalesa</option>
        <option value="tecnologia">Tecnología</option>
        <option value="comida">Menjar</option>
        <option value="arquitectura">Arquitectura</option>
        <option value="animales">Animals</option>
        <option value="viajes">Viatges</option>
    </select>
    <input type="submit" value="Enviar">
</form>

<main>
    <div id="mur" class="mur"></div>

    <div id="loading" class="loading">
        Carregant...
    </div>
</main>

<script>
    // Dades PHP passades a JS de forma segura
    const usuariLogat = <?php echo $usuari ? 'true' : 'false'; ?>;
    const username = <?php echo $usuari ? json_encode($username) : 'null'; ?>;

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
            li.innerHTML = `<a href="/view/imatges.php?id=${item['id']}">${item['img_titol']}</a>`;
            ul.appendChild(li);
        });
    }

let offset = 0;
const limit = 20;
let loading = false;

window.addEventListener("scroll", () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
        carregarImatges();
    }
});

carregarImatges();

async function carregarImatges() {
    if (loading) return;
    loading = true;
    document.getElementById("loading").style.display = "block";
    const res = await fetch(`api/imatgesApi.php?offset=${offset}&limit=${limit}`);
    const imatges = await res.json();
    const mur = document.getElementById("mur");

    imatges.forEach(img => {
        const div = document.createElement("div");
        div.id = 'imgAbansDeFiltrar';
        div.className = "imatge";
        div.dataset.id = img.id;

        let likeBtn = '';
        if (!usuariLogat) {
            likeBtn = `<button class="btn-like-guest" onclick="window.location.href='/view/login.php'">Like</button>`;
        } else {
            likeBtn = `<button class="btn-like" data-id="${img.id}">Like | <span class="num-likes">${img.num_likes}</span></button>`;
        }

        div.innerHTML = `
            <a href="/view/imatges.php?id=${img.id}">
                <img src="${img.img_url}" alt="${img.img_titol}" loading="lazy">
            </a>
            ${likeBtn}
        `;
        mur.appendChild(div);
    });

    // Afegir event listeners als botons de like acabats d'inserir
    if (usuariLogat) {
        const botons = mur.querySelectorAll('.btn-like:not([data-listener])');
        botons.forEach(btn => {
            btn.setAttribute('data-listener', 'true');
            btn.addEventListener('click', () => donarLike(btn));
        });
    }

    offset += limit;
    loading = false;
    document.getElementById("loading").style.display = "none";
}

addEventListener("submit", (event) => {
    event.preventDefault();
    cercaVol();
});

async function cercaVol(){
    const cerca = document.getElementById('filtre').value;
    if (cerca != 'senseFiltre') {
        const res = await fetch('api/cercaVol.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cerca })
        });
        const imatges = await res.json();
        const mur = document.getElementById("mur");
        imatges.forEach(img => {
            const div = document.createElement("div");
            div.id = 'imgAbansDeFiltrar'; // Per quan es filtri, s'esborrin aquestes
            div.className = "imatge";
            div.dataset.id = img.id;
            
            let likeBtn = '';
            if (!usuariLogat) {
                likeBtn = `<button class="btn-like-guest" onclick="window.location.href='/view/login.php'">Like</button>`;
            } else {
                likeBtn = `<button class="btn-like" data-id="${img.id}">Like | <span class="num-likes">${img.num_likes}</span></button>`;
            }

        div.innerHTML = `
            <a href="/view/imatges.php?id=${img.id}">
                <img src="${img.img_url}" alt="${img.img_titol}" loading="lazy">
            </a>
            ${likeBtn}
        `;

        const divSenseCercar = document.getElementById('imgAbansDeFiltrar');
        mur.removeChild(divSenseCercar); // Esborra les imatges antigues
        mur.appendChild(div); // Afageix les noves
        });

        // Afegir event listeners als botons de like acabats d'inserir
        if (usuariLogat) {
            const botons = mur.querySelectorAll('.btn-like:not([data-listener])');
            botons.forEach(btn => {
                btn.setAttribute('data-listener', 'true');
                btn.addEventListener('click', () => donarLike(btn));
            });
        }

        offset += limit;
        loading = false;
        document.getElementById("loading").style.display = "none";
    } else {
        window.location.href = '/index.php';
    }
}

async function donarLike(btn) {
    if (btn.disabled) return;
    btn.disabled = true;

    const imatgeId = btn.dataset.id;

    try {
        const res = await fetch('api/imatgesApi.php', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: parseInt(imatgeId), username: username })
        });
        const data = await res.json();

        if (data.num_likes !== undefined) {
            btn.querySelector('.num-likes').textContent = data.num_likes;
            btn.innerHTML = 'Like donat!;';
            btn.classList.add('liked');
        } else if (data.error === 'ja_like') {
            btn.innerHTML = 'Ja has donat like';
            btn.classList.add('liked');
        } else {
            alert("Error: No s'ha pogut fer like");
            btn.disabled = false;
        }
    } catch (err) {
        console.error(err);
        alert('Error de connexió');
        btn.disabled = false;
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.html'; ?>