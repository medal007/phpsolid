<?php
require_once '../functions/dataaccess.php';

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

if($check1 > 0 && $check2 > 0 && $check3 > 0 && $check4 > 0 && $check5 > 0  && $check6 > 0){
    session_destroy();
    header("Location:../login");
}

$domainname = $_SERVER['SERVER_NAME'];
$domainname = explode(".",$domainname);
$domainname = $domainname[0];

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>PHP SOLID - <?php echo strtoupper($domainname); ?> SETUP</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="../assets/images/phpsolid.png">
        <!-- App css -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

    </head>

    <body>
        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <a href="index.html">
                                        <span><img src="../assets/images/phpsolid.png" alt="" height="150px"></span>
                                    </a>
                                </div>
                                <h5 style="text-align:center;">Welcome, Setup Your Project To Get Started</h5>
                                <form method="POST" action="setupprocess.php" class="p-2">
                                    <div class="form-group">
                                        <label for="username">Project Name</label>
                                        <input class="form-control" type="text" id="project_name" name="project_name"  required="" placeholder="Project Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Super Admin Full Name</label>
                                        <input class="form-control" type="text" id="fullname" name="fullname" required="" placeholder="Super Admin Full Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="emailaddress">Super Admin Email address</label>
                                        <input class="form-control" type="email" id="email" name="email" required="" placeholder="john@deo.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Super Admin Password</label>
                                        <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
                                    </div>
                                    <div class="form-group mb-4 pb-3">
                                        <div class="custom-control custom-checkbox checkbox-primary">
                                            <input required type="checkbox" class="custom-control-input" id="checkbox-signin">
                                            <label class="custom-control-label" for="checkbox-signin">I accept <a href="#">Terms and Conditions</a></label>
                                        </div>
                                    </div>
                                    <div class="mb-3 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Get Started!!! </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <!-- Vendor js -->
        <script src="../assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>

    </body>

</html>