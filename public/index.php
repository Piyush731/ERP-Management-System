<?php
// Basic router
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
    case '':
        require __DIR__ . '/login_page.html';
        break;
    case '/login':
        require __DIR__ . '/login.php';
        break;
    case '/register':
        require __DIR__ . '/register.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/404.html';
        break;
}
?>