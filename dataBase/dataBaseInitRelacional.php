<?php
require_once __DIR__ . '/../includes/db_connect.php'; // Connexió a la base de dades SQLite

$db->exec("CREATE TABLE IF NOT EXISTS likes (
    id_like TEXT PRIMARY KEY,
    id_imatge TEXT,
    usuari TEXT,
    FOREIGN KEY (id_imatge) REFERENCES imatges(id),
    FOREIGN KEY (usuari) REFERENCES usuaris(usuari)
)");