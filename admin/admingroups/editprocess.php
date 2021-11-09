<?php	
require_once '../functions/dataaccess.php';
session_start();

extract($_POST);

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

else if (!isset($_SESSION['phpsolid_id'])&&$_SESSION['phpsolid_id']==""){
   header("Location: ../login/");     
}

$psid = $_SESSION['phpsolid_id'];
$getadmin = dbquery("SELECT * FROM ".$table1." WHERE id = '".dbescape($psid)."'")->rows;
$adminimage = $getadmin[0]['image'];
$adminname = $getadmin[0]['fullname'];
$adminemail = $getadmin[0]['email'];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$pages = implode(', ', $pages);
$actions = implode(', ', $actions);
$currentdate = date("Y-m-d H:i:s");

$runadd1 = dbquery("UPDATE ". $table6 . " SET groupname = '".dbescape($groupname)."', role = '".dbescape($pages)."', action = '".dbescape($actions)."' WHERE id = '".dbescape($groupid)."'");

if($runadd1 > 0){
    notify($adminname. " edited " .$groupname, "All");
    echo '<script language="javascript">';
    echo 'alert("User Group Updated!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../admingroups/" />';
} else{
    echo '<script language="javascript">';
    echo 'alert("Invalid Details!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../admingroups/" />';
}
?>