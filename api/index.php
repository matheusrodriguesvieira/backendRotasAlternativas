<?php

// Roteamento manual
$uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';

switch ($uri) {
    case '/':
        break;
    case '/home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    case '/about':
        require_once 'controllers/AboutController.php';
        $controller = new AboutController();
        $controller->index();
        break;
    default:
        // Lidar com 404
        http_response_code(404);
        echo '404 Not Foung';
        break;
}
exit;
