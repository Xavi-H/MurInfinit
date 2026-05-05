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
        div.className = "imatge";
        div.dataset.id = img.id;
        div.innerHTML = `
            <a href="/view/imatges.php?id=${img.id}">
                <img src="${img.img_url}" alt="${img.img_titol}" loading="lazy">
            </a>
            <div class="imatge-info">
                <p class="imatge-titol">${img.img_titol}</p>
                <div class="valoracio-actual">
                    <span class="valoracio-num">${img.valoracio} ⭐</span>
                    <span class="num-vots">(${img.num_votacio})</span>
                </div>
                <select class="select-estrelles" name="estrelles" data-id="${img.id}">
                    <option value="" disabled selected>-- Valorar --</option>
                    <option value="1">1 ⭐</option>
                    <option value="2">2 ⭐⭐</option>
                    <option value="3">3 ⭐⭐⭐</option>
                    <option value="4">4 ⭐⭐⭐⭐</option>
                    <option value="5">5 ⭐⭐⭐⭐⭐</option>
                </select>
            </div>
        `;
        mur.appendChild(div);
        iniciarSelect(div, img.id);
    });

    offset += limit;
    loading = false;
    document.getElementById("loading").style.display = "none";
}

function iniciarSelect(card, id) {
    const select = card.querySelector('.select-estrelles');

    select.addEventListener('change', async () => {
        const estrelles = parseInt(select.value);
        select.disabled = true;

        try {
            const res = await fetch('/api/imatgesApi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({id, estrelles})
            });
            const data = await res.json();
            if (data.imatge) {
                card.querySelector('.valoracio-num').textContent = data.imatge.valoracio + ' ⭐';
                card.querySelector('.num-vots').textContent = `(${data.imatge.num_votacio})`;
            }
        } catch {
            select.disabled = false;
        }
    });
}
