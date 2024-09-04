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


$sql = "INSERT INTO login (username,password)values(?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $username, $pass);


if($stmt->execute())
{
    header('Location:account_created.html');
}


$stmt->close();
$con->close();
?>