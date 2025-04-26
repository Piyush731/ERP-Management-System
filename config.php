<?php
// Get the project root directory
$rootDir = dirname(__DIR__);

// Load environment variables from .env file if available
if (file_exists($rootDir . '/.env')) {
    $lines = file($rootDir . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Get database configuration
function getDbConfig() {
    $database_url = getenv('DATABASE_URL');
    
    if ($database_url) {
        // Parse DATABASE_URL
        $db_parts = parse_url($database_url);
        
        return [
            'host' => $db_parts['host'],
            'user' => $db_parts['user'],
            'pass' => $db_parts['pass'],
            'name' => ltrim($db_parts['path'], '/'),
            'type' => 'pgsql' // PostgreSQL
        ];
    }

    return [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'name' => getenv('DB_NAME') ?: 'mini_project',
        'type' => getenv('DB_TYPE') ?: 'mysql',
    ];
}

// Function to get database connection
function getDatabaseConnection() {
    $dbConfig = getDbConfig();
    
    if ($dbConfig['type'] === 'pgsql') {
        $dsn = "pgsql:host={$dbConfig['host']};dbname={$dbConfig['name']}";
        try {
            $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    } else {
        // MySQL connection
        $con = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }
        return $con;
    }
}
?>