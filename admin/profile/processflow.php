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
    $getvalue = dbquery("SELECT * FROM ".$table7." WHERE id = '".dbescape($varrId)."'")->rows;
    if($getvalue != null){
        print json_encode($getvalue);
    }
}

$arr = array();

if($type == "edit"){
    $getdata = dbquery("SELECT * FROM ".$table7." WHERE id = '$varrId' LIMIT 1")->rows;
    foreach($getdata as $data){
        
        $getval = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' AND strucactive = 'yes'")->rows;
        foreach($getval as $val){
            $strucname = $val['strucname'];
            $structype = $val['structype'];
            
            $strucext = $val['strucextra'];
            $listext = explode(",",$strucext);
            $firstext = $listext[0];
            $secondext = $listext[1];
            $thirdext = $listext[2]; 
            $fourthext = $listext[3];
            
            $dbfieldname = dbescape($strucname);
            $dbfieldname = strtolower($dbfieldname);
            $dbfieldname = str_replace(' ', '_', $dbfieldname);
            
            $varrid = $data['id'];
            $varr = $data[$dbfieldname];
            $varrdate = $data['date_created'];
            $varrago = getdiff($varrdate);
            
            $arr[] = array("code" => $dbfieldname, "content" => $varr, "type" => $structype, "extra" => $strucext);
        }
        
    }
    print json_encode($arr);
}


if($type == "viewbtn"){
    $getvalue = dbquery("SELECT * FROM ".$getdb." WHERE id = '".dbescape($getid)."'")->rows;
    if($getvalue != null){
        $value = $getvalue[0][$toget];
        print json_encode($value);
    }
}

if($type == "encpassword"){
    $encpassword = md5($password);
    print json_encode($encpassword);
}

?>