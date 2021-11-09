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
    
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $projectname ?> - Admin Users</title>
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
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.5.95/css/materialdesignicons.min.css" integrity="sha512-EhtFgx6fGa2B3UNje2rTcPBgryWKx7TVkcGuOsCkybAbAaWEGrWDjsMFPqJUwXf2u1qmz6BxocZvcXVmTfMG9g==" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css" />
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
                            <h4 class="header-title mb-3">Admin Users List &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal">Add Admin User <i class="fas fa-plus"></i></button></h4>
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                        $getadmin = dbquery("SELECT * FROM ".$table1." WHERE id != '$psid' ORDER BY id DESC")->rows;
                                        foreach($getadmin as $admin){
                                            $tid = $admin['id'];
                                            $fullname = $admin['fullname'];
                                            $email = $admin['email'];
                                            $image = $admin['image'];
                                            $role = $admin['role'];
                                            $date = $admin['date_created'];
                                            $ago = getdiff($date);
                                            $getgroup = dbquery("SELECT * FROM ".$table6." WHERE id = '$role'")->rows;
                                            foreach($getgroup as $group){
                                                $groupname = $group['groupname'];
                                    ?>
                                    <tr>
                                        <td><img height="50px" width="50px" src="<?php echo $image ?>"></td>
                                        <td><?php echo $fullname ?></td>
                                        <td><?php echo $email ?></td>
                                        <td><?php echo $groupname ?></td>
                                        <td><?php echo $ago ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm editbtn" data-id="<?php echo $tid ?>" title="Edit"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm delbtn" data-id="<?php echo $tid ?>" title="Delete "><i class="fas fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    <?php }} ?>
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
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" action="addprocess.php" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add Admin User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Upload Image</h5>
                        <input type="file" name="file" class="filestyle" id="filestyleicon">
                        
                        <h5 class="font-14">Full Name<span class="text-danger">*</span></h5>
                        <input type="text" name="fullname" parsley-trigger="change" required class="form-control" id="addfullname" value="" placeholder="Enter Full Name">
                        
                        <h5 class="font-14">Email Address<span class="text-danger">*</span></h5>
                        <input type="email" name="email" parsley-trigger="change" required class="form-control" id="addemail" value="" placeholder="Enter Email Address">
                        
                        <h5 class="font-14">Password<span class="text-danger">*</span></h5>
                        <input type="password" name="password" parsley-trigger="change" class="form-control" id="addpassword" value="" placeholder="Enter Password">
                        
                        <h5 class="font-14">Select Roles<span class="text-danger">*</span></h5>
                        <select id="addgrouprole" name="role" class="form-control">
                            <?php $getrole = dbquery("SELECT * FROM ".$table6." ORDER BY id ASC")->rows;
                                    foreach($getrole as $role){
                                        $gid = $role['id'];
                                        $groupname = $role['groupname'];
                            ?>
                            <option value="<?php echo $gid ?>"><?php echo $groupname ?></option>
                            <?php } ?>
                        </select>
                        
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
        <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" action="editprocess.php" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Edit Admin User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Upload Image</h5>
                        <input type="file" name="file" class="filestyle" id="filestyleicon">
                        
                        <h5 class="font-14">Full Name<span class="text-danger">*</span></h5>
                        <input type="text" name="fullname" parsley-trigger="change" required class="form-control" id="editfullname" value="" placeholder="Enter Full Name">
                        
                        <h5 class="font-14">Email Address<span class="text-danger">*</span></h5>
                        <input type="email" name="email" parsley-trigger="change" required class="form-control" id="editemail" value="" placeholder="Enter Email Address">
                        
                        <h5 class="font-14">Password<span class="text-danger">*</span></h5>
                        <input type="password" name="password" parsley-trigger="change" class="form-control" id="editpassword" value="" placeholder="Enter Password">
                        
                        <h5 class="font-14">Select Roles<span class="text-danger">*</span></h5>
                        <select id="editgrouprole" name="role" class="form-control">
                            <?php $getrole = dbquery("SELECT * FROM ".$table6." ORDER BY id ASC")->rows;
                                    foreach($getrole as $role){
                                        $gid = $role['id'];
                                        $groupname = $role['groupname'];
                            ?>
                            <option value="<?php echo $gid ?>"><?php echo $groupname ?></option>
                            <?php } ?>
                        </select>
                        
                    </div>
                     <input type="hidden" name="img" parsley-trigger="change" required  class="form-control" id="editimage">
                     <input type="hidden" name="userid" parsley-trigger="change" required  class="form-control" id="edituserid">
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
            <form method="post" action="deleteprocess.php" enctype="multipart/form-data" class="form-validation">
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
                    <input type="hidden" name="userid" parsley-trigger="change" required  class="form-control" id="deluserid">
                    <input type="hidden" name="fullname" parsley-trigger="change" required  class="form-control" id="delusername">
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
                 var userId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {userId : userId, type : "view"},
                  success: function(datanow){
                         var role = datanow[0].role;
                         var action = datanow[0].action;
                         
                         var tagRole = $('#viewrole');
                         tagRole.tagsinput();
                         var rolearray = role.split(',');
                         for(var i=0; i<rolearray.length; i++){
                            tagRole.tagsinput('add', rolearray[i]);
                         }
                         
                         
                         var tagAction = $('#viewaction');
                         tagAction.tagsinput();
                         var actionarray = action.split(',');
                         for(var i=0; i<actionarray.length; i++){
                            tagAction.tagsinput('add', actionarray[i]);
                         }
                         
                         $('#viewModal').modal('show');
                         
                    }
                });
            });
            
            
            $(document).on("click", ".editbtn", function () {
                 var userId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {userId : userId, type : "view"},
                  success: function(datanow){
                         var fullname = datanow[0].fullname;
                         var email = datanow[0].email;
                         var password = datanow[0].password;
                         
                         var role = datanow[0].role;
                         
                         var image = datanow[0].image;
                         
                         var userid = datanow[0].id;
                         
                         $('#editfullname').val(fullname);
                         $('#editemail').val(email); 
                         $('#editimage').val(image);
                         $('#edituserid').val(userid);
                         
                         var roleopts = document.getElementById("editgrouprole").options;
                         for(var i = 0; i < roleopts.length; i++) {
                            if(roleopts[i].value == role) {
                               var indexopt = i;
                               document.getElementById("editgrouprole").selectedIndex = indexopt;
                               break;
                            }
                         }
                         
                         
                         $('#editModal').modal('show');
                         
                    }
                });
            });
            
            
            
            $(document).on("click", ".delbtn", function () {
                 var userId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {userId : userId, type : "view"},
                  success: function(datanow){
                    var fullname = datanow[0].fullname;
                    $('#delusername').val(fullname);
                    $('#deluserid').val(userId);
                    $('#deleteModal').modal('show');
                         
                    }
                });
                 
            });
        </script>

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
        <script src="../assets/libs/jszip.min.js"></script>
        <script src="../assets/libs/pdfmake.min.js"></script>
        <script src="../assets/libs/vfs_fonts.js"></script>
        <script src="../assets/libs/buttons.html5.min.js"></script>
        <script src="../assets/libs/buttons.print.min.js"></script>
        <script src="../assets/libs/bootstrap-tagsinput.min.js"></script>
        <script src="../assets/libs/switchery.min.js"></script>
        <script src="../assets/libs/moment.min.js"></script>

        <!-- Responsive examples -->
        <script src="../assets/libs/dataTables.responsive.min.js"></script>
        <script src="../assets/libs/responsive.bootstrap4.min.js"></script>

        <!-- Datatables init -->
        <script src="../assets/js/datatables.init.js"></script>
        <script src="../assets/libs/form-advanced.init.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>

    </body>
</html>