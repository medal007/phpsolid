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

$getpgno = dbquery("SELECT * FROM ".$table3." WHERE id = '".dbescape($pageid)."'")->rows;
$pgno = $getpgno[0]['pagenumber'];

$tofind = $pgno + 1;

$runadd1 = dbquery("DELETE FROM " .$table3. " WHERE id='".dbescape($pageid)."'");
if($runadd1 > 0){
    $updatevalue1 = dbquery("UPDATE ". $table3 . " SET pagenumber = pagenumber - 1 WHERE pagenumber > " . $pgno ."");
    notify($adminname. " deleted " .$pagename, "All");
    $run1 = dbquery("DROP TABLE " .$table7." ");
    $run2 = dbquery("DELETE FROM " .$table4. " WHERE pageid='".dbescape($pageid)."'");
    
    $getrole = dbquery("SELECT * FROM ".$table6." WHERE id = '$adminrole' LIMIT 1")->rows;
    $roles = $getrole[0]['role'];
    
    $roles = str_replace(", ".$pageid, '', $roles);
    
    $run3 = dbquery("UPDATE ". $table6 . " SET role = '".dbescape($roles)."' WHERE id = '".dbescape($adminrole)."'");
    
    echo '<script language="javascript">';
    echo 'alert("Page Deleted!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../adminpages/" />';
} else{
    echo '<script language="javascript">';
    echo 'alert("Failed to delete!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../adminpages/" />';
}

?>