<?php
// Include the configuration file (adjust path if needed)
require_once __DIR__ . '/../config.php';

// Get database connection
$db = getDatabaseConnection();
$dbConfig = getDbConfig();

// Get user input
$username = isset($_POST['fname']) ? $_POST['fname'] : '';
$password = isset($_POST['pass']) ? $_POST['pass'] : '';

// Initialize authentication result
$authenticated = false;

// Handle different database types
if ($dbConfig['type'] === 'pgsql') {
    // PostgreSQL authentication
    try {
        $stmt = $db->prepare("SELECT password FROM login WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $hashed_password = $row['password'];
            $authenticated = password_verify($password, $hashed_password);
        }
    } catch (PDOException $e) {
        die("Authentication error: " . $e->getMessage());
    }
} else {
    // MySQL authentication
    $username = mysqli_real_escape_string($db, $username);
    
    $stmt = $db->prepare("SELECT password FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $authenticated = true;
    }
    
    $stmt->close();
}

// Redirect based on authentication result
if ($authenticated) {
    // Password is correct, redirect to appropriate page
    header('Location: admin.html');
    exit;
} else {
    // Invalid credentials
    header('Location: invalid_credentials_page.html');
    exit;
}

// Close MySQL connection if using MySQL
if ($dbConfig['type'] !== 'pgsql') {
    $db->close();
}
?>