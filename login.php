<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "mini_project";


$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$username = mysqli_real_escape_string($con, $_POST['fname']); 
$pass = mysqli_real_escape_string($con, $_POST['pass']);
if($username==='admin'&& $pass==='admin')
{
    header('Location:admin.html');
    exit();
    
}


$sql = "SELECT * FROM login WHERE username = ? AND password = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $username, $pass);
$stmt->execute();
$result = $stmt->get_result(); 
 if ($result->num_rows === 1) {
   
    header('Location:student.html');
    exit(); 
} else {
    header('Location:invalid_credentials_page.html');
    
}


$stmt->close();
$con->close();
?>


