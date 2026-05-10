<?php
require_once __DIR__ . '/../includes/db_connect.php';

// La constraint UNIQUE(id_imatge, usuari) evita que un usuari pugui
// donar like a la mateixa imatge més d'una vegada
$db->exec("CREATE TABLE IF NOT EXISTS likes (
    id_like INTEGER PRIMARY KEY AUTOINCREMENT,
    id_imatge INTEGER NOT NULL,
    usuari TEXT NOT NULL,
    FOREIGN KEY (id_imatge) REFERENCES imatges(id),
    FOREIGN KEY (usuari) REFERENCES usuaris(usuari),
    UNIQUE(id_imatge, usuari)
)");
?>