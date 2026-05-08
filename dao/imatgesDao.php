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

function valorarImatge($id, $estrelles, $db) {
    $imatge = getImatgeById($id, $db);
    if(!$imatge) return false;

    $numVotacio = $imatge['num_votacio'] + 1;
    $novaValoracio = (($imatge['valoracio'] * $imatge['num_votacio']) + $estrelles) / $numVotacio;
    $novaValoracio = round($novaValoracio, 2);

    $sql = "UPDATE imatges SET valoracio = :valoracio, num_votacio = :num_votacio WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':valoracio', $novaValoracio, SQLITE3_FLOAT);
    $stmt->bindValue(':num_votacio', $numVotacio, SQLITE3_INTEGER);
    $stmt->bindValue(':id', (int)$id, SQLITE3_INTEGER);
    $stmt->execute();

    return getImatgeById($id, $db);
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
    $consulta = "SELECT img_titol FROM imatges WHERE LOWER(img_titol) LIKE LOWER(:cerca) LIMIT 5";
    $stmt = $db->prepare($consulta);
    $stmt->bindValue(':cerca', "%$cerca%", SQLITE3_TEXT);
    $resultat = $stmt->execute();

    $titols = [];
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        $titols[] = $row['img_titol'];
    }
    return $titols;
}

function donarLike($id, $db) {
    $sql = "UPDATE imatges SET num_likes = num_likes + 1 WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', (int)$id, SQLITE3_INTEGER);
    $stmt->execute();
    return getImatgeById($id, $db);
}