<?php
require_once __DIR__ . '/../dao/imatgesDao.php';
require_once __DIR__ . '/../includes/db_connect.php';

header('Content-Type: application/json');

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // imatge per id
        if(isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $imatge = getImatgeById($id, $db);

            if($imatge) {
                echo json_encode($imatge);
            } else {
                http_response_code(404);
                echo json_encode(['error' => "Imatge amb id $id no trobada"]);
            }
        } elseif(isset($_GET['username'])) {
            // imatges que ha donat like un usuari
            $username = $_GET['username'];
            echo json_encode(getImatgesLikedByUser($username, $db));
        } else {
            // totes les imatges
            $limit = isset($_GET['limit'])? (int)$_GET['limit']: 30;
            $offset = isset($_GET['offset'])? (int)$_GET['offset']: 0;
            echo json_encode(getImatges($limit, $offset, $db));
        }
        break;

    case 'PATCH': // Dona like a una imatge per un usuari
        $body = json_decode(file_get_contents('php://input'), true);
        $id      = isset($body['id'])       ? (int)$body['id']       : null;
        $username = isset($body['username']) ? trim($body['username']) : null;

        // Validacions
        if($id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Falta el camp "id"']);
            exit;
        }

        if(empty($username)) {
            http_response_code(401);
            echo json_encode(['error' => 'Cal estar autenticat per donar like']);
            exit;
        }

        $resultat = donarLikeUsuari($id, $username, $db);

        if($resultat === 'ja_like') {
            http_response_code(409);
            echo json_encode(['error' => 'ja_like', 'missatge' => 'Ja has donat like a aquesta imatge']);
        } elseif($resultat) {
            echo json_encode([
                'missatge'  => 'Like registrat correctament',
                'num_likes' => $resultat['num_likes']
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => "Imatge amb id $id no trobada"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Mètode no permès.']);
        break;
}
