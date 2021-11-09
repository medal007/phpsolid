<?php
session_start();
if (isset($_SESSION['phpsolid_id'])&&$_SESSION['phpsolid_id']!=""){
    session_destroy();
    header("Location: ../login/");     
} else{
    header("Location: ../login/"); 
}

?>