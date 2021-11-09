<?php
require_once '../functions/dataaccess.php';
session_start();

$table1 = TB_PRE . "admin";
$table2 = TB_PRE . "config";
$table3 = TB_PRE . "pages";
$table4 = TB_PRE . "structure";
$table5 = TB_PRE . "notification";
$table6 = TB_PRE . "groups";

$check1 = dbquery("SHOW TABLES LIKE '".$table1."'")->num_rows;
$check2 =  dbquery("SHOW TABLES LIKE '".$table2."'")->num_rows;
$check3 =  dbquery("SHOW TABLES LIKE '".$table3."'")->num_rows;
$check4 =  dbquery("SHOW TABLES LIKE '".$table4."'")->num_rows;
$check5 =  dbquery("SHOW TABLES LIKE '".$table5."'")->num_rows;
$check6 =  dbquery("SHOW TABLES LIKE '".$table6."'")->num_rows;

if($check1 < 1 && $check2 < 1 && $check3 < 1 && $check4 < 1 && $check5 < 1  && $check6 < 1){
    session_destroy();
    header("Location:../setup");
}
else if (isset($_SESSION['phpsolid_id'])&&$_SESSION['phpsolid_id']!=""){
   header("Location: ../dashboard/");     
}
$id = 1;

$table2 = TB_PRE . "config";

$getconfig = dbquery("SELECT * FROM ".$table2." WHERE id = '".dbescape($id)."'")->rows;

$projectname = $getconfig[0]['projectname'];
$icon = $getconfig[0]['icon'];
$background = $getconfig[0]['background'];
$copyright = $getconfig[0]['copyright'];

?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex">
  <meta name="googlebot" content="noindex">
  <title>Welcome to - <?php echo $projectname ?></title>
  <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Hind:300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'><link rel="stylesheet" href="../assets/css/style.css">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=YAXaAv7pao">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $icon ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $icon ?>">
  <link rel="manifest" href="<?php echo $icon ?>">
  <link rel="mask-icon" href="<?php echo $icon ?>" color="#009af6">
  <link rel="shortcut icon" href="<?php echo $icon ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
  
  <style>
      html { 
          background: url(<?php echo $background ?>) no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
          overflow: hidden;
        }
  </style>

</head>
<body>
<!-- partial:index.partial.html -->
<div id="login-button">
  <img src="<?php echo $icon ?>">
  </img>
</div>
<div id="container">
  <h1>Log In</h1>
  <span class="close-btn">
    <img src="https://www.freeiconspng.com/uploads/cancel-close-button-png-11.png"></img>
  </span>

  <form id="form_id" method="POST" action="loginprocess.php">
    <input type="email" name="email" placeholder="E-mail" required>
    <input id="pass_log_id" type="password" name="password" placeholder="Password" required > <span id="visible"><i class="far fa-eye" id="togglePassword"></i></span>
    <a href="javascript:$('#form_id').submit();">Log in</a>
    <div id="remember-container">
      <input type="checkbox" id="checkbox-2-1" class="checkbox" checked="checked"/>
      <span id="remember">Remember me</span>
      <span id="forgotten">Forgotten password</span>
    </div>
</form>
</div>

<!-- Forgotten Password Container -->
<div id="forgotten-container">
   <h1>Forgotten</h1>
  <span class="close-btn">
    <img src="https://www.freeiconspng.com/uploads/cancel-close-button-png-11.png"></img>
  </span>

  <form>
    <input type="email" name="email" placeholder="E-mail">
    <a href="#">Get new password</a>
</form>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="../assets/js/script.js"></script>

<script>

$("body").on('click', '#togglePassword', function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $("#pass_log_id");
  if (input.attr("type") === "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }

});
</script>
</body>
</html>
