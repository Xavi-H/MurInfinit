    document.getElementById("form-login").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita el comportament per defecte del formulari

    // Envia les dades de login al PHP
    const res = await fetch("../controller/login.proc.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            nom: document.getElementById("nom").value,
            contrassenya: document.getElementById("contrasenya").value
        })
    });

    const data = await res.json();

    // Si la resposta és correcta, redirigeix
    if (res.ok) {
        window.location.href = "../view/historialLikes.php";
    } else {
        document.getElementById("missatge-error").textContent = data.error;
    }
});