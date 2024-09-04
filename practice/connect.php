<?php
 $firstname = $_POST['firstname'];
 $lastname=$_POST['lastname'];
 $email=$_POST['email'];
 $password=$_POST['password'];
 $phone=$_POST['phone']; 

 $conn = new mysqli("localhost","root","","testdatabase");
 if($conn->connect_error){
    die("connect failed".$conn->connect_error);
 }
 else{
   $stmt=connect->prepare("insert into register(firstname,lastname,email,password,phone) values (?,?,?,?,?)");
   $stmt=bind_param=(sssss,$firstname,$lastname,$email,$password,$phone);
   $stmt=execute();
   echo "registered successfully";
   session_start();   
   $_SESSION["logged_in"]=true;                //setting session
  
   setcookie("firstname",$firstname,time()+3600);   //setting cookie
   setcookie("lastname",$lastname,time()+3600);
   setcookie("email",$email,time()+3600);
   setcookie("password",$password,time()+3600);
   setcookie("phone",$phone,time()+3600);

   if(isset($_COOKIE["phone"])){      //retrieving cookie and session is same 
      echo("phone is".$_COOKIE["phone"]."<br>");
   }

   header("Location: dashboard.php"); // Redirect to the dashboard 
   $stmt->close();
   $conn->close(); 
 }
?>