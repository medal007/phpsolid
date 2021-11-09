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
    $getvalue = dbquery("SELECT * FROM ".$table4." WHERE id = '".dbescape($strucId)."'")->rows;
    if($getvalue != null){
        print json_encode($getvalue);
    }
}

if($type == "tick"){
    $gettick= dbquery("SELECT * FROM ".$table4." WHERE id = '".dbescape($tickId)."'")->rows;
    if($gettick != null){
        $tick = $gettick[0]['strucactive'];
        
        if($tick == ""){
            $updateval1 = dbquery("UPDATE ". $table4 . " SET strucactive = '".dbescape("yes")."' WHERE id = '".dbescape($tickId)."'");
            echo json_encode("success");
        } else{
            $updateval1 = dbquery("UPDATE ". $table4 . " SET strucactive = '".dbescape("")."' WHERE id = '".dbescape($tickId)."'");
            echo json_encode("success");
        }
    }
}

if($type == "moveup"){
    $checkvalue1 = dbquery("SELECT * FROM ".$table4." WHERE strucnumber = '".dbescape($structogo)."' AND pageid = '".dbescape($pageid)."'")->rows;
    if($checkvalue1 != null){
        $togoid1 = $checkvalue1[0]['id'];
        $strucname1 = $checkvalue1[0]['strucname'];
        $dbfieldname1 = dbescape($strucname1);
        $dbfieldname1 = strtolower($dbfieldname1);
        $dbfieldname1 = str_replace(' ', '_', $dbfieldname1);
        
        $dbfieldname2 = dbescape($strucName);
        $dbfieldname2 = strtolower($dbfieldname2);
        $dbfieldname2 = str_replace(' ', '_', $dbfieldname2);
        
        $structype1 = $checkvalue1[0]['structype'];
        
        $updatevalue1 = dbquery("UPDATE ". $table4 . " SET strucnumber = '".dbescape($structogo)."' WHERE strucnumber = '".dbescape($strucNo)."' AND pageid = '".dbescape($pageid)."'");
        $updatevalue2 = dbquery("UPDATE ". $table4 . " SET strucnumber = '".dbescape($strucNo)."' WHERE id = '".dbescape($togoid1)."' AND pageid = '".dbescape($pageid)."'");
        
        if($updatevalue1 > 0 && $updatevalue2 > 0){
            
            if($structype1 == "Text Area" || $structype1 == "Editor"){
                
                $justrun1 = dbquery("ALTER TABLE " . $table7 . " CHANGE COLUMN " . $dbfieldname1 . " " . $dbfieldname1 . " TEXT NOT NULL AFTER " . $dbfieldname2 . "");
            
            } else if($structype1 == "Number"){
                
                $justrun1 = dbquery("ALTER TABLE " . $table7 . " CHANGE COLUMN " . $dbfieldname1 . " " . $dbfieldname1 . " INT(255) NOT NULL AFTER " . $dbfieldname2 . "");
                
            } else{
                
                $justrun1 = dbquery("ALTER TABLE " . $table7 . " CHANGE COLUMN " . $dbfieldname1 . " " . $dbfieldname1 . " VARCHAR(255) NOT NULL AFTER " . $dbfieldname2 . "");
                
            }
            
            echo json_encode("success");
        }
    }
}

if($type == "movedown"){
    $checkvalue2 = dbquery("SELECT * FROM ".$table4." WHERE strucnumber = '".dbescape($structogo)."' AND pageid = '".dbescape($pageid)."'")->rows;
    if($checkvalue2 != null){
        $togoid2 = $checkvalue2[0]['id'];
        $strucname2 = $checkvalue2[0]['strucname'];
        
        $dbfieldname3 = dbescape($strucname2);
        $dbfieldname3 = strtolower($dbfieldname3);
        $dbfieldname3 = str_replace(' ', '_', $dbfieldname3);
        
        $dbfieldname4 = dbescape($strucName);
        $dbfieldname4 = strtolower($dbfieldname4);
        $dbfieldname4 = str_replace(' ', '_', $dbfieldname4);
        
        $structype2 = $checkvalue2[0]['structype'];
        
        $updatevalue3 = dbquery("UPDATE ". $table4 . " SET strucnumber = '".dbescape($structogo)."' WHERE strucnumber = '".dbescape($strucNo)."' AND pageid = '".dbescape($pageid)."'");
        $updatevalue4 = dbquery("UPDATE ". $table4 . " SET strucnumber = '".dbescape($strucNo)."' WHERE id = '".dbescape($togoid2)."' AND pageid = '".dbescape($pageid)."'");
        
        if($updatevalue3 > 0 && $updatevalue4 > 0){
            
            if($structype2 == "Text Area" || $structype2 == "Editor"){
                
                $justrun2 = dbquery("ALTER TABLE " . $table7 . " CHANGE COLUMN " . $dbfieldname4 . " " . $dbfieldname4 . " TEXT NOT NULL AFTER " . $dbfieldname3 . "");
            
            } else if($structype2 == "Number"){
                
                $justrun2 = dbquery("ALTER TABLE " . $table7 . " CHANGE COLUMN " . $dbfieldname4 . " " . $dbfieldname4 . " INT(255) NOT NULL AFTER " . $dbfieldname3 . "");
                
            } else{
                
                $justrun2 = dbquery("ALTER TABLE " . $table7 . " CHANGE COLUMN " . $dbfieldname4 . " " . $dbfieldname4 . " VARCHAR(255) NOT NULL AFTER " . $dbfieldname3 . "");
                
            }
            
            echo json_encode("success");
        }
    }
}

if($type == "getfields"){
    $getfields = dbquery("SELECT COLUMN_NAME, ORDINAL_POSITION FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$databaseName' AND TABLE_NAME ='$tablename' ORDER BY ORDINAL_POSITION")->rows;
    if($getfields != null){
        print json_encode($getfields);
    }
}

?>