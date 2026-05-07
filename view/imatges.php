<?php
include_once __DIR__ . '/../controller/auth.php';
$usuari = validarToken();

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

  const id = getParam('id') || 1;
  const url = `../api/imatgesApi.php?id=${id}`;

  fetch(url)
    .then(r => r.json())
    .then(data => {
      const container = document.getElementById('imatge-container');

      if(data && !data.error) {
        container.innerHTML = `
          <div class="imatge-card">
            <img src="${data.img_url}" alt="${data.img_titol}" loading="lazy">
            <div class="imatge-info">
              <h2>${data.img_titol}</h2>
              <p>Likes: ${data.num_likes}</p>
              <?php if(!$usuari): ?>
                <p>Inicia sessió per a donar likes.<p>
                <button disabled>Like</button>
              <?php else: ?>
                <button id="btn-like">Like</button>
              <?php endif; ?>
            </div>
          </div>
        `;
      <?php if($usuari): ?>
        document.getElementById('btn-like').addEventListener('click', () => {
          const btn = this;
          btn.disabled = true;// evita doble click mentre es processa un

          fetch('../api/imatgesApi.php', {
            method: 'PATH',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id})
          })
          .then(r => r.json())
          .then(res => {
            if(res.num_likes !== undefined) {
              document.getElementById('num-likes').textContent = res.num_likes;
              btn.textContent = 'Like donat!';
            }else {
              alert('Error: ' + (res.error || "No s'ha pogut fer like"));
              btn.disabled = false;
            }
          })
          .catch(err => {
            console.error(err);
            alert('Error de connexió');
            btn.disabled = false;
          });
        });
      <?php endif; ?>
      }else {
        container.innerHTML = `<p>Error: ${data.error || 'Imatge no trobada.'}</p>`;
      }
    })
  .catch(err => {
    console.error(err);
    document.getElementById('imatge-container').innerHTML = "<p>Error de connexió a l'API.</p>";
  });
</script>

<?php include_once __DIR__ . '/../includes/footer.html'; ?>