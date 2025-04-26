<?php
// Basic router for the application
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove the base path from the request URI
// This might need adjustment based on your deployment
$base_path = '/';
$route = rtrim(substr($path, strlen($base_path)), '/');

// Route the request
switch ($route) {
    case '':
    case 'login':
        require __DIR__ . '/login_page.html';
        break;
        
    case 'process-login':
        require __DIR__ . '/login.php';
        break;
        
    case 'register':
        require __DIR__ . '/create_account.html';
        break;
        
    case 'process-register':
        require __DIR__ . '/register.php';
        break;
        
    case 'admin':
        require __DIR__ . '/admin.html';
        break;
        
    case 'student':
        require __DIR__ . '/student.html';
        break;
        
    case 'faculty':
        require __DIR__ . '/faculty_admin.html';
        break;
        
    default:
        // 404 page
        header('HTTP/1.0 404 Not Found');
        echo '404 - Page not found';
        break;
}
?>