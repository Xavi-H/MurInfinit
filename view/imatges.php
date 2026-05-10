<?php
include_once __DIR__ . '/../controller/auth.php';
$usuari = validarToken();
$username = $usuari ? $usuari['nom'] : '';

include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php';
?>

<h1 class="titol">Imatge</h1>

<div id="imatge-container" class="imatge-detail"></div>

<script>
  const usuariLogat = <?php echo $usuari ? 'true' : 'false'; ?>;
  const username = <?php echo $usuari ? json_encode($username) : 'null'; ?>;

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
        let likeSection = '';
        if (!usuariLogat) {
          likeSection = `
            <p>Inicia sessió per a donar likes.</p>
            <button disabled>Like</button>
          `;
        } else {
          likeSection = `
            <button id="btn-like">Like | <span id="num-likes">${data.num_likes}</span></button>
          `;
        }

        container.innerHTML = `
          <div class="imatge-card">
            <img src="${data.img_url}" alt="${data.img_titol}" loading="lazy">
            <div class="imatge-info">
              <h2>${data.img_titol}</h2>
              ${likeSection}
            </div>
          </div>
        `;

        if (usuariLogat) {
          const btn = document.getElementById('btn-like');
          btn.addEventListener('click', () => donarLike(btn, id));
        }
      } else {
        container.innerHTML = `<p>Error: ${data.error || 'Imatge no trobada.'}</p>`;
      }
    })
    .catch(err => {
      console.error(err);
      document.getElementById('imatge-container').innerHTML = "<p>Error de connexió a l'API.</p>";
    });

  async function donarLike(btn, imatgeId) {
    if (btn.disabled) return;
    btn.disabled = true;

    try {
      const res = await fetch('../api/imatgesApi.php', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: parseInt(imatgeId), username: username })
      });
      const data = await res.json();

      if (data.num_likes !== undefined) {
        document.getElementById('num-likes').textContent = data.num_likes;
        btn.innerHTML = 'Like donat! &#9829;';
        btn.classList.add('liked');
      } else if (data.error === 'ja_like') {
        btn.innerHTML = 'Ja has donat like &#9829;';
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

<?php include_once __DIR__ . '/../includes/footer.html'; ?>
