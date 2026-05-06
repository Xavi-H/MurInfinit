<?php
// Eliminar la cookie del token posant una data d'expiració passada
setcookie('token', '', time() - 3600, '/');

header("Location: /index.php");
exit();
?>