<?php
require_once '../functions/dataaccess.php';

extract($_POST);

$password = trim($password);
$password = dbescape($password);
$password = md5($password);

$table1 = TB_PRE . "admin";
$table2 = TB_PRE . "config";
$table3 = TB_PRE . "pages";
$table4 = TB_PRE . "structure";
$table5 = TB_PRE . "notification";
$table6 = TB_PRE . "groups";

$run1 = dbquery("CREATE TABLE ".$table1."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, fullname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, role INT(255) NOT NULL, date_created VARCHAR(255) NOT NULL)");
$run2 = dbquery("CREATE TABLE ".$table2."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, projectname VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, copyright VARCHAR(255) NOT NULL, dicon1 VARCHAR(255) NOT NULL, dicon2 VARCHAR(255) NOT NULL, dicon3 VARCHAR(255) NOT NULL, dicon4 VARCHAR(255) NOT NULL, date_created VARCHAR(255) NOT NULL)");
$run3 = dbquery("CREATE TABLE ".$table3."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, pagename VARCHAR(255) NOT NULL, pagenumber INT(255) NOT NULL, pagestatus VARCHAR(255) NOT NULL, pagerole VARCHAR(255) NOT NULL, pageicon VARCHAR(255) NOT NULL, date_created VARCHAR(255) NOT NULL)");
$run4 = dbquery("CREATE TABLE ".$table4."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, pageid INT(255) NOT NULL, strucname VARCHAR(255) NOT NULL, strucnumber INT(255) NOT NULL, structype VARCHAR(255) NOT NULL, strucextra TEXT NOT NULL, strucactive VARCHAR(255) NOT NULL DEFAULT '', date_created VARCHAR(255) NOT NULL)");
$run5 = dbquery("CREATE TABLE ".$table5."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, message VARCHAR(255) NOT NULL, recipient VARCHAR(255) NOT NULL, date_created VARCHAR(255) NOT NULL)");
$run6 = dbquery("CREATE TABLE ".$table6."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, groupname VARCHAR(255) NOT NULL, role TEXT NOT NULL, action TEXT NOT NULL, date_created VARCHAR(255) NOT NULL)");

if($run1 > 0 && $run2 > 0 && $run3 > 0 && $run4 > 0 && $run5 > 0  && $run6 > 0){
    $domainname = $_SERVER['SERVER_NAME'];
    $defaultrole = "adminusers, adminpages, admingroups, settings, dashboard, profile, structure";
    $defaultaction = "Add, Update, Delete";
    $defaultavatar = "https://" . $domainname . "/admin/assets/images/avatar.png";
    $defaulticon = "https://" . $domainname . "/admin/assets/images/phpsolid.png";
    $defaultbg = "https://images.pexels.com/photos/1327496/pexels-photo-1327496.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940";
    $defaultcopyright = "Build Your Dream Project With Ease";
    $currentdate = date("Y-m-d H:i:s");
    $roleint = 1;
    
    $dicon1 = "fas fa-file,{$table5},Example Tag";
    $dicon2 = "fas fa-file,{$table5},Example Tag";
    $dicon3 = "fas fa-file,{$table5},Example Tag";
    $dicon4 = "fas fa-file,{$table5},Example Tag";
    
    $runadd1 = dbquery("INSERT INTO " .$table1. " (fullname, email, password, image, role, date_created) VALUES ('".dbescape($fullname)."', '".dbescape($email)."', '".$password."', '".dbescape($defaultavatar)."', '".dbescape($roleint)."', '".dbescape($currentdate)."')");
    $runadd2 = dbquery("INSERT INTO " .$table2. " (projectname, icon, background, copyright, dicon1, dicon2, dicon3, dicon4, date_created) VALUES ('".dbescape($project_name)."', '".dbescape($defaulticon)."', '".dbescape($defaultbg)."', '".dbescape($defaultcopyright)."', '".dbescape($dicon1)."', '".dbescape($dicon2)."', '".dbescape($dicon3)."', '".dbescape($dicon4)."', '".dbescape($currentdate)."')");
    $runadd3 = dbquery("INSERT INTO " .$table6. " (groupname, role, action, date_created) VALUES ('".dbescape("Administrator")."', '".dbescape($defaultrole)."', '".dbescape($defaultaction)."', '".dbescape($currentdate)."')");
    
    if($runadd1 > 0 && $runadd2 > 0  && $runadd3 > 0){
        $notifyemail = dbescape($email);
        notify("Welcome!😎😍, ". $fullname, $notifyemail);
        header("Location:../login/");
    } else{
        header("Location:../setup/");
    }
    
} else{
    header("Location:../setup/");
}
?>