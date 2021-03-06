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

$dicon1 = $getconfig[0]['dicon1'];
$dicon2 = $getconfig[0]['dicon2'];
$dicon3 = $getconfig[0]['dicon3'];
$dicon4 = $getconfig[0]['dicon4'];
$dtable = $getconfig[0]['dtable'];

$psid = $_SESSION['phpsolid_id'];
$getadmin = dbquery("SELECT * FROM ".$table1." WHERE id = '".dbescape($psid)."'")->rows;

$image = $getadmin[0]['image'];
$fullname = $getadmin[0]['fullname'];
$email = $getadmin[0]['email'];


$getnotification = dbquery("SELECT * FROM ".$table5." WHERE recipient = '".dbescape("All")."' OR recipient = '".dbescape($email)."' ORDER BY id DESC LIMIT 10")->rows;
$countnotification = dbquery("SELECT * FROM ".$table5." WHERE recipient = '".dbescape("All")."' OR recipient = '".dbescape($email)."' ORDER BY id DESC LIMIT 10")->num_rows;
    
?>


<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8" />
        <title><?php echo $projectname ?> - Settings Page</title>
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

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <h4 class="header-title mb-3"><?php echo $projectname ?> Admin Dashboard</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="card-box widget-inline">
                                        <div class="row">
                                            <?php 
                                                $spltdicon = explode(",", $dicon1);
                                                $icon = $spltdicon[0];
                                                $counttable = $spltdicon[1];
                                                $countit = dbquery("SELECT COUNT(id) as total FROM ".$counttable."")->rows;
                                                $counttotal = $countit[0]['total'];
                                                $tag = $spltdicon[2];
                                            ?>
                                            <div class="col-xl-3 col-sm-6 widget-inline-box">
                                                <div class="text-center p-3">
                                                    <h2 class="mt-2"><i class="text-primary <?php echo $icon ?> fa-3"></i> <b><?php echo $counttotal ?></b></h2>
                                                    <p class="text-muted mb-0"><?php echo $tag ?></p>
                                                </div>
                                            </div>
                                            
                                            <?php 
                                                $spltdicon = explode(",", $dicon2);
                                                $icon = $spltdicon[0];
                                                $counttable = $spltdicon[1];
                                                $countit = dbquery("SELECT COUNT(id) as total FROM ".$counttable."")->rows;
                                                $counttotal = $countit[0]['total'];
                                                $tag = $spltdicon[2];
                                            ?>

                                            <div class="col-xl-3 col-sm-6 widget-inline-box">
                                                <div class="text-center p-3">
                                                    <h2 class="mt-2"><i class="text-teal <?php echo $icon ?> fa-3"></i> <b><?php echo $counttotal ?></b></h2>
                                                    <p class="text-muted mb-0"><?php echo $tag ?></p>
                                                </div>
                                            </div>
                                            
                                            <?php 
                                                $spltdicon = explode(",", $dicon3);
                                                $icon = $spltdicon[0];
                                                $counttable = $spltdicon[1];
                                                $countit = dbquery("SELECT COUNT(id) as total FROM ".$counttable."")->rows;
                                                $counttotal = $countit[0]['total'];
                                                $tag = $spltdicon[2];
                                            ?>

                                            <div class="col-xl-3 col-sm-6 widget-inline-box">
                                                <div class="text-center p-3">
                                                    <h2 class="mt-2"><i class="text-info <?php echo $icon ?> fa-3"></i> <b><?php echo $counttotal ?></b></h2>
                                                    <p class="text-muted mb-0"><?php echo $tag ?></p>
                                                </div>
                                            </div>
                                            
                                            <?php 
                                                $spltdicon = explode(",", $dicon4);
                                                $icon = $spltdicon[0];
                                                $counttable = $spltdicon[1];
                                                $countit = dbquery("SELECT COUNT(id) as total FROM ".$counttable."")->rows;
                                                $counttotal = $countit[0]['total'];
                                                $tag = $spltdicon[2];
                                            ?>

                                            <div class="col-xl-3 col-sm-6">
                                                <div class="text-center p-3">
                                                    <h2 class="mt-2"><i class="text-danger <?php echo $icon ?> fa-3"></i> <b><?php echo $counttotal ?></b></h2>
                                                    <p class="text-muted mb-0"><?php echo $tag ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    
                                    <h5 class="mt-0 font-14 mb-3">Notifications????</h5>
                                    <div class="table-responsive datatable">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Message</th>
                                                <th>Recipient</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php 
                                                $count = 1;
                                                $getnot = dbquery("SELECT * FROM ".$table5." WHERE recipient='All' OR recipient = '$email' ORDER BY id DESC")->rows;
                                                foreach($getnot as $not){
                                                    $tid = $not['id'];
                                                    $message = $not['message'];
                                                    $recipient = $not['recipient'];
                                                    $date = $not['date_created'];
                                                    $ago = getdiff($date);
                                            ?>
                                            <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $message ?></td>
                                                <td><?php echo $recipient ?></td>
                                                <td><?php echo $ago ?></td>
                                            </tr>
                                            <?php $count = $count + 1; } ?>
                                        </tbody>
                                    </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

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

        <script src="../assets/js/morris.min.js"></script>
        <script src="../assets/js/raphael.min.js"></script>

        <script src="../assets/js/dashboard.init.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>

    </body>
</html>