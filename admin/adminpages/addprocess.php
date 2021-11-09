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

$pgname = str_replace(' ', '_', $pagename);
$pgname = strtolower($pgname);

$table7 = TB_PRE . $pgname."_usergen";

$psid = $_SESSION['phpsolid_id'];
$getadmin = dbquery("SELECT * FROM ".$table1." WHERE id = '".dbescape($psid)."'")->rows;
$adminimage = $getadmin[0]['image'];
$adminname = $getadmin[0]['fullname'];
$adminemail = $getadmin[0]['email'];
$adminrole = $getadmin[0]['role'];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$currentdate = date("Y-m-d H:i:s");

$pagenumber = 1;

$getpgno = dbquery("SELECT * FROM ".$table3." ORDER BY pagenumber DESC LIMIT 1")->rows;

if($getpgno != null){
    $pgno = $getpgno[0]['pagenumber'];
    $pagenumber = $pgno + 1;
}

if($pageicon == "" || !isset($pageicon)){
    $pageicon = "fas fa-file-alt";
}else{
    $pageicon = "fas " . $pageicon;
    $pageicon = str_replace('-o', '', $pageicon);
}

// if($actions == "" || $actions == null){
//     $actions = "Delete";
// } else{
//     $actions = implode(', ', $actions);
// }

if($pagestatus != "restrictedpager"){
    $pagerole = 0;
}
    
$runadd1 = dbquery("INSERT INTO " .$table3. " (pagename, pagenumber, pagestatus, pagerole, pageicon, date_created) VALUES ('".dbescape($pagename)."', '".dbescape($pagenumber)."', '".dbescape($pagestatus)."', '".dbescape($pagerole)."', '".dbescape($pageicon)."', '".dbescape($currentdate)."')");
if($runadd1 > 0){
    notify($adminname. " added a page: " .$pagename, "All");
    $run1 = dbquery("CREATE TABLE ".$table7."(id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT, pageres INT(255) NOT NULL, date_created VARCHAR(255))");
    
    $getrole = dbquery("SELECT * FROM ".$table6." WHERE id = '$adminrole' LIMIT 1")->rows;
    $roles = $getrole[0]['role'];
    
    $getlast = dbquery("SELECT * FROM ".$table3." ORDER BY id DESC LIMIT 1")->rows;
    $last = $getlast[0]['id'];
    
    $inputinto = $roles . ", " . $last;
    
    $run2 = dbquery("UPDATE ". $table6 . " SET role = '".dbescape($inputinto)."' WHERE id = '".dbescape($adminrole)."'");
    
    echo '<script language="javascript">';
    echo 'alert("New Page Added!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../adminpages/" />';
    
} else{
    echo '<script language="javascript">';
    echo 'alert("Invalid Details!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../adminpages/" />';
}
?>