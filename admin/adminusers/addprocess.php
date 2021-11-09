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

$password = trim($password);
$password = dbescape($password);
$password = md5($password);

$currentdate = date("Y-m-d H:i:s");

if (isset($_FILES['file'])){
    $url = "https://chiefexecutiveprints.com/admin/assets/images/avatar.png";
    // get details of the uploaded file
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // sanitize file-name
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // check if file has one of the following extensions
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');

    if (in_array($fileExtension, $allowedfileExtensions))
    {
      // directory in which the uploaded file will be moved
      $uploadFileDir = '../gallery/';
      $dest_path = $uploadFileDir . $newFileName;
      
      $pt = $_SERVER['REQUEST_URI']; 

      $pt = explode('/', $pt);
      
      $dpt = $pt[1];
       
      $uploadFileDi =  "./$dpt/gallery/";
      $dest_pat = $uploadFileDi . $newFileName;
      
      
      
        $repl = substr($dest_pat, 1);
        
        $basename = "https://" . $_SERVER['SERVER_NAME'];
        
   
        $url = $basename.$repl;

      if(move_uploaded_file($fileTmpPath, $dest_path)) 
      {
        $message ='File is successfully uploaded.';
      }
      else 
      {
        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
      }
    }
    else
    {
      $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
} else {
    $message = 'There is some error in the file upload. Please check the following error.<br>';
    $message .= 'Error:' . $_FILES['uploadedFile']['error'];
}

    
$runadd1 = dbquery("INSERT INTO " .$table1. " (fullname, email, password, image, role, date_created) VALUES ('".dbescape($fullname)."', '".dbescape($email)."', '".$password."', '".dbescape($url)."', '".dbescape($role)."', '".dbescape($currentdate)."')");
if($runadd1 > 0){
    notify("Welcome!😎😍, ". $fullname, $email);
    notify($adminname. " added " .$fullname, "All");
    echo '<script language="javascript">';
    echo 'alert("New Admin User Added!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../adminusers/" />';
} else{
    echo '<script language="javascript">';
    echo 'alert("Invalid Details!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../adminusers/" />';
}
?>