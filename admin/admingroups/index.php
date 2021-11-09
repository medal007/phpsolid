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
                            <h4 class="header-title mb-3">User Groups List &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal">Add User Group <i class="fas fa-plus"></i></button></h4>
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                        $getgroup = dbquery("SELECT * FROM ".$table6." ORDER BY id DESC")->rows;
                                        foreach($getgroup as $group){
                                            $gid = $group['id'];
                                            $groupname = $group['groupname'];
                                            $role = $group['role'];
                                            $action = $group['action'];
                                            $date = $group['date_created'];
                                            $ago = getdiff($date);
                                    ?>
                                    <tr>
                                        <td><?php echo $groupname ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm viewbtn" data-id="<?php echo $gid ?>" title="View Group Roles and Actions"><i class="fas fa-eye" aria-hidden="true"></i></button>
                                            <button type="button" class="btn btn-primary btn-sm editbtn" data-id="<?php echo $gid ?>" title="Edit"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm delbtn" data-id="<?php echo $gid ?>" title="Delete "><i class="fas fa-trash" aria-hidden="true"></i></button>
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
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" action="addprocess.php" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add Group Roles and Actions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Group Name<span class="text-danger">*</span></h5>
                        <input type="text" name="groupname" parsley-trigger="change" required class="form-control" id="addgroupname" value="" placeholder="Enter Group Name">
                        
                        <h5 class="font-14">Select Access To Pages<span class="text-danger">*</span></h5>
                        <select name="pages[]" class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                            <option selected value="dashboard">Dashboard</option>
                            <option value="adminusers">Admin Users</option>
                            <option value="admingroups">User Groups</option>
                            <option value="adminpages">Manage Pages</option>
                            <option value="settings">Settings</option>
                            <option value="profile">Profile</option>
                            <option value="structure">Structure</option>
                            <?php $getpage = dbquery("SELECT * FROM ".$table3." ORDER BY id ASC")->rows;
                                    foreach($getpage as $page){
                                        $pid = $page['id'];
                                        $pagename = $page['pagename'];
                            ?>
                            <option value="<?php echo $pid ?>"><?php echo $pagename ?></option>
                            <?php } ?>
                        </select>
                        
                        <h5 class="font-14">Select Access To Actions<span class="text-danger">*</span></h5>
                        <select name="actions[]" class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                            <option value="Add">Add</option>
                            <option value="Update">Update</option>
                            <option value="Delete">Delete</option>
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
        <div id="viewModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">View Group Roles and Actions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Roles<span class="text-danger">*</span></h5>
                        <input disabled id="viewrole" required parsley-trigger="change" type="text" class="form-control" value="" data-role="tagsinput">
                        
                        <h5 class="font-14">Actions<span class="text-danger">*</span></h5>
                        <input disabled id="viewaction" required parsley-trigger="change" type="text" class="form-control" value="" data-role="tagsinput">
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
                        <h5 class="modal-title" id="myModalLabel">Edit Group Roles and Actions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Group Name<span class="text-danger">*</span></h5>
                        <input type="text" name="groupname" parsley-trigger="change" required class="form-control" id="editgroupname" value="" placeholder="Enter Group Name">
                        
                        <h5 class="font-14">Select Access To Pages<span class="text-danger">*</span></h5>
                        <select id="gpages" name="pages[]" class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                            <option selected value="dashboard">Dashboard</option>
                            <option value="adminusers">Admin Users</option>
                            <option value="admingroups">User Groups</option>
                            <option value="adminpages">Manage Pages</option>
                            <option value="settings">Settings</option>
                            <option value="profile">Profile</option>
                            <option value="structure">Structure</option>
                            <?php $getpage = dbquery("SELECT * FROM ".$table3." ORDER BY id ASC")->rows;
                                    foreach($getpage as $page){
                                        $pid = $page['id'];
                                        $pagename = $page['pagename'];
                            ?>
                            <option value="<?php echo $pid ?>"><?php echo $pagename ?></option>
                            <?php } ?>
                        </select>
                        
                        <h5 class="font-14">Select Access To Actions<span class="text-danger">*</span></h5>
                        <select id="gactions" name="actions[]" class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                            <option value="Add">Add</option>
                            <option value="Update">Update</option>
                            <option value="Delete">Delete</option>
                        </select>
                        
                    </div>
                     <input type="hidden" name="groupid" parsley-trigger="change" required  class="form-control" id="editgroupid">
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
                        <h5 class="modal-title" id="myModalLabel">Delete Group Roles and Actions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <center>
                        <h5 class="font-14"><span class="text-danger">Are you sure you want to delete this Group Roles and Actions?.</span></h5>
                        <h5 class="font-14"><span class="text-danger">It may affect some data related to it.</span></h5>
                        </center>
                    </div>
                    <input type="hidden" name="groupid" parsley-trigger="change" required  class="form-control" id="delgroupid">
                    <input type="hidden" name="groupname" parsley-trigger="change" required  class="form-control" id="delgroupname">
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
                 var groupId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {groupId : groupId, type : "view"},
                  success: function(datanow){
                         var role = datanow[0].role;
                         var action = datanow[0].action;
                         
                         
                         $('#viewrole').tagsinput('removeAll');
                         
                         $('#viewaction').tagsinput('removeAll');
                         
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
            
            var remrole = "dashboard";
            var remaction = "Add";
            
            $(document).on("click", ".editbtn", function () {
                 var groupId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {groupId : groupId, type : "view"},
                  success: function(datanow){
                         var groupname = datanow[0].groupname;

                         var role = datanow[0].role;
                         var action = datanow[0].action;
                         
                         var groupid = datanow[0].id;
                         
                         
                         $('#editgroupname').val(groupname);
                         $('#editgroupid').val(groupid);

                         var remrolearray = remrole.split(',');
                         
                         for(var i=0; i<remrolearray.length; i++){
                            $('#gpages option[value=' + remrolearray[i] + ']').attr('selected',false).change();
                         }
                         
                         var remactionarray = remaction.split(',');
                         
                         for(var i=0; i<remactionarray.length; i++){
                            $('#gactions option[value=' + remactionarray[i] + ']').attr('selected',false).change();
                         }
                         
                         var rolearray = role.split(',');
                         for(var i=0; i<rolearray.length; i++){
                            $('#gpages option[value=' + rolearray[i] + ']').attr('selected','').change();
                         }
                         
                         var actionarray = action.split(',');
                         for(var i=0; i<actionarray.length; i++){
                            $('#gactions option[value=' + actionarray[i] + ']').attr('selected','').change();
                         }
                         
                         
                         remrole = role;
                         remaction = action;
                         
                         
                         $('#editModal').modal('show');
                         
                    }
                });
            });
            
            
            
            $(document).on("click", ".delbtn", function () {
                 var groupId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {groupId : groupId, type : "view"},
                  success: function(datanow){
                    var groupname = datanow[0].groupname;
                    $('#delgroupname').val(groupname);
                    $('#delgroupid').val(groupId);
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