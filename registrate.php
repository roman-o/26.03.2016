<?php
session_start();
 if ($_SESSION['user'] == 'logged'){
	 header("location: /index.php");
 }
$type = 'reg';
include 'config.php';
include 'lib.php';
$pass = (md5($_POST['password'].SALT));
LOG_REG($type, $link, $pass);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
   

  
    <meta name="description" content="">
    

     <title><?=$title;?></title>

 
<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    
    
  </head>

  <body>

    <div class="container">

 <form class="form-signin" action="" method="POST">
        <h2 class="form-signin-heading">Регистрация на сайте</h2>
       
        <input type="text"  class="form-control" placeholder="логин" required name="login" >
        <input type="password"  class="form-control" placeholder="пароль" required name="password">
       
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
      </form>




   </div> <!-- /container -->

  </body>
</html>


