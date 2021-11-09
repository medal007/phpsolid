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
    
    else if (isset($_SESSION['phpsolid_id'])&&$_SESSION['phpsolid_id']!=""){
       header("Location: ../dashboard/");     
    }

	$email = dbescape($email);
	$password = trim($password);
    $password = dbescape($password);
	$password = md5($password);
	
	$veremail = dbquery("SELECT * FROM ".$table1." WHERE email = '$email'")->rows;
	$veremailpass = dbquery("SELECT * FROM ".$table1." WHERE email = '$email' AND password = '$password'")->rows;
	
	$dbemail = $veremail[0]['email'];
	$dbpassword = $veremailpass[0]['password'];
	$dbimage = $veremailpass[0]['image'];
	$dbid = $veremailpass[0]['id'];
	$dbfullname = $veremailpass[0]['fullname'];
	$dbrole = $veremailpass[0]['role'];
    
    if ($dbemail == $email){
        if ($dbpassword == $password){
            $_SESSION['phpsolid_fullname'] = $dbfullname;
            $_SESSION['phpsolid_email'] = $dbemail;
            $_SESSION['phpsolid_role'] = $dbrole;
            $_SESSION['phpsolid_image'] = $dbimage;
            $_SESSION['phpsolid_id'] = $dbid;
            header("Location: ../dashboard/");
        } else {
            echo '<script language="javascript">';
            echo 'alert("Invalid Password!")';
            echo '</script>';
            echo '<meta http-equiv="refresh" content="0;url=../login/" />';
        }
    }
    
    else{
    	echo '<script language="javascript">';
        echo 'alert("Invalid Email Address!")';
        echo '</script>';
        echo '<meta http-equiv="refresh" content="0;url=../login/" />';
    }


?>
