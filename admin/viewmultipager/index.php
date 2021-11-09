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
    
$id = 1;

$getconfig = dbquery("SELECT * FROM ".$table2." WHERE id = '".dbescape($id)."'")->rows;
$projectname = $getconfig[0]['projectname'];
$icon = $getconfig[0]['icon'];
$background = $getconfig[0]['background'];
$copyright = $getconfig[0]['copyright'];

$psid = $_SESSION['phpsolid_id'];
$getadmin = dbquery("SELECT * FROM ".$table1." WHERE id = '".dbescape($psid)."'")->rows;

$image = $getadmin[0]['image'];
$fullname = $getadmin[0]['fullname'];
$email = $getadmin[0]['email'];


$getnotification = dbquery("SELECT * FROM ".$table5." WHERE recipient = '".dbescape("All")."' OR recipient = '".dbescape($email)."' ORDER BY id DESC LIMIT 10")->rows;
$countnotification = dbquery("SELECT * FROM ".$table5." WHERE recipient = '".dbescape("All")."' OR recipient = '".dbescape($email)."' ORDER BY id DESC LIMIT 10")->num_rows;


$request_uri = $_SERVER['REQUEST_URI'];
// echo $request_uri;

$whole = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = rtrim($request_uri, '/');
$url = filter_var($request_uri, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$pageid = (string) $url[3];
$getpage = dbquery("SELECT * FROM ".$table3." WHERE id = '".dbescape($pageid)."'")->rows;
$pgname = $getpage[0]['pagename'];

$pgename = str_replace(' ', '_', $pgname);
$pgename = strtolower($pgename);

$table7 = TB_PRE . $pgename."_usergen";

$currentdate = date("Y-m-d H:i:s");

$columns = "";
    
$getcol = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' AND structype != 'PHP Code' ORDER BY strucnumber ASC")->rows;

foreach($getcol as $col){
    
    $strucname = $col['strucname'];
    $dbfieldname = dbescape($strucname);
    $dbfieldname = strtolower($dbfieldname);
    $dbfieldname = str_replace(' ', '_', $dbfieldname);
    
    if($columns != ""){
        
        $columns = $columns . ",".$dbfieldname;
        
    } else{
        $columns = $dbfieldname;
    }
    
}


$editcolumns = "";
    
$getcol = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' AND structype != 'PHP Code' ORDER BY strucnumber ASC")->rows;

foreach($getcol as $col){
    
    $strucname = $col['strucname'];
    $dbfieldname = dbescape($strucname);
    $dbfieldname = strtolower($dbfieldname);
    $dbfieldname = str_replace(' ', '_', $dbfieldname);
    
    if($editcolumns != ""){
        
        $editcolumns = $editcolumns . ",".$dbfieldname;
        
    } else{
        $editcolumns = $dbfieldname;
    }
    
}


$valuesadd = "";

foreach($_POST as $eachvalue) {
    $eachvalue = dbescape($eachvalue);
    $eachvalue = str_replace(',', ' ', $eachvalue);
    
    $eachvalue = "'". $eachvalue . "'";
    
    if($valuesadd != ""){
        
        $valuesadd = $valuesadd . ",".$eachvalue;
        
    } else{
        $valuesadd = $eachvalue;
    }
}


$values = "";
$isFirst = true;

foreach($_POST as $eachvalue) {
    
    if ($isFirst) {
        $isFirst = false;
        continue;
    }
    
    $eachvalue = dbescape($eachvalue);
    $eachvalue = str_replace(',', ' ', $eachvalue);
    
    $eachvalue = "'". $eachvalue . "'";
    
    if($values != ""){
        
        $values = $values . ",".$eachvalue;
        
    } else{
        $values = $eachvalue;
    }
}

$values = str_replace(",''", "", $values);

$getfilestrucname = dbquery("SELECT * FROM ".$table4." WHERE structype = 'Select File' AND pageid = '$pageid' ORDER BY id DESC LIMIT 1")->rows;
$filestrucname = $getfilestrucname[0]['strucname'];
$filestrucno = $getfilestrucname[0]['strucnumber'];
$filestrucextra = $getfilestrucname[0]['strucextra'];
$filestructype = $getfilestrucname[0]['structype'];
$filefieldname = dbescape($filestrucname);
$filefieldname = strtolower($filefieldname);
$filefieldname = str_replace(' ', '_', $filefieldname);


//print_r($filestrucno);
//print_r($columns);

$urlactive = "";

if (isset($_FILES[$filefieldname])){
    $url = $filestrucextra;
    // get details of the uploaded file
    $fileTmpPath = $_FILES[$filefieldname]['tmp_name'];
    $fileName = $_FILES[$filefieldname]['name'];
    $fileSize = $_FILES[$filefieldname]['size'];
    $fileType = $_FILES[$filefieldname]['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // sanitize file-name
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // check if file has one of the following extensions
    $allowedfileExtensions = array('jpg', 'jpeg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc', 'docx', 'pdf');

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
        $urlactive = "1";
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

if($filestrucno == "1" && $filestructype == "Select File"){
    $columns = str_replace($filefieldname .",", '', $columns);
} else if($filestrucno != "1" && $filestructype == "Select File"){
    $columns = str_replace("," . $filefieldname, '', $columns);
}


$valuesadd = str_replace("''", "", $valuesadd);

$valuesadd = $valuesadd . "'{$currentdate}'";

$valuesadd = $valuesadd . ",'0'";
if($filestructype == "Select File"){
    $valuesadd = $valuesadd . ",'something'";
}
$columns = $columns. ",date_created";
$columns = $columns. ",pageres";
if($filestructype == "Select File"){
    $columns = $columns. ",$filefieldname";
}

$updatecols = $editcolumns;
$updatevals = $values;

$updatecols = explode (",", $updatecols); 
$updatevals = explode (",", $updatevals); 


if(isset($addprocess)){
    // print_r($columns);
    // echo "<br>";
    // print_r($valuesadd);
    
    $runadd1 = dbquery("INSERT INTO " .$table7. " (".$columns.") VALUES (".$valuesadd.")");
    if($runadd1 > 0){
        if($getfilestrucname > 0 && $filestructype == "Select File"){
            $getlastinsert = dbquery("SELECT * FROM ".$table7." ORDER BY id DESC LIMIT 1")->rows;
            $lastinsertid = $getlastinsert[0]['id'];
            $runadd1 = dbquery("UPDATE ". $table7 . " SET $filefieldname = '".dbescape($url)."' WHERE id = '".dbescape($lastinsertid)."'");
        }
        notify($fullname. " added a new data to page: " .$pgname, "All");
        // echo '<script language="javascript">';
        // echo 'alert("New Data Added Into '.$pgname.' Page!")';
        // echo '</script>';
        header('Location: '.$_SERVER['REQUEST_URI']);
    }
} else if(isset($editprocess)){
    // print_r($updatecols);
    // echo "<br>";
    // print_r($updatevals); 
    // echo "<br>";
    //print_r($urlactive); 
    foreach (array_combine($updatecols, $updatevals) as $updatecol => $updateval) {
        $runadd1 = dbquery("UPDATE ". $table7 . " SET $updatecol = $updateval WHERE id = '".dbescape($editid)."'");
    }
    if($urlactive == "1"){
        $runadd1 = dbquery("UPDATE ". $table7 . " SET $filefieldname = '".dbescape($url)."' WHERE id = '".dbescape($editid)."'");
    }
    notify($fullname. " edited data in page: " .$pgname, "All");
    header('Location: '.$_SERVER['REQUEST_URI']);
    
} else if(isset($delprocess)){
    $runadd1 = dbquery("DELETE FROM " .$table7. " WHERE id='".dbescape($pagedelid)."'");
    if($runadd1 > 0){
         notify($fullname. " delete data in page: " .$pgname, "All");
         header('Location: '.$_SERVER['REQUEST_URI']);
    }
}
    
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $projectname ?> - <?php echo $pgname ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="<?php echo $projectname ?> - Admin Dashboard" name="description" />
        <meta content="<?php echo $projectname ?>" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo $icon ?>">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
      
        <!-- third party css -->
        <link href="../assets/libs/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
        <link href="../assets/libs/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
        <link href="../assets/libs/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
        <link href="../assets/libs/select.bootstrap4.css" rel="stylesheet" type="text/css" />
        <link href="../assets/libs/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/libs/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="../assets/libs/switchery.min.css" rel="stylesheet" type="text/css" />
         <link href="../assets/libs/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
         <link href="../assets/libs/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/libs/bootstrap-datepicker.css" rel="stylesheet">
        <link href="../assets/libs/daterangepicker.css" rel="stylesheet">
        <link href="../assets/libs/bootstrap-clockpicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- CodeMirror -->
          <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/codemirror/codemirror.css">
          <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/codemirror/theme/monokai.css">
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.5.95/css/materialdesignicons.min.css" integrity="sha512-EhtFgx6fGa2B3UNje2rTcPBgryWKx7TVkcGuOsCkybAbAaWEGrWDjsMFPqJUwXf2u1qmz6BxocZvcXVmTfMG9g==" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css'><link rel="stylesheet" href="../assets/css/selstyle.css">
    </head>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            
            <?php include '../header/index.php'; ?>

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start container-fluid -->
                    <div class="container-fluid">

                        <h4 class="header-title mb-3"></h4>
                        
                        <div class="mt-5">
                            <h4 class="header-title mb-3"><?php echo $pgname ?> List &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal">Add <?php echo $pgname ?> <i class="fas fa-plus"></i></button></h4>
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <?php
                                            $getval = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' AND strucactive = 'yes' ORDER BY strucnumber ASC")->rows;
                                            foreach($getval as $val){
                                                $strucname = $val['strucname'];
                                        ?>
                                        <th><?php echo $strucname ?></th>
                                        
                                        <?php } ?>
                                        
                                        <th>Date</th>
                                        <th>Actions</th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                        $getdata = dbquery("SELECT * FROM ".$table7." ORDER BY id DESC")->rows;
                                        foreach($getdata as $data){
                                            
                                    ?>
                                    <tr>
                                        <?php
                                            $getval = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' AND strucactive = 'yes' ORDER BY strucnumber ASC")->rows;
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
                                        ?>
                                        
                                            <?php if($structype == "Select File"){?>
                                            
                                                <td><img height="50px" width="50px" src="<?php echo $varr ?>"></td>
                                                
                                                
                                            <?php } else if($structype == "Relationship"){
                                                $getrel = dbquery("SELECT * FROM ".$firstext." WHERE id = '$varr'")->rows;
                                                $varr = $getrel[0][$secondext];
                                            ?>
                                            
                                                <td><?php echo $varr ?></td>
                                                
                                            <?php } else if($structype == "Tags Input"){?>
                                            
                                                <td><?php echo str_replace(' ', ',', $varr) ?></td>
                                                
                                            <?php } else if($structype == "Text Area" || $structype == "Editor"){?>
                                            
                                                <td>
                                                    <?php
                                                        if (strpos($varr, '</p>') !== false) {
                                                    ?>
                                                        <iframe  height="20"><?php echo shorten($varr, 2000) ?></iframe>
                                                        <br>
                                                    <?php } else { ?>
                                                        <iframe  height="20"><p><?php echo shorten($varr, 2000) ?></p></iframe>
                                                        <br>
                                                    <?php } ?>
                                                    <button type="button" class="btn btn-warning btn-sm viewbtn" data-id="<?php echo $varrid . ",".$dbfieldname . "," . $table7?>" title="View Content">View Content <i class="fas fa-eye"></i></button>
                                                </td>
                                                
                                            
                                            <?php } else { ?>
                                            
                                                <td><?php echo $varr ?></td>
                                            
                                            <?php } ?>
                                            
                                        <?php } ?>
                                        <td><?php echo $varrago ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm editbtn" data-id="<?php echo $varrid ?>" title="Edit"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm delbtn" data-id="<?php echo $varrid ?>" title="Delete "><i class="fas fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>


                    </div>
                    <!-- end container-fluid -->

                    

                    <!-- Footer Start -->
                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo date('Y'); ?> &copy; <?php echo $projectname ?> | PHPSOLID CMS DASHBOARD by <a href="https://medaltechie.com">MedalTechie</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- end Footer -->

                </div>
                <!-- end content -->

            </div>
            <!-- END content-page -->

        </div>
        <!-- END wrapper -->
        
        
         <!-- sample modal content -->
        <div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add <?php echo $pgname ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <?php 
                                $getstructure = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' ORDER BY strucnumber ASC")->rows;
                                foreach($getstructure as $structure){
                                    $sid = $structure['id'];
                                    $strucname = $structure['strucname'];
                                    $lowerstrucname = strtolower($strucname);
                                    $lowerstrucname = str_replace(' ', '_', $lowerstrucname);
                                    
                                    $strucnumber = $structure['strucnumber'];
                                    $structype = $structure['structype'];
                                    $strucactive = $structure['strucactive'];
                                    $lowerstructype = strtolower($structype);
                                    $strucextra = $structure['strucextra'];
                                    $lowerstrucextra = strtolower($strucextra);
                                    $splt = explode(",",$lowerstrucextra);
                                    $listextra = explode(",",$strucextra);
                                    
                                    $typenme = $splt[0];
                                    $iconn = $splt[1]; 
                                    
                                    $firstextra = $listextra[0];
                                    $secondextra = $listextra[1];
                                    $thirdextra = $listextra[2]; 
                                    $fourthextra = $listextra[3]; 
                                    $fifthextra = $listextra[4];
                                    $sixthextra = $listextra[5];
                                    
                                    $date = $structure['date_created'];
                                    $ago = getdiff($date);
                            ?>
                            
                            <div class="row">

                                <div class="col-lg-12">
                                    <?php if($structype == "Text" || $structype == "Email" || $structype == "Number" || $structype == "Phone" || $structype == "Address" || $structype == "City" || $structype == "URL"){ ?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="<?php echo $lowerstructype ?>" class="form-control" placeholder="<?php echo $strucname ?>">
                                    
                                    <?php } else if($structype == "Password"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="<?php echo $lowerstructype ?>" class="form-control password" placeholder="<?php echo $strucname ?>">
                                        <input type="hidden" name="<?php echo $lowerstrucname ?>" parsley-trigger="change" required  class="form-control" id="encpassword">
                                        
                                    <?php } else if($structype == "Text Area"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <textarea name="<?php echo $lowerstrucname ?>" class="form-control" rows="5" id="<?php echo $lowerstrucname ?>" placeholder="<?php echo $strucname ?>"></textarea>
                                    <?php } else if($structype == "Default Time Picker"){?>
                                        <div class="input-group">
                                            <input id="timepicker" name="<?php echo $lowerstrucname ?>" type="text" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "24Hrs Time Picker"){?>
                                        <div class="input-group">
                                            <input id="timepicker2" name="<?php echo $lowerstrucname ?>" type="text" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "Color Picker"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control" id="default-colorpicker" value="#8fff00">
                                    <?php } else if($structype == "RGBA Color Picker"){?>
                                        <div id="component-colorpicker" class="input-group" title="Using format option">
                                            <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control input-lg" value="#188ae2" />
                                            <span class="input-group-append">
                                                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                            </span>
                                        </div>
                                        
                                    <?php } else if($structype == "Date Picker"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="date" name="<?php echo $lowerstrucname ?>" class="form-control" placeholder="mm/dd/yyyy" id="datepicker">
                                    <?php } else if($structype == "Clock Picker"){?>
                                        <div class="input-group clockpicker mb-3">
                                            <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control" value="09:30">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "Editor"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <textarea class="summernote" id="summernote" name="<?php echo $lowerstrucname ?>"></textarea>
                                        
                                    <?php } else if($structype == "Money"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                         <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="number" class="form-control" placeholder="<?php echo $strucname ?>">
                                        
                                    <?php } else if($structype == "Select File"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="file" class="filestyle" name="<?php echo $lowerstrucname ?>" data-btnClass="btn-primary">
                                        
                                    <?php } else if($structype == "Tags Input"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="text" name="<?php echo $lowerstrucname ?>" value="" data-role="tagsinput" placeholder="Add <?php echo $strucname ?>" />
                                        
                                    <?php } else if($structype == "Icon Inputs"){?>
                                        <div class="input-group">
                                            <div class="input-group-prepend"> 
                                                <span class="input-group-text"><i class="<?php echo $iconn ?>"></i></span>
                                            </div>
                                            <input type="<?php echo $typenme ?>" id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control" placeholder="<?php echo $strucname ?>">
                                        </div>
                                        
                                    <?php } else if($structype == "Input Select"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                            <?php for ($i = 0; $i < count($listextra); $i++){?>
                                                <option value="<?php echo $listextra[$i] ?>"><?php echo $listextra[$i] ?></option>
                                            <?php }?>
                                        </select>
                                        
                                    <?php } else if($structype == "Disabled"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="text" class="form-control" readonly value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "Enabled"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="text" class="form-control" value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "PHP Code"){?>
                                      <?php eval($strucextra); ?>
                                      
                                    <?php } else if($structype == "Hidden"){?>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="hidden" class="form-control" value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "Toggle Button"){?>
                                        <div class="custom-control custom-switch">
                                            <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?php echo $lowerstrucname ?>" type="checkbox" class="custom-control-input" id="<?php echo $lowerstrucname ?>">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="custom-control-label" for="<?php echo $lowerstrucname ?>"><strong><?php echo $strucname ?></strong></label>
                                        </div>
                                        
                                    <?php } else if($structype == "Inline Checkboxes"){?>
                                    
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <?php for ($i = 0; $i < count($listextra); $i++){?>
                                            <div class="checkbox form-check-inline">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?php echo $lowerstrucname ?>" type="checkbox" id="<?php echo $listextra[$i] ?>" value="<?php echo $listextra[$i] ?>">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="<?php echo $listextra[$i] ?>"> <?php echo $listextra[$i] ?> </label>
                                            </div>
                                        <?php }?>
                                        
                                    <?php } else if($structype == "Inline Radios"){?>
                                    
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <?php for ($i = 0; $i < count($listextra); $i++){?>
                                            <div class="radio radio-info form-check-inline">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="<?php echo $listextra[$i] ?>" value="<?php echo $listextra[$i] ?>" name="<?php echo $lowerstrucname ?>" checked>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="<?php echo $listextra[$i] ?>"> <?php echo $listextra[$i] ?> </label>
                                            </div>
                                        <?php }?>
                                        
                                    <?php } else if($structype == "Relationship"){?>
                                    
                                        <?php if($fourthextra == "0" && $fifthextra == "no filter"){ ?>
                                        
                                            <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." ORDER BY id " .$thirdextra."")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>
                                            
                                        <?php } else if($fourthextra != "0" && $fifthextra == "no filter"){?>
                                        
                                            <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." ORDER BY id " .$thirdextra." LIMIT ".$fourthextra. "")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>

                                        <?php } else if($fourthextra == "0" && $fifthextra != "no filter"){?>
                                        
                                            <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." WHERE $fifthextra='$sixthextra' ORDER BY id " .$thirdextra."")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>

                                        <?php } else if($fourthextra != "0" && $fifthextra != "no filter"){?>
                                        
                                            <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." WHERE $fifthextra='$sixthextra' ORDER BY id " .$thirdextra." LIMIT ".$fourthextra. "")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>
                                        
                                        <?php } ?>
                                        
                                    <?php }?>
                                </div>
                                
                            </div>
                            
                            <br>
                            
                            <?php } ?>
                            
                            <input type="hidden" name="addprocess" parsley-trigger="change" required  class="form-control" id="">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light add-btn">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </form>
        </div>
        <!-- /.modal -->
        
        
        <!-- sample modal content -->
        <div id="viewModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">View More Content</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                       <td><p id="viewContent"></p></td>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </form>
        </div>
        <!-- /.modal -->
        
        
        <!-- sample modal content -->
        <div id="editModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Edit <?php echo $pgname ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="editid" parsley-trigger="change" required  class="form-control" id="editvarrid">
                       <?php 
                                $getstructure = dbquery("SELECT * FROM ".$table4." WHERE pageid = '$pageid' ORDER BY strucnumber ASC")->rows;
                                foreach($getstructure as $structure){
                                    $sid = $structure['id'];
                                    $strucname = $structure['strucname'];
                                    $lowerstrucname = strtolower($strucname);
                                    $lowerstrucname = str_replace(' ', '_', $lowerstrucname);
                                    
                                    $strucnumber = $structure['strucnumber'];
                                    $structype = $structure['structype'];
                                    $strucactive = $structure['strucactive'];
                                    $lowerstructype = strtolower($structype);
                                    $strucextra = $structure['strucextra'];
                                    $lowerstrucextra = strtolower($strucextra);
                                    $splt = explode(",",$lowerstrucextra);
                                    $listextra = explode(",",$strucextra);
                                    
                                    $typenme = $splt[0];
                                    $iconn = $splt[1]; 
                                    
                                    $firstextra = $listextra[0];
                                    $secondextra = $listextra[1];
                                    $thirdextra = $listextra[2]; 
                                    $fourthextra = $listextra[3];
                                    $fifthextra = $listextra[4];
                                    $sixthextra = $listextra[5];
                                    
                                    $date = $structure['date_created'];
                                    $ago = getdiff($date);
                            ?>
                            
                            <div class="row">

                                <div class="col-lg-12">
                                    <?php if($structype == "Text" || $structype == "Email" || $structype == "Number" || $structype == "Phone" || $structype == "Address" || $structype == "City" || $structype == "URL"){ ?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="<?php echo $lowerstructype ?>" class="form-control edit<?php echo $lowerstrucname ?>" placeholder="<?php echo $strucname ?>">
                                    
                                    <?php } else if($structype == "Password"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="edit<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="<?php echo $lowerstructype ?>" class="form-control password edit<?php echo $lowerstrucname ?>" placeholder="<?php echo $strucname ?>">
                                        <input type="hidden" name="<?php echo $lowerstrucname ?>" parsley-trigger="change" required  class="form-control" id="editencpassword">
                                        
                                    <?php } else if($structype == "Text Area"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <textarea name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>" rows="5" id="edit<?php echo $lowerstrucname ?>" placeholder="<?php echo $strucname ?>"></textarea>
                                    <?php } else if($structype == "Default Time Picker"){?>
                                        <div class="input-group">
                                            <input id="timepicker" name="<?php echo $lowerstrucname ?>" type="text" class="form-control edit<?php echo $lowerstrucname ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "24Hrs Time Picker"){?>
                                        <div class="input-group">
                                            <input id="timepicker2" name="<?php echo $lowerstrucname ?>" type="text" class="form-control edit<?php echo $lowerstrucname ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "Color Picker"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>" id="default-colorpicker" value="#8fff00">
                                    <?php } else if($structype == "RGBA Color Picker"){?>
                                        <div id="component-colorpicker" class="input-group" title="Using format option">
                                            <input id="edit<?php echo $lowerstrucname ?>" type="text" name="<?php echo $lowerstrucname ?>" class="form-control input-lg edit<?php echo $lowerstrucname ?>" value="#188ae2" />
                                            <span class="input-group-append">
                                                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                            </span>
                                        </div>
                                        
                                    <?php } else if($structype == "Date Picker"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="date" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>" placeholder="mm/dd/yyyy" id="datepicker">
                                    <?php } else if($structype == "Clock Picker"){?>
                                        <div class="input-group clockpicker mb-3">
                                            <input id="edit<?php echo $lowerstrucname ?>" type="text" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>" value="09:30">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "Editor"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <textarea class="summernote edit<?php echo $lowerstrucname ?>" id="editsummernote" name="<?php echo $lowerstrucname ?>"></textarea>
                                        
                                    <?php } else if($structype == "Money"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                         <input id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="number" class="form-control edit<?php echo $lowerstrucname ?>" placeholder="<?php echo $strucname ?>">
                                        
                                    <?php } else if($structype == "Select File"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input type="file" class="filestyle" name="<?php echo $lowerstrucname ?>" data-btnClass="btn-primary">
                                        <input id="edit<?php echo $lowerstrucname ?>" name="ex<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="hidden" class="form-control edit<?php echo $lowerstrucname ?>">
                                        
                                    <?php } else if($structype == "Tags Input"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="edit<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>" type="text" name="<?php echo $lowerstrucname ?>" value="" data-role="tagsinput" placeholder="Add <?php echo $strucname ?>" />
                                        
                                    <?php } else if($structype == "Icon Inputs"){?>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="<?php echo $iconn ?>"></i></span>
                                            </div>
                                            <input type="<?php echo $typenme ?>" id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>" placeholder="<?php echo $strucname ?>">
                                        </div>
                                        
                                    <?php } else if($structype == "Input Select"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <select id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>">
                                            <?php for ($i = 0; $i < count($listextra); $i++){?>
                                                <option value="<?php echo $listextra[$i] ?>"><?php echo $listextra[$i] ?></option>
                                            <?php }?>
                                        </select>
                                        
                                    <?php } else if($structype == "Disabled"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="text" class="form-control edit<?php echo $lowerstrucname ?>" readonly value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "Enabled"){?>
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <input id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="text" class="form-control edit<?php echo $lowerstrucname ?>" value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "PHP Code"){?>
                                      <?php eval($strucextra); ?>
                                      
                                    <?php } else if($structype == "Hidden"){?>
                                        <input id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="hidden" class="form-control edit<?php echo $lowerstrucname ?>" value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "Toggle Button"){?>
                                        <div class="custom-control custom-switch">
                                            <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="<?php echo $lowerstrucname ?>" type="checkbox" class="custom-control-input" id="edit<?php echo $lowerstrucname ?>">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="custom-control-label edit<?php echo $lowerstrucname ?>" for="<?php echo $lowerstrucname ?>"><strong><?php echo $strucname ?></strong></label>
                                        </div>
                                        
                                    <?php } else if($structype == "Inline Checkboxes"){?>
                                    
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <?php for ($i = 0; $i < count($listextra); $i++){?>
                                            <div class="checkbox form-check-inline">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-control edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="checkbox" id="edit<?php echo $listextra[$i] ?>" value="<?php echo $listextra[$i] ?>">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="edit<?php echo $listextra[$i] ?>"> <?php echo $listextra[$i] ?> </label>
                                            </div>
                                        <?php }?>
                                        
                                    <?php } else if($structype == "Inline Radios"){?>
                                    
                                        <h5 class="font-14"><?php echo $strucname ?><span class="text-danger">*</span></h5>
                                        <?php for ($i = 0; $i < count($listextra); $i++){?>
                                            <div class="radio radio-info form-check-inline">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-control edit<?php echo $lowerstrucname ?>" type="radio" id="edit<?php echo $listextra[$i] ?>" value="<?php echo $listextra[$i] ?>" name="<?php echo $lowerstrucname ?>" checked>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="edit<?php echo $listextra[$i] ?>"> <?php echo $listextra[$i] ?> </label>
                                            </div>
                                        <?php }?>
                                        
                                    <?php } else if($structype == "Relationship"){?>
                                    
                                        <?php if($fourthextra == "0" && $fifthextra == "no filter"){ ?>
                                        
                                            <select id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." ORDER BY id " .$thirdextra."")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>
                                            
                                        <?php } else if($fourthextra != "0" && $fifthextra == "no filter"){?>
                                        
                                            <select id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." ORDER BY id " .$thirdextra." LIMIT ".$fourthextra. "")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>

                                        <?php } else if($fourthextra == "0" && $fifthextra != "no filter"){?>
                                        
                                            <select id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." WHERE $fifthextra='$sixthextra' ORDER BY id " .$thirdextra."")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>

                                        <?php } else if($fourthextra != "0" && $fifthextra != "no filter"){?>
                                        
                                            <select id="edit<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control edit<?php echo $lowerstrucname ?>">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." WHERE $fifthextra='$sixthextra' ORDER BY id " .$thirdextra." LIMIT ".$fourthextra. "")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                        $rid = $rel['id'];
                                                ?>
                                                    <option value="<?php echo $rid ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>
                                        
                                        <?php } ?>
                                        
                                    <?php }?>
                                </div>
                                
                            </div>
                            
                            <br>
                            
                            <?php } ?>
                            
                            <input type="hidden" name="editprocess" parsley-trigger="change" required  class="form-control" id="">
                            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light edit-btn">Update</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </form>
        </div>
        <!-- /.modal -->
        
        
        <!-- sample modal content -->
        <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Delete Admin User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <center>
                        <h5 class="font-14"><span class="text-danger">Are you sure you want to delete this Admin User?.</span></h5>
                        <h5 class="font-14"><span class="text-danger">It may affect some data related to it.</span></h5>
                        </center>
                    </div>
                    <input type="hidden" name="pagedelid" parsley-trigger="change" required  class="form-control" id="delpageid">
                    <input type="hidden" name="delprocess" parsley-trigger="change" required  class="form-control" id="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light delete-btn">Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </form>
        </div>
        <!-- /.modal -->
        

        <script>
        
        
            $(document).on("click", ".viewbtn", function () {
                 var jointval = $(this).data('id');
                 var jointvalarray = jointval.split(',');
                 
                 var getid = jointvalarray[0];
                 var toget = jointvalarray[1];
                 var getdb = jointvalarray[2];
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {getid : getid, toget : toget, getdb : getdb, type : "viewbtn"},
                  success: function(datanow){
                        $('#viewContent').html(datanow)
                         $('#viewModal').modal('show');
                    }
                });
                 
                 
            });
            
            
            $(document).on("click", ".editbtn", function () {
                 var varrId = $(this).data('id');
                 var table7 = "<?php echo $table7; ?>";
                 var pageid = "<?php echo $pageid; ?>";
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {varrId : varrId, table7 : table7, pageid : pageid, type : "edit"},
                  success: function(datanow){
                      
                      $('#editvarrid').val(varrId);
                        
                      var len = datanow.length;
                      
                      for(var i=0; i<len; i++){

                          var code = datanow[i].code;
                          
                          var content = datanow[i].content;
                          
                          var type = datanow[i].type;
                          var extra = datanow[i].extra;
                          
                          if(type == "Editor"){
                              $('.edit'+ code).summernote("code", content);
                          } else if(type == "Text Area"){
                              $('.edit'+ code).text(content);
                          } else if(type == "Inline Checkboxes"){
                                $('.edit'+ code).each(function (){
                           			var checkit = $(this).is(':checked'); 
                                
                                    if(checkit == true){
                                    	$(this).prop('checked', false);
                                    } 
                                 });
                              $('#edit'+ content).prop('checked', true);

                          } else if(type == "Inline Radios"){
                              $('#edit'+ content).prop('checked', true);
                              
                          } else if(type == "Tags Input"){
                            newcontent = content.replaceAll(" ", ",");
                            
                            $('.edit'+ code).tagsinput('removeAll');
                            
                            var conArray = newcontent.split(",");
                            
                            var tagAction = $('.edit'+ code);
                            
                            conArray.forEach(function (item,index) {
                                tagAction.tagsinput('add', item);
                              })
                          } else {
                              $('.edit'+ code).val(content);
                          }
                      
                      }
                      
                      if(type == "Relationship" || type == "Input Select"){
                         var selopts = document.getElementsByClassName('edit'+ code)[0].options;
                         console.log(selopts);
                         for(var i = 0; i < selopts.length; i++) {
                            if(selopts[i].value == content) {
                               var indexopt = i;
                               document.getElementsByClassName('edit'+ code)[0].selectedIndex = indexopt;
                               break;
                            }
                         }
                          
                      }
        
                     $('#editModal').modal('show');
                         
                    }
                });
            });
            
            
            
            $(document).on("click", ".delbtn", function () {
                 var varrId = $(this).data('id');
                 var table7 = "<?php echo $table7; ?>";
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {varrId : varrId, table7 : table7, type : "view"},
                  success: function(datanow){
                    var pagedelid = datanow[0].id;
                    $('#delpageid').val(pagedelid);
                    $('#deleteModal').modal('show');
                    }
                });
                 
            });
            
            $('.password').on('input',function(e){
                
               var password = $('.password').val(); 
               
               $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {password : password, type : "encpassword"},
                  success: function(datanow){
                        $('#encpassword').val(datanow);
                    }
                });
            });
            
            
             $('.password').on('input',function(e){
                
               var password = $('.password').val(); 
               
               $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {password : password, type : "encpassword"},
                  success: function(datanow){
                        $('#editencpassword').val(datanow);
                    }
                });
            });
            
        </script>

        <script src='https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/js-yaml/3.6.0/js-yaml.min.js'></script><script  src="../assets/js/selscript.js"></script>

        <!-- Vendor js -->
        <script src="../assets/js/vendor.min.js"></script>

        <!-- Required datatable js -->
        <script src="../assets/libs/jquery.dataTables.min.js"></script>
        <script src="../assets/libs/dataTables.bootstrap4.min.js"></script>

        <!-- Buttons examples -->
        <script src="../assets/libs/dataTables.buttons.min.js"></script>
        <script src="../assets/libs/buttons.bootstrap4.min.js"></script>
        <script src="../assets/libs/dataTables.keyTable.min.js"></script>
        <script src="../assets/libs/dataTables.select.min.js"></script>
        <script src="../assets/libs/select2.min.js"></script>
        <script src="../assets/libs/parsley.min.js"></script>
        <script src="../assets/libs/jszip.min.js"></script>
        <script src="../assets/libs/pdfmake.min.js"></script>
        <script src="../assets/libs/vfs_fonts.js"></script>
        <script src="../assets/libs/buttons.html5.min.js"></script>
        <script src="../assets/libs/buttons.print.min.js"></script>
        <script src="../assets/libs/bootstrap-tagsinput.min.js"></script>
        <script src="../assets/libs/switchery.min.js"></script>
        <script src="../assets/libs/moment.min.js"></script>
        <script src="../assets/libs/bootstrap-filestyle.min.js"></script>
        <script src="../assets/libs/bootstrap-timepicker.min.js"></script>
        <script src="../assets/libs/bootstrap-colorpicker.min.js"></script>
        <script src="../assets/libs/bootstrap-datepicker.min.js"></script>
        <script src="../assets/libs/daterangepicker.js"></script>
        <script src="../assets/libs/bootstrap-clockpicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js" integrity="sha512-ZESy0bnJYbtgTNGlAD+C2hIZCt4jKGF41T5jZnIXy4oP8CQqcrBGWyxNP16z70z/5Xy6TS/nUZ026WmvOcjNIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Responsive examples -->
        <script src="../assets/libs/dataTables.responsive.min.js"></script>
        <script src="../assets/libs/responsive.bootstrap4.min.js"></script>

        <!-- Datatables init -->
        <script src="../assets/js/datatables.init.js"></script>
        <script src="../assets/libs/form-advanced.init.js"></script>
        
        <!-- CodeMirror -->
        <script src="https://adminlte.io/themes/v3/plugins/codemirror/codemirror.js"></script>
        <script src="https://adminlte.io/themes/v3/plugins/codemirror/mode/css/css.js"></script>
        <script src="https://adminlte.io/themes/v3/plugins/codemirror/mode/xml/xml.js"></script>
        <script src="https://adminlte.io/themes/v3/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
        
        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>

    </body>
</html>