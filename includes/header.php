<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/auth.php'); ?>

<?php
    // Valida el token per saber si hi ha un usuari autenticat
    $usuari = validarToken();
?>

<header class="capcalera">
    <nav class="menu">
        <div class="logo">
            <a href="../index.php">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Pinterest_Logo_%282%29.svg?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=original" alt="Logo" />
            </a>
        </div>
        <div class="links">
            <?php if($usuari): ?>
                <span class="salutacio">Hola, <?= htmlspecialchars($usuari['nom']) ?>!</span>
                <a href="/controller/logout.proc.php">Logout</a>
            <?php else: ?>
                <a href="/view/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>