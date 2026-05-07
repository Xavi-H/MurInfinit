    document.getElementById("form-registre").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita el comportament per defecte del formulari

    // Envia les dades de registre al PHP
    const res = await fetch("../controller/registre.proc.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            nom: document.getElementById("nom").value,
            contrasenya: document.getElementById("contrasenya").value
        })
    });

    const data = await res.json();

    // Si la resposta és correcta, redirigeix
    if (res.ok) {
        window.location.href = "../view/login.php";
    } else {
        document.getElementById("missatge-error").textContent = data.error;
    }
});