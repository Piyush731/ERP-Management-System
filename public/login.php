<?php

require_once 'config.php';
$dbConfig = getDbConfig();
if ($dbConfig['type'] === 'pgsql') {
    $dsn = "pgsql:host={$dbConfig['host']};dbname={$dbConfig['name']}";
    try {
        $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Use $pdo for database operations
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
} else {
    // MySQL connection
    $con = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
}

$username = mysqli_real_escape_string($con, $_POST['fname']);
$password = mysqli_real_escape_string($con, $_POST['pass']);

// Get the hashed password from database
$sql = "SELECT password FROM login WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hashed_password);

if ($stmt->fetch() && password_verify($password, $hashed_password)) {
    // Password is correct, redirect to appropriate page
    header('Location: admin.html'); // Or your main page
} else {
    // Invalid credentials
    header('Location: invalid_credentials_page.html');
}

$stmt->close();
$con->close();
?>