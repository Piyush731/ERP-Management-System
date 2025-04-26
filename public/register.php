<?php
// Include the configuration file (adjust path if needed)
require_once __DIR__ . '/../config.php';

// Get database connection
$db = getDatabaseConnection();
$dbConfig = getDbConfig();

// Process only if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username = isset($_POST['fname']) ? $_POST['fname'] : '';
    $raw_password = isset($_POST['pass']) ? $_POST['pass'] : '';
    
    // Hash the password before storing
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
    
    $registration_successful = false;
    
    // Handle different database types
    if ($dbConfig['type'] === 'pgsql') {
        // PostgreSQL registration
        try {
            $stmt = $db->prepare("INSERT INTO login (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            
            if ($stmt->execute()) {
                $registration_successful = true;
            }
        } catch (PDOException $e) {
            die("Registration error: " . $e->getMessage());
        }
    } else {
        // MySQL registration
        $username = mysqli_real_escape_string($db, $username);
        
        $stmt = $db->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            $registration_successful = true;
        }
        
        $stmt->close();
    }
    
    // Redirect based on registration result
    if ($registration_successful) {
        header('Location: account_created.html');
        exit;
    } else {
        header('Location: registration_failed.html'); // Create this page
        exit;
    }
    
    // Close MySQL connection if using MySQL
    if ($dbConfig['type'] !== 'pgsql') {
        $db->close();
    }
} else {
    // If not a POST request, redirect to registration form
    header('Location: create_account.html');
    exit;
}
?>