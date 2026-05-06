<?php
include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php';
?>

<h1 class="titol">Imatge</h1>

<div id="imatge-container" class="imatge-detail"></div>

<script>
  function getParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  const id = getParam('id')|| 1;
  const url = `../api/imatgesApi.php?id=${id}`;

  fetch(url)
    .then(r => r.json())
    .then(data => {
      const container = document.getElementById('imatge-container');

      if(data && !data.error) {
        const arrodonit = Math.round(data.valoracio);
        let resultat = '';
        for(let i = 1; i <= 5; i++) {
          if(i <= arrodonit) {
            resultat += '⭐';
          }else {
            resultat += '☆';
          }
        }
        const estrellesHTML = resultat;
        container.innerHTML = `
          <div class="imatge-card">
            <img src="${data.img_url}" alt="${data.img_titol}" loading="lazy">
            <div class="imatge-info">
              <h2>${data.img_titol}</h2>
              <div class="valoracio-actual">
                <span class="valoracio-numero">${estrellesHTML} (${data.valoracio})</span>
                <span class="num-votacions">(${data.num_votacio} valoracions)</span>
              </div>
              <select id="select-estrelles">
                <option value="" disabled selected>-- Valorar --</option>
                <option value="1">⭐</option>
                <option value="2">⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
              </select>
              <button id="btn-valorar" disabled>Valorar</button>
              <p id="missatge-valoracio"></p>
            </div>
          </div>
        `;

        const selectEstrelles = document.getElementById('select-estrelles');
        const btnValorar = document.getElementById('btn-valorar');

        selectEstrelles.addEventListener('change', () => {
          btnValorar.disabled = selectEstrelles.value === '';
        });

        btnValorar.addEventListener('click', () => {
          const estrelles = parseInt(selectEstrelles.value);
          fetch('../api/imatgesApi.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: parseInt(id), estrelles })
          })
          .then(r => r.json())
          .then(res => {
            if(res.imatge) {
              document.querySelector('.valoracio-numero').textContent = res.imatge.valoracio + ' ⭐';
              document.querySelector('.num-votacions').textContent = `(${res.imatge.num_votacio} valoracions)`;
              document.getElementById('missatge-valoracio').textContent = 'Valoració enviada!';
              btnValorar.disabled = true;
            }else {
              document.getElementById('missatge-valoracio').textContent = 'Error: ' + (res.error || 'desconegut');
            }
          })
          .catch(() => {
            document.getElementById('missatge-valoracio').textContent = 'Error de connexió.';
          });
        });

      }else {
        container.innerHTML = `<p>Error: ${data.error || 'Imatge no trobada.'}</p>`;
      }
    })
  .catch(() => {
    document.getElementById('imatge-container').innerHTML = "<p>Error de connexió a l'API.</p>";
  });
</script>

<?php include_once __DIR__ . '/../includes/footer.html'; ?>