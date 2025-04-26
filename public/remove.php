<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "mini_project";


$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


$FirstName = mysqli_real_escape_string($con, $_POST['Fname']); 
$LastName = mysqli_real_escape_string($con, $_POST['Lname']);
$ID = mysqli_real_escape_string($con, $_POST['id']);
$Course = mysqli_real_escape_string($con, $_POST['Course']);


$sql = "INSERT INTO student (fname,lname,id,course)values(?,?,?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssss", $FirstName , $LastName,$ID,$Course);


if($stmt->execute())
{
    echo "Added Successfully";
}


$stmt->close();
$con->close();
?>