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
            }else {
                http_response_code(404);
                echo json_encode(['error' => "Imatge amb id $id no trobada"]);
            }

        }else {
            // totes les imatges
            $limit = isset($_GET['limit'])? (int)$_GET['limit']: 30;
            $offset = isset($_GET['offset'])? (int)$_GET['offset']: 0;
            echo json_encode(getImatges($limit, $offset, $db));
        }
        break;

    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);

        $id = isset($body['id'])? (int)$body['id']: null;
        $estrelles = isset($body['estrelles'])? (float)$body['estrelles']: null;

        // Seguretat en cas de curl o Postman
        if($id === null || $estrelles === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Falten camps: id i estrelles (1-5)']);
            exit;
        }
        if($estrelles < 1 || $estrelles > 5) {
            http_response_code(400);
            echo json_encode(['error' => 'Les estrelles han de ser entre 1 i 5']);
            exit;
        }

        $resultat = valorarImatge($id, $estrelles, $db);

        if($resultat) {
            echo json_encode([
                'missatge' => 'Valoració guardada correctament',
                'imatge' => $resultat
            ]);
        }else {
            http_response_code(404);
            echo json_encode(['error' => "Imatge amb id $id no trobada"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Mètode no permès. Usa GET o POST.']);
        break;
}
