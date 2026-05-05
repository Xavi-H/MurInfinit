<?php include_once __DIR__ . '/../includes/head.php'; ?>

<h1 class="titol">Imatge</h1>

<div id="imatge" class="imatge"></div>

<script>
  function getParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  let id = getParam('id');
  let url = id ? `/api/imatgesApi.php?id=${id}` : "/api/imatgesApi.php?id=1"; // ID de la imatge per defecte 1

  fetch(url)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById("imatge");

      if (data) {
        const imatge = data;

        const div = document.createElement("div");
        div.className = "imatge";
        div.innerHTML = `
          <img src="${imatge.img_url}" alt="${imatge.img_titol}" loading="lazy">
        `;
        container.appendChild(div);
      } else {
        container.innerHTML = "<p>Error al obtenir les dades de l'API.</p>";
      }
    })
    .catch(error => {
      document.getElementById("imatge").innerHTML =
        "<p>Error de connexió a l'API.</p>";
      console.error(error);
    });
</script>


      if (data) {
        const product = data;

        const div = document.createElement("div");
        div.className = "producte";
        div.innerHTML = `
          <img src="${product.image}" alt="Imatge del producte">
          <div class="producte-info">
            <h4>${product.title}</h4>
            <p class="preu">Preu: $${product.price}</p>
            <p>${product.description}</p>
            <p class="categoria">Categoria: <a href="veureProductesCategoria.php?categoria=${encodeURIComponent(product.category)}">${product.category}</a></p>
            <p class="rating">Puntuació: ${product.rating.rate} (${product.rating.count} valoracions)</p>
            <?php if ($usuariLoguejat): ?>
              <a href="editarProducte.php?id=${product.id}">Modificar</a>
              <button onclick="eliminarProducte(${product.id})">Eliminar</button>
            <?php endif; ?>
          </div>
        `;
        container.appendChild(div);
      } else {
        container.innerHTML = "<p>Error al obtenir les dades de l'API.</p>";
      }
    })
    .catch(error => {
      document.getElementById("producte-container").innerHTML =
        "<p>Error de connexió a l'API.</p>";
      console.error(error);
    });
    
    function eliminarProducte(id) {
      if (confirm("Estàs segur que vols eliminar aquest producte?")) {
        fetch(`../api/productes.php?id=${id}`, {
          method: "DELETE"
        })
        .then(() => {
          alert("Producte eliminat correctament!");
          location.reload();
        })
        .catch(error => {
          console.error("Error:", error);
        });
      }
    }
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>