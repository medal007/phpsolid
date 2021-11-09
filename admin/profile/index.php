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
$passw = $getadmin[0]['password'];


$getnotification = dbquery("SELECT * FROM ".$table5." WHERE recipient = '".dbescape("All")."' OR recipient = '".dbescape($email)."' ORDER BY id DESC LIMIT 10")->rows;
$countnotification = dbquery("SELECT * FROM ".$table5." WHERE recipient = '".dbescape("All")."' OR recipient = '".dbescape($email)."' ORDER BY id DESC LIMIT 10")->num_rows;


$currentdate = date("Y-m-d H:i:s");

$getfilestrucname = dbquery("SELECT * FROM ".$table1." WHERE id = '$psid'")->rows;
$filestrucextra = $getfilestrucname[0]['image'];

$urlactive = "";

if (isset($_FILES['file'])){
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
    //$message .= 'Error:' . $_FILES['file']['error'];
}


if($urlactive == "1"){
    $columns = "image, fullname, email, password";
    
    $_POST = \array_diff_key($_POST, ["eximage" => "xy", "dataprocess" => "xy"]);
    
    $values = "";

    foreach($_POST as $eachvalue) {
        $eachvalue = dbescape($eachvalue);
        $eachvalue = str_replace(',', ' ', $eachvalue);
        
        $eachvalue = "'". $eachvalue . "'";
        
        if($values != ""){
            
            $values = $values . ",".$eachvalue;
            
        } else{
            $values = $eachvalue;
        }
    }
    $values = "'{$url}'"."," .$values;
    $values = str_replace(",''", "", $values);
} else{
    $url = $filestrucextra;
    
    
    $columns = "image, fullname, email, password";
    
    $_POST = \array_diff_key($_POST, ["eximage" => "xy", "dataprocess" => "xy"]);
    
    $values = "";

    foreach($_POST as $eachvalue) {
        $eachvalue = dbescape($eachvalue);
        $eachvalue = str_replace(',', ' ', $eachvalue);
        
        $eachvalue = "'". $eachvalue . "'";
        
        if($values != ""){
            
            $values = $values . ",".$eachvalue;
            
        } else{
            $values = $eachvalue;
        }
    }
    
    $values = "'{$url}'"."," .$values;
    $values = str_replace(",''", "", $values);
    
}



if(isset($dataprocess)){

    $updatecols = $columns;
    $updatevals = $values;
    
    $updatecols = explode (",", $updatecols); 
    $updatevals = explode (",", $updatevals);
    
    foreach (array_combine($updatecols, $updatevals) as $updatecol => $updateval) {
            $runadd1 = dbquery("UPDATE ". $table1 . " SET $updatecol = $updateval WHERE id = '".dbescape($psid)."'");
            notify("you edited your profile information", $email);
            header('Location: '.$_SERVER['REQUEST_URI']);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $projectname ?> - Profile Page</title>
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
                            <h4 class="header-title mb-3">Profile Page</h4>
                            <!-- start  -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-0 text-center">
                                        <div class="member-card">
                                            <div class="avatar-xxl member-thumb mb-2 center-page mx-auto">
                                                <img src="<?php echo $image ?>" class="rounded-circle img-thumbnail" alt="profile-image">
                                                <i class="mdi mdi-star-circle member-star text-success" title="Dashboard User"></i>
                                            </div>
    
                                            <div class="">
                                                <h5 class="mt-3"><?php echo $fullname ?></h5>
                                                <p class="text-muted"><?php echo $email ?></p>
                                            </div>
    
                                        </div>
    
                                    </div>
                                    <!-- end card-box -->
                                    
                                    <!-- Personal-Information -->
                                    <div class="panel card panel-fill">
                                        <div class="card-header">
                                           
                                        </div>
                                        <div class="card-body">
                                            <form method="post" enctype="multipart/form-data" class="form-validation">
                                                <div class="form-group">
                                                    <label for="Upload Picture">Upload Picture</label>
                                                    <input type="file" class="filestyle" name="file" data-btnClass="btn-primary">
                                                </div>
                                                <div class="form-group">
                                                    <label for="FullName">Full Name</label>
                                                    <input name="fullname" type="text" value="<?php echo $fullname ?>" id="FullName" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Email">Email</label>
                                                    <input name="email" type="email" value="<?php echo $email ?>"  placeholder="email@example.com" id="Email" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Password">Password</label>
                                                    <input type="password" id="Password" class="form-control password">
                                                    <input type="hidden" name="password" id="encpassword" class="form-control password">
                                                </div>
                                                <input type="hidden" name="dataprocess" parsley-trigger="change" required  class="form-control" id="">
                                                <center><button type="submit" class="btn btn-success btn-lg updatebtn" title="Update">Update Profile  <i class="fas fa-user"></i></button></center>
                                            </form>

                                        </div>
                                    </div>
                                    <!-- Personal-Information -->
    
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                            <!-- end -->
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
        

        <script>
            $( document ).ready(function() {
                
               var password = $('.password').val();
               
               var passw = "<?php echo $passw; ?>";
               
               if(password != ""){
               
                   $.ajax({
                      url: './processflow.php',
                      type: 'POST',
                      dataType: 'json',
                      data: {password : password, type : "encpassword"},
                      success: function(datanow){
                            $('#encpassword').val(datanow);
                        }
                    });
                
               } else{
                   $('#encpassword').val(passw);
               }
                
                
            });
            
             $('.password').on('input',function(e){
                
               var password = $('.password').val();
               
               var passw = "<?php echo $passw; ?>";
               
               if(password != ""){
               
                   $.ajax({
                      url: './processflow.php',
                      type: 'POST',
                      dataType: 'json',
                      data: {password : password, type : "encpassword"},
                      success: function(datanow){
                            $('#encpassword').val(datanow);
                        }
                    });
                
               } else{
                   $('#encpassword').val(passw);
               }
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