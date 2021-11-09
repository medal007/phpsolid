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
    print json_encode("setup");
}

else if (!isset($_SESSION['phpsolid_id'])&&$_SESSION['phpsolid_id']==""){
   print json_encode("login");     
}

if($type == "view"){
    $getvalue = dbquery("SELECT * FROM ".$table3." WHERE id = '".dbescape($pageId)."'")->rows;
    if($getvalue != null){
        print json_encode($getvalue);
    }
}

if($type == "moveup"){
    $checkvalue1 = dbquery("SELECT * FROM ".$table3." WHERE pagenumber = '".dbescape($pagetogo)."'")->rows;
    if($checkvalue1 != null){
        $togoid1 = $checkvalue1[0]['id'];
        $pagename1 = $checkvalue1[0]['pagename'];
        
        $updatevalue1 = dbquery("UPDATE ". $table3 . " SET pagenumber = '".dbescape($pagetogo)."' WHERE pagenumber = '".dbescape($pageNo)."'");
        $updatevalue2 = dbquery("UPDATE ". $table3 . " SET pagenumber = '".dbescape($pageNo)."' WHERE id = '".dbescape($togoid1)."'");
        
        if($updatevalue1 > 0 && $updatevalue2 > 0){
            echo json_encode("success");
        }
    }
}

if($type == "movedown"){
    $checkvalue2 = dbquery("SELECT * FROM ".$table3." WHERE pagenumber = '".dbescape($pagetogo)."'")->rows;
    if($checkvalue2 != null){
        $togoid2 = $checkvalue2[0]['id'];
        $pagename2 = $checkvalue2[0]['pagename'];
        
        $updatevalue3 = dbquery("UPDATE ". $table3 . " SET pagenumber = '".dbescape($pagetogo)."' WHERE pagenumber = '".dbescape($pageNo)."'");
        $updatevalue4 = dbquery("UPDATE ". $table3 . " SET pagenumber = '".dbescape($pageNo)."' WHERE id = '".dbescape($togoid2)."'");
        
        if($updatevalue3 > 0 && $updatevalue4 > 0){
            echo json_encode("success");
        }
    }
}

?>