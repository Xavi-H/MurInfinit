let offset = 0;
const limit = 20;
let loading = false;

window.addEventListener("scroll", () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
        carregarImatges();
    }
});

carregarImatges(); // carregar les primeres

async function carregarImatges() {
    if (loading) return;
    loading = true;
    document.getElementById("loading").style.display = "block";
    const res = await fetch(`api/imatgesApi.php?offset=${offset}&limit=${limit}`);
    const imatges = await res.json();    
    const mur = document.getElementById("mur");

    imatges.forEach(img => {
        const div = document.createElement("div");
        div.className = "imatge";
        div.innerHTML = `
        <a href="/view/imatges.php?id=${img.id}"><img src="${img.img_url}" alt="${img.img_titol}" loading="lazy"></a>
        `;
        mur.appendChild(div);
    });

    offset += limit;
    loading = false;
    document.getElementById("loading").style.display = "none";
}