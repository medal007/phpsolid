<?php

$host = "localhost";
$user = "ngslccor_tolu";
$password = "tolayo";
$db = "ngslccor_phpsolid";

//$con = mysql_connect($host,$user,$password) or die("Could not connect to database");
//mysql_select_db($db,$con) or die("No database selected");

$con = mysqli_connect($host,$user,$password,$db);

if($con == null){
    echo "servererror";
}

session_start();
?>