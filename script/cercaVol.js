addEventListener("submit", (event) => {
    event.preventDefault();
    cercaVol();
});

async function cercaVol(){
    const cerca = document.getElementById('cerca').value;
    const res = await fetch('api/cercaVol.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cerca })
    });
    const imatges = await res.json();
    const mur = document.getElementById("mur");
    imatges.forEach(img => {
        const div = document.createElement("div");
        div.className = "imatge";
        div.dataset.id = img.id;
        div.innerHTML = `
            <a href="/view/imatges.php?id=${img.id}">
                <img src="${img.img_url}" alt="${img.img_titol}" loading="lazy">
            </a>
        `;
        mur.appendChild(div);
    });
    offset += limit;
    loading = false;
    document.getElementById("loading").style.display = "none";
}