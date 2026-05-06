<?php
include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h3>INICIAR SESSIÓ</h3>
    <!-- Contenidor per mostrar errors -->
    <p class="error" id="missatge-error"></p>

    <form class="formulari-edicio" id="form-login">
        <div class="camp">
            <label for="nom">Nom d'usuari:</label>
            <input type="text" id="nom" name="nom">
        </div>
        <div class="camp">
            <label for="contrasenya">Contrasenya:</label>
            <input type="password" id="contrasenya" name="contrasenya">
        </div>
        <div class="botons">
            <button type="submit">Entrar</button>
        </div>
    </form>
</div>

<script src="../js/login.js"></script>
<?php
include_once __DIR__ . ("/../includes/footer.html");
?>