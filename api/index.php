<?php
/**
 * Resolving CORS
 */
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}


require '../vendor/autoload.php';

use Controller\ContactController as Controller;

function getMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

function getContentType() {
    return $_SERVER['HTTP_ACCEPT'];
}

$controller = new Controller();

switch (getMethod()) {
    case 'GET':
        $controller->get();
        break;
    case 'POST':
        $controller->post();
        break;
    case 'PUT':
        $controller->put();
        break;
    case 'DELETE':
        $controller->delete();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Request Method'
        ]);
        http_response_code(405);
        break;
}


