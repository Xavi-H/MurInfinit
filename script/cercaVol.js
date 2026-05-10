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
            div.innerHTML = `
                <a href="/view/imatges.php?id=${img.id}">
                    <img src="${img.img_url}" alt="${img.img_titol}" loading="lazy">
                </a>
            `;
            const divSenseCercar = document.getElementById('imgAbansDeFiltrar');
            mur.removeChild(divSenseCercar); // Esborra les imatges antigues
            mur.appendChild(div); // Afageix les noves
        });
        offset += limit;
        loading = false;
        document.getElementById("loading").style.display = "none";
    } else {
        window.location.href = '/index.php';
    }
}