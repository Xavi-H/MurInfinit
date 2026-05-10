<?php
function getImatges($limit, $offset, $db) {
    $sql = "SELECT * FROM imatges LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', (int)$offset, SQLITE3_INTEGER);
    $resultat = $stmt->execute();

    $imatges = [];
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        $imatges[] = $row;
    }
    return $imatges;
}

function getImatgeById($id, $db) {
    $sql = "SELECT * FROM imatges WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', (int)$id, SQLITE3_INTEGER);
    $resultat = $stmt->execute();
    return $resultat->fetchArray(SQLITE3_ASSOC);
}

function cercaImatgeAlVol($cerca, $db) {
    $consulta = "SELECT * FROM imatges WHERE LOWER(par_clau) LIKE LOWER(:cerca)";
    $stmt = $db->prepare($consulta);
    $stmt->bindValue(':cerca', "%$cerca%", SQLITE3_TEXT);
    $resultat = $stmt->execute();

    $imatges = [];
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        $imatges[] = $row;
    }
    return $imatges;
}

function cercaTitolImatgeAlVol($cerca, $db) {
    $consulta = "SELECT img_titol, id FROM imatges WHERE LOWER(img_titol) LIKE LOWER(:cerca) LIMIT 5";
    $stmt = $db->prepare($consulta);
    $stmt->bindValue(':cerca', "%$cerca%", SQLITE3_TEXT);
    $resultat = $stmt->execute();

    $img = [];
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        $img[] = $row;
    }
    return $img;
}

/**
 * Dona like a una imatge per part d'un usuari.
 * Insereix un registre a la taula 'likes'.
 * Si l'usuari ja ha donat like (UNIQUE constraint), retorna 'ja_like'.
 * Retorna l'imatge actualitzada amb el nou num_likes, o 'ja_like', o false si no existeix.
 */
function donarLikeUsuari($id_imatge, $usuari, $db) {
    // Comprovar si l'usuari ja ha donat like a aquesta imatge
    $checkSql = "SELECT id_like FROM likes WHERE id_imatge = :id_imatge AND usuari = :usuari";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->bindValue(':id_imatge', (int)$id_imatge, SQLITE3_INTEGER);
    $checkStmt->bindValue(':usuari', $usuari, SQLITE3_TEXT);
    $checkResultat = $checkStmt->execute();

    if ($checkResultat->fetchArray(SQLITE3_ASSOC)) {
        // Ja ha donat like
        return 'ja_like';
    }

    // Inserir el like a la taula likes
    $insertSql = "INSERT INTO likes (id_imatge, usuari) VALUES (:id_imatge, :usuari)";
    $insertStmt = $db->prepare($insertSql);
    $insertStmt->bindValue(':id_imatge', (int)$id_imatge, SQLITE3_INTEGER);
    $insertStmt->bindValue(':usuari', $usuari, SQLITE3_TEXT);
    $insertStmt->execute();

    // Incrementar el comptador de likes a la taula imatges
    $updateSql = "UPDATE imatges SET num_likes = num_likes + 1 WHERE id = :id";
    $updateStmt = $db->prepare($updateSql);
    $updateStmt->bindValue(':id', (int)$id_imatge, SQLITE3_INTEGER);
    $updateStmt->execute();

    return getImatgeById($id_imatge, $db); // Retorna l'imatge actualitzada amb el nou num_likes
}

function getImatgesLikedByUser($username, $db) {
    $sql = "SELECT i.* FROM imatges i JOIN likes l ON i.id = l.id_imatge WHERE l.usuari = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $resultat = $stmt->execute();
 
    $imatges = [];
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        $imatges[] = $row;
    }
    return $imatges;
}