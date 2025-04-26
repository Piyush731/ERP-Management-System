<?php 
require_once 'config.php';
$dbConfig = getDbConfig();
$con = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']); 
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$username = mysqli_real_escape_string($con, $_POST['fname']); 
$raw_password = mysqli_real_escape_string($con, $_POST['pass']);

// Hash the password before storing it
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO login (username,password)values(?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);

if($stmt->execute())
{
    header('Location:account_created.html');
}

$stmt->close();
$con->close();
?>