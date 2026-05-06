<?php
require_once __DIR__ . '/../dao/imatgesDao.php';
require_once __DIR__ . '/../includes/db_connect.php';

header('Content-Type: application/json');

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $busqueda = $_GET['busqueda'];
//TODO
        $resultat = cercaTitolImatgeAlVol($busqueda, $db);

        if($resultat) {
            echo json_encode($resultat);
        }

        break;

    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);

        $cerca = isset($body['cerca'])? $body['cerca']: null;


        $resultat = cercaImatgeAlVol($cerca, $db);

        if($resultat) {
            echo json_encode($resultat);
        }else {
            http_response_code(404);
            echo json_encode(['error' => "No s'han trobat imatges per la cerca: $cerca"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Mètode no permès.']);
        break;
}
