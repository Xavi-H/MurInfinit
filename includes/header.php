<?php include_once __DIR__ . '/../controller/auth.php'; ?>

<?php
    // Valida el token per saber si hi ha un usuari autenticat
    $usuari = validarToken();
?>

<header class="capcalera">
    <nav class="menu-nav">
        <div class="logo-container">
            <a href="../index.php">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Pinterest_Logo_%282%29.svg" alt="Logo" class="logo-img" />
            </a>
        </div>

        <div class="links-container">
            <?php if($usuari): ?>
                <span class="salutacio-text">Hola, <strong><?= htmlspecialchars($usuari['nom']) ?></strong>!</span>
                <a href="/view/perfilUsuari.php" class="btn-link">Historial de likes</a>
                <a href="/controller/logout.proc.php" class="btn-link btn-logout">Logout</a>
            <?php else: ?>
                <a href="/view/login.php" class="btn-link btn-login">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>