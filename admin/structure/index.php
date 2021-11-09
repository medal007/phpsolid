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

$strucnumber = 1;

$getstrno = dbquery("SELECT * FROM ".$table4." WHERE pageid = ".$pageid." ORDER BY strucnumber DESC LIMIT 1")->rows;
if($getstrno != null){
    $strno = $getstrno[0]['strucnumber'];
    $strucnumber = $strno + 1;
}


$dbfieldname = dbescape($strucname);
$dbfieldname = strtolower($dbfieldname);
$dbfieldname = str_replace(' ', '_', $dbfieldname);

if(isset($basicprocess)){
    $strucextra = "";
    $strucactive = "";
    
    $runadd1 = dbquery("INSERT INTO " .$table4. " (pageid, strucname, strucnumber, structype, strucextra, strucactive, date_created) VALUES ('".dbescape($pageid)."', '".dbescape($strucname)."', '".dbescape($strucnumber)."', '".dbescape($structype)."', '".dbescape($strucextra)."', '".dbescape($strucactive)."', '".dbescape($currentdate)."')");
    if($runadd1 > 0){
        
        $getsecondtolast = dbquery("SELECT COLUMN_NAME, ORDINAL_POSITION FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$databaseName' AND TABLE_NAME ='$table7' ORDER BY ORDINAL_POSITION DESC LIMIT 1,1")->rows;
        
        $secondtolast = $getsecondtolast[0]['COLUMN_NAME'];
        
        if($structype == "Text Area" || $structype == "Editor"){
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " TEXT NOT NULL AFTER " .$secondtolast."");
            
        } else if($structype == "Number"){
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " INT(255) NOT NULL AFTER " .$secondtolast."");
            
        } else{
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " VARCHAR(255) NOT NULL AFTER " .$secondtolast."");
            
        }
        
    }
    
    header('Location: '.$_SERVER['REQUEST_URI']);
}


else if(isset($advanceprocess)){
    
    $strucactive = "";
    
    if($strucextracode != "Add Your PHP Code Here......"){
        $strucextracode = str_replace('<?php', '', $strucextracode);
        $strucextracode = str_replace('?>', '', $strucextracode);
        $strucextra = $strucextracode;
    }
    
    $runadd1 = dbquery("INSERT INTO " .$table4. " (pageid, strucname, strucnumber, structype, strucextra, strucactive, date_created) VALUES ('".dbescape($pageid)."', '".dbescape($strucname)."', '".dbescape($strucnumber)."', '".dbescape($structype)."', '".dbescape($strucextra)."', '".dbescape($strucactive)."', '".dbescape($currentdate)."')");
    if($runadd1 > 0){
        
        $getsecondtolast = dbquery("SELECT COLUMN_NAME, ORDINAL_POSITION FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$databaseName' AND TABLE_NAME ='$table7' ORDER BY ORDINAL_POSITION DESC LIMIT 1,1")->rows;
        
        $secondtolast = $getsecondtolast[0]['COLUMN_NAME'];
        
        if($structype == "Text Area" || $structype == "Editor"){
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " TEXT NOT NULL AFTER " .$secondtolast."");
            
        } else if($structype == "Number"){
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " INT(255) NOT NULL AFTER " .$secondtolast."");
            
        } else{
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " VARCHAR(255) NOT NULL AFTER " .$secondtolast."");
            
        }
        
    }
    
   header('Location: '.$_SERVER['REQUEST_URI']);
    
}



else if(isset($relprocess)){
    
    $structype = "Relationship";
    
    $strucactive = "";
    
    $strucextra = $strucextra1 . "," . $strucextra2 . "," . $strucextra3 . "," . $strucextra4 . "," . $strucextra5 . "," . $strucextra6;
    
    $runadd1 = dbquery("INSERT INTO " .$table4. " (pageid, strucname, strucnumber, structype, strucextra, strucactive, date_created) VALUES ('".dbescape($pageid)."', '".dbescape($strucname)."', '".dbescape($strucnumber)."', '".dbescape($structype)."', '".dbescape($strucextra)."', '".dbescape($strucactive)."', '".dbescape($currentdate)."')");
    if($runadd1 > 0){
        
        $getsecondtolast = dbquery("SELECT COLUMN_NAME, ORDINAL_POSITION FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$databaseName' AND TABLE_NAME ='$table7' ORDER BY ORDINAL_POSITION DESC LIMIT 1,1")->rows;
        
        $secondtolast = $getsecondtolast[0]['COLUMN_NAME'];
        
        if($structype == "Text Area" || $structype == "Editor"){
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " TEXT NOT NULL AFTER " .$secondtolast."");
            
        } else if($structype == "Number"){
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " INT(255) NOT NULL AFTER " .$secondtolast."");
            
        } else{
            
            $run1 = dbquery("ALTER TABLE " .$table7. " ADD " .$dbfieldname. " VARCHAR(255) NOT NULL AFTER " .$secondtolast."");
            
        }
        
    }
    
    header('Location: '.$_SERVER['REQUEST_URI']);
    
}

else if(isset($delprocess)){
    
    $getstrno = dbquery("SELECT * FROM ".$table4." WHERE id = '".dbescape($strucid)."'")->rows;
    $strno = $getstrno[0]['strucnumber'];
    $tofind = $strno + 1;
    $runadd1 = dbquery("DELETE FROM " .$table4. " WHERE id='".dbescape($strucid)."'");
    if($runadd1 > 0){
        $updatevalue1 = dbquery("UPDATE ". $table4 . " SET strucnumber = strucnumber - 1 WHERE strucnumber > " . $strno ." AND pageid = '$pageid'");
        notify($fullname. " deleted " .$strucname, "All");
        $run1 = dbquery("ALTER TABLE ".$table7." DROP COLUMN ".$dbfieldname."");
        echo '<script language="javascript">';
        echo 'alert("Input Field Deleted!")';
        echo '</script>';
        header('Location: '.$_SERVER['REQUEST_URI']);
    } else{
        echo '<script language="javascript">';
        echo 'alert("Failed to delete!")';
        echo '</script>';
        header('Location: '.$_SERVER['REQUEST_URI']);
    }
    
}

?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $projectname ?> - Create <?php echo $pgname ?> Page Structure</title>
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
        <div id="wrapper" class="refresh">

            
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
                            <h4 class="header-title mb-3">Create <?php echo $pgname ?> Page Structure&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#basicModal">Create Basic Input Fields <i class="fas fa-plus"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning waves-effect waves-light" data-toggle="modal" data-target="#advanceModal">Create Advance Input Fields <i class="fas fa-plus"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#relModal">Create Relationship Input Fields <i class="fas fa-plus"></i></button></h4>
                            
                            
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
                                
                                <div class="col-lg-1">
                                    <h5><?php echo $strucnumber ?>.</h5>
                                </div>

                                <div class="col-lg-9">
                                    <?php if($structype == "Text" || $structype == "Email" || $structype == "Password" || $structype == "Number" || $structype == "Phone" || $structype == "Address" || $structype == "City" || $structype == "URL"){ ?>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="<?php echo $lowerstructype ?>" class="form-control" placeholder="<?php echo $strucname ?>">
                                    <?php } else if($structype == "Text Area"){?>
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
                                        <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control" id="default-colorpicker" value="#8fff00">
                                    <?php } else if($structype == "RGBA Color Picker"){?>
                                        <div id="component-colorpicker" class="input-group" title="Using format option">
                                            <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control input-lg" value="#188ae2" />
                                            <span class="input-group-append">
                                                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                            </span>
                                        </div>
                                        
                                    <?php } else if($structype == "Date Picker"){?>
                                        <input type="date" name="<?php echo $lowerstrucname ?>" class="form-control" placeholder="mm/dd/yyyy" id="datepicker">
                                    <?php } else if($structype == "Clock Picker"){?>
                                        <div class="input-group clockpicker mb-3">
                                            <input type="text" name="<?php echo $lowerstrucname ?>" class="form-control" value="09:30">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                            </div>
                                        </div>
                                    <?php } else if($structype == "Editor"){?>
                                        <textarea class="summernote" id="summernote" name="<?php echo $lowerstrucname ?>"></textarea>
                                        
                                    <?php } else if($structype == "Money"){?>
                                         <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" required parsley-trigger="change" type="number" class="form-control" placeholder="<?php echo $strucname ?>">
                                        
                                    <?php } else if($structype == "Select File"){?>
                                        <input type="file" class="filestyle" name="<?php echo $lowerstrucname ?>" data-btnClass="btn-primary">
                                        
                                    <?php } else if($structype == "Tags Input"){?>
                                        <input type="text" name="<?php echo $lowerstrucname ?>" value="" data-role="tagsinput" placeholder="Add <?php echo $strucname ?>" />
                                        
                                    <?php } else if($structype == "Icon Inputs"){?>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="<?php echo $iconn ?>"></i></span>
                                            </div>
                                            <input type="<?php echo $typenme ?>" id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control" placeholder="<?php echo $strucname ?>">
                                        </div>
                                        
                                    <?php } else if($structype == "Input Select"){?>
                                        <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                            <?php for ($i = 0; $i < count($listextra); $i++){?>
                                                <option value="<?php echo $listextra[$i] ?>"><?php echo $listextra[$i] ?></option>
                                            <?php }?>
                                        </select>
                                        
                                    <?php } else if($structype == "Disabled"){?>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="text" class="form-control" readonly value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "Enabled"){?>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="text" class="form-control" value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "PHP Code"){?>
                                    
                                        <h5><?php echo $strucname . ": PHP CODE IS INSERTED HERE!!!"; ?></h5>
                                    
                                      <?php eval($strucextra); ?>
                                      
                                    <?php } else if($structype == "Hidden"){?>
                                        <h5><?php echo $strucname . ": HIDDEN TEXTFIELD IS INSERTED HERE!!!"; ?></h5>
                                        <input id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" type="hidden" class="form-control" value="<?php echo ${$firstextra} ?>">
                                        
                                    <?php } else if($structype == "Toggle Button"){?>
                                        <div class="custom-control custom-switch">
                                            <input name="<?php echo $lowerstrucname ?>" type="checkbox" class="custom-control-input" id="<?php echo $lowerstrucname ?>">
                                            <label class="custom-control-label" for="<?php echo $lowerstrucname ?>"><strong><?php echo $strucname ?></strong></label>
                                        </div>
                                        
                                    <?php } else if($structype == "Inline Checkboxes"){?>
                                    
                                        <?php for ($i = 0; $i < count($listextra); $i++){?>
                                            <div class="checkbox form-check-inline">
                                                <input name="<?php echo $lowerstrucname ?>" type="checkbox" id="<?php echo $listextra[$i] ?>" value="<?php echo $listextra[$i] ?>">
                                                <label for="<?php echo $listextra[$i] ?>"> <?php echo $listextra[$i] ?> </label>
                                            </div>
                                        <?php }?>
                                        
                                    <?php } else if($structype == "Inline Radios"){?>
                                    
                                        <?php for ($i = 0; $i < count($listextra); $i++){?>
                                            <div class="radio radio-info form-check-inline">
                                                <input type="radio" id="<?php echo $listextra[$i] ?>" value="<?php echo $listextra[$i] ?>" name="<?php echo $lowerstrucname ?>" checked>
                                                <label for="<?php echo $listextra[$i] ?>"> <?php echo $listextra[$i] ?> </label>
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
                                                ?>
                                                    <option value="<?php echo $values ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>

                                        <?php } else if($fourthextra == "0" && $fifthextra != "no filter"){?>
                                        
                                            <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." WHERE $fifthextra='$sixthextra' ORDER BY id " .$thirdextra."")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                ?>
                                                    <option value="<?php echo $values ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>

                                        <?php } else if($fourthextra != "0" && $fifthextra != "no filter"){?>
                                        
                                            <select id="<?php echo $lowerstrucname ?>" name="<?php echo $lowerstrucname ?>" class="form-control">
                                                <?php $getrel = dbquery("SELECT * FROM ".$firstextra." WHERE $fifthextra='$sixthextra' ORDER BY id " .$thirdextra." LIMIT ".$fourthextra. "")->rows;
                                                        foreach($getrel as $rel){
                                                        $values = $rel[$secondextra];
                                                ?>
                                                    <option value="<?php echo $values ?>"><?php echo $values ?></option>
                                                <?php }?>
                                            </select>
                                        
                                        <?php } ?>
                                        
                                    <?php }?>
                                </div>

                                <div class="col-lg-2">
                                    <?php if($strucactive == ""){ ?>
                                        <img id="activebtn" src="untick.png" data-id="<?php echo $sid ?>" height="35px" width="35px" title="Visibility">
                                    <?php } else if($strucactive == "yes"){ ?>
                                        <img id="activebtn" src="tick.png" data-id="<?php echo $sid ?>" height="35px" width="35px" title="Visibility">
                                    <?php } ?>
                                    <button class="btn btn-success btn-sm moveupbtn" data-id="<?php echo $sid ?>" title="Move Up"><i class="fas fa-arrow-up" aria-hidden="true"></i></button>
                                    <button class="btn btn-success btn-sm movedownbtn" data-id="<?php echo $sid ?>" title="Move Down"><i class="fas fa-arrow-down" aria-hidden="true"></i></button>
                                    <button class="btn btn-danger btn-sm delbtn" data-id="<?php echo $sid ?>" title="Delete "><i class="fas fa-times" aria-hidden="true"></i></button>

                                </div>
                                
                            </div>
                            
                            <br>
                            <br>
                            
                            <?php } ?>

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
                        <h5 class="modal-title" id="myModalLabel">Add New Page</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Page Icon<span class="text-danger">*</span></h5>
                        <!-- partial:index.partial.html -->
                        <div id="icon"></div>
                        <select id="select" name="pageicon" class="form-control fa-select"></select>
                        
                        <h5 class="font-14">Page Name<span class="text-danger">*</span></h5>
                        <input type="text" name="pagename" parsley-trigger="change" required class="form-control" id="addpagename" value="" placeholder="Enter Page Name">
                        
                        <h5 class="font-14">Page Status<span class="text-danger">*</span></h5>
                        <select name="pagestatus" class="form-control">
                            <option value="onepager">One Pager</option>
                            <option value="multipager">Multi Pager</option>
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
        <div id="basicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Create Basic Input Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Input Field Name<span class="text-danger">*</span></h5>
                        <input id="strucname" name="strucname" required parsley-trigger="change" type="text" class="form-control" value="">
                        <br>
                        
                        <h5 class="font-14">Input Field Type<span class="text-danger">*</span></h5>
                        <p class="sub-header">
                            Select the type of input field you want to create.
                        </p>
                        <select name="structype" class="form-control">
                            <option value="Text">Text</option>
                            <option value="Email">Email</option>
                            <option value="Password">Password</option>
                            <option value="Number">Number</option>
                            <option value="Phone">Phone</option>
                            <option value="Address">Address</option>
                            <option value="City">City</option>
                            <option value="URL">URL</option>
                            <option value="Money">Money</option>
                            <option value="Toggle Button">Toggle Button</option>
                            <option value="Tags Input">Tags Input</option>
                            <option value="Text Area">Text Area</option>
                            <option value="Default Time Picker">Default Time Picker</option>
                            <option value="24Hrs Time Picker">24Hrs Time Picker</option>
                            <option value="Color Picker">Color Picker</option>
                            <option value="RGBA Color Picker">RGBA Color Picker</option>
                            <option value="Date Picker">Date Picker</option>
                            <option value="Clock Picker">Clock Picker</option>
                            <option value="Editor">Editor</option>
                        </select>
                        <input type="hidden" name="pageid" parsley-trigger="change" required  value="<?php echo $pageid ?>" class="form-control" id="editpageid">
                        <input type="hidden" name="basicprocess" parsley-trigger="change" required  class="form-control" id="editpageid">
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
        <div id="advanceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Create Advance Input Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Input Field Name<span class="text-danger">*</span></h5>
                        <input id="strucname" name="strucname" required parsley-trigger="change" type="text" class="form-control" value="">
                        <br>
                        
                        <h5 class="font-14">Input Field Type<span class="text-danger">*</span></h5>
                        <p class="sub-header">
                            Select the type of input field you want to create.
                        </p>
                        <select name="structype" class="form-control advbtntype">
                            <option value="Icon Inputs">Icon Inputs</option>
                            <option value="Select File">Select File</option>
                            <option value="Input Select">Input Select</option>
                            <option value="PHP Code">PHP Code</option>
                            <option value="Disabled">Disabled</option>
                            <option value="Enabled">Enabled</option>
                            <option value="Hidden">Hidden</option>
                            <option value="Inline Checkboxes">Inline Checkboxes</option>
                            <option value="Inline Radios">Inline Radios</option>
                        </select>
                        
                        <br>
                        
                        <h5 class="font-14">Input Field Extra<span class="text-danger">*</span></h5>
                        <p id="infoshow" class="sub-header">
                            Example (text, fas fa-eye,)==> Input Type (comma seperated) Font Awesome Icon Code (comma seperated)
                        </p>
                        
                        <div id="showcoder"><textarea name="" id="codeMirrorDemo" class="p-3" placeholder=""></textarea></div>
                         <textarea style="display:none;" name="strucextracode" class="form-control" rows="5" id="getcoder" placeholder=""></textarea>
                         
                        <div id="showextra"><input type="text" id="strucextra" name="strucextra" value="" data-role="tagsinput" placeholder="add extra conditions" /></div>
                        
                        <input type="hidden" name="pageid" parsley-trigger="change" required  value="<?php echo $pageid ?>" class="form-control" id="editpageid">
                        <input type="hidden" name="advanceprocess" parsley-trigger="change" required  class="form-control" id="editpageid">
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning waves-effect waves-light add-btn">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </form>
        </div>
        <!-- /.modal -->
        
        
        
        <!-- sample modal content -->
        <div id="relModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form method="post" enctype="multipart/form-data" class="form-validation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Create Relationship Input Fields</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <h5 class="font-14">Input Field Name<span class="text-danger">*</span></h5>
                        <input id="strucname" name="strucname" required parsley-trigger="change" type="text" class="form-control" value="">
                        <br>
                        
                        
                        <div id="dbname">
                        
                            <h5 class="font-14">Input Select Table Name<span class="text-danger">*</span></h5>
                            
                            <select name="strucextra1" id="strucextra1" class="form-control" required>
                                <option>Select Table Name.....</option>
                                <?php
                                    $getphase1 = "%usergen";
                                    $getphase2 = "%admin";
                                    $gettable = dbquery("SHOW FULL TABLES")->rows;
                                    
                                    foreach($gettable as $table){
                                        $inval = "Tables_in_". $databaseName;
                                        
                                        
                                    //For Future References You can use this to restrict tables shown
                                        
                                    //$gettable = dbquery("SHOW TABLES LIKE '".$getphase."'")->rows;
                                    
                                    //foreach($gettable as $table){
                                        //$inval = "Tables_in_". $databaseName. " ({$getphase})";
                                ?>
                                <option value="<?php echo $table[$inval] ?>"><?php echo $table[$inval] ?></option>
                                <?php } ?>
                            </select>
                        
                        </div>
                        
                        <br>
                        
                        <div id="dbname">
                            
                            <h5 class="font-14">Input Select Field To Display<span class="text-danger">*</span></h5>
                        
                            <select name="strucextra2" id="strucextra2" class="form-control" required></select>
                        
                        </div>
                        
                        <br>
                        
                        <h5 class="font-14">Input Field Order<span class="text-danger">*</span></h5>
                        <select name="strucextra3" class="form-control" required>
                            <option value="ASC">ASC</option>
                            <option value="DESC">DESC</option>
                        </select>
                        
                        <br>
                        
                        <h5 class="font-14">Input Field Limit<span class="text-danger">*</span></h5>
                        <p id="infoshow" class="sub-header">
                            Do not change the default value 0 to get all rows from the database.
                        </p>
                        <input id="strucextra4" name="strucextra4" required parsley-trigger="change" type="number" class="form-control" value="0">
                        
                        <br>
                        <h5 class="font-14">Select Where Clause Field To Filter<span class="text-danger">*</span></h5>
                        <select name="strucextra5" id="strucextra5" class="form-control" required>
                            <option value="no filter">no filter</option>
                        </select>
                        <br>

                        <h5 class="font-14">Input Value For Where Clause<span class="text-danger">*</span></h5>
                        <p id="infoshow" class="sub-header">
                            If the WHERE CLAUSE field is a relationship field you have to input the Foreign Key ID to filter.
                        </p>
                        <input id="strucextra6" name="strucextra6"  parsley-trigger="change" type="text" class="form-control" value="">
                        <br>
                        
                        <input type="hidden" name="relprocess" parsley-trigger="change" required  class="form-control" id="relstrucid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light add-btn">Save</button>
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
                         <h5 class="font-14">Page Icon<span class="text-danger">*</span></h5>
                        <!-- partial:index.partial.html -->
                        <div id="iconic"></div>
                        <select id="selectic" name="pageicon" class="form-control fa-select"></select>
                        
                        <h5 class="font-14">Page Name<span class="text-danger">*</span></h5>
                        <input type="text" name="pagename" parsley-trigger="change" required class="form-control" id="editpagename" value="" placeholder="Enter Page Name">
                        
                        <h5 class="font-14">Page Status<span class="text-danger">*</span></h5>
                        <select id="editpagestatus" name="pagestatus" class="form-control">
                            <option value="onepager">One Pager</option>
                            <option value="multipager">Multi Pager</option>
                        </select>
                        
                    </div>
                     <input type="hidden" name="pageid" parsley-trigger="change" required  class="form-control" id="editpageid">
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
                        <h5 class="modal-title" id="myModalLabel">Delete <?php echo $pgname ?> Input Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <center>
                        <h5 class="font-14"><span class="text-danger">Are you sure you want to delete this <?php echo $pgname ?> Input Field?.</span></h5>
                        <h5 class="font-14"><span class="text-danger">It may affect some data related to it.</span></h5>
                        </center>
                    </div>
                    <input type="hidden" name="strucid" parsley-trigger="change" required  class="form-control" id="delstrucid">
                    <input type="hidden" name="strucname" parsley-trigger="change" required  class="form-control" id="delstrucname">
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
        
        $(document).ready(function() {
            $('#showcoder').hide();
        });
            
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
                 var pageId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {pageId : pageId, type : "view"},
                  success: function(datanow){
                         var pagename = datanow[0].pagename;
                         var pagestatus = datanow[0].pagestatus;
                         var pageicon = datanow[0].pageicon;
                         
                         $('#editpagename').val(pagename);
                         
                         var pagestatusopts = document.getElementById("editpagestatus").options;
                         for(var i = 0; i < pagestatusopts.length; i++) {
                            if(pagestatusopts[i].value == pagestatus) {
                               var indexopt = i;
                               document.getElementById("editpagestatus").selectedIndex = indexopt;
                               break;
                            }
                         }
                         var pageicon = pageicon.replace("fas ", "");
                         
                         var pageicon2 = pageicon + "-o";
                         
                         var pageiconopts = document.getElementById("selectic").options;
                         for(var i = 0; i < pageiconopts.length; i++) {
                            if(pageiconopts[i].value == pageicon) {
                               var indexopt = i;
                               document.getElementById("selectic").selectedIndex = indexopt;
                               break;
                            }
                         }
                         
                         var pageiconopts = document.getElementById("selectic").options;
                         for(var i = 0; i < pageiconopts.length; i++) {
                            if(pageiconopts[i].value == pageicon2) {
                               var indexopt = i;
                               document.getElementById("selectic").selectedIndex = indexopt;
                               break;
                            }
                         }
                         $('#editpageid').val(pageId);
                         
                         $('#editModal').modal('show');
                         
                    }
                });
            });
            
            
            
            $(document).on("click", ".delbtn", function () {
                 var strucId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {strucId : strucId, type : "view"},
                  success: function(datanow){
                    var strucname = datanow[0].strucname;
                    $('#delstrucname').val(strucname);
                    $('#delstrucid').val(strucId);
                    $('#deleteModal').modal('show');
                         
                    }
                });
                 
            });
            
            
            
            $(document).on("click", ".advbtntype", function () {
                
                 $('#showcoder').hide();
                 $('#showextra').show();
                 
                 var pageValue = $('.advbtntype :selected').text();
                 
                 if(pageValue == "Icon Inputs"){
                     $('#infoshow').text('Example (text, fas fa-eye,)==> Input Type (comma seperated) Font Awesome Icon Code (comma seperated)');
                     
                 }else if(pageValue == "Input Select"){
                     $('#infoshow').text('List the options you want the select field to have make sure to seperate each item using a comma');
                     
                 }else if(pageValue == "Disabled"){
                     $('#infoshow').text('This disabled field displays the value of a PHP Variable enter a variable name to be used, do not include a $(dollar sign) and also seperate by comma(it only accepts one input)');
                     
                 }else if(pageValue == "Enabled"){
                     $('#infoshow').text('This normal textfield displays the value of a PHP Variable enter a variable name to be used, do not include a $(dollar sign) and also seperate by comma(it only accepts one input)');
                
                 }else if(pageValue == "PHP Code"){
                     $('#infoshow').text('Add PHP Tags and Code to your input so as to manipulate variables.');
                     $('#showcoder').show();
                     $('#showextra').hide();
                     
                 }else if(pageValue == "Hidden"){
                     $('#infoshow').text('This is a hidden text and it can be used to store PHP Variables without displaying values.');
                     
                 }else if(pageValue == "Inline Checkboxes"){
                     $('#infoshow').text('This is a multi checkbox input field, enter the options you want along with the checkboxes(seperate by comma).');
                     
                 }else if(pageValue == "Inline Radios"){
                     $('#infoshow').text('This is a multi radio input field, enter the options you want along with the radios(seperate by comma).');
                     
                 }else if(pageValue == "Select File"){
                     $('#infoshow').text('This is a select input field, enter the URL of the default image you want with this select field(seperate by comma).');
                     
                 } else{
                     $('#infoshow').text('');
                 }
                 
            });
            
            
            
            $(document).on("click", ".moveupbtn", function () {
                var strucId = $(this).data('id');

                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {strucId : strucId, type : "view"},
                  success: function(datanow){
                     var strucNo = datanow[0].strucnumber;
                     var strucName = datanow[0].strucname;
                     var pageid = datanow[0].pageid;
                     var structogo = strucNo - 1;
                     var table7 = "<?php echo $table7; ?>";
                     
                     $.ajax({
                      url: './processflow.php',
                      type: 'POST',
                      dataType: 'json',
                      data: {strucNo : strucNo, strucName : strucName, pageid : pageid, table7 : table7, structogo : structogo, type : "moveup"},
                      success: function(datanow1){
                          window.location.reload();
                        }
                    });
                    
                    
                  }
                });
                 
            });
            
            
            $(document).on("click", ".movedownbtn", function () {
                 var strucId = $(this).data('id');
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {strucId : strucId, type : "view"},
                  success: function(datanow){
                     var strucNo = datanow[0].strucnumber;
                     var strucName = datanow[0].strucname;
                     var pageid = datanow[0].pageid;
                     var structogo = parseInt(strucNo) + 1;
                     var table7 = "<?php echo $table7; ?>";
                     
                     $.ajax({
                      url: './processflow.php',
                      type: 'POST',
                      dataType: 'json',
                      data: {strucNo : strucNo, strucName : strucName, pageid : pageid, table7 : table7, structogo : structogo, type : "movedown"},
                      success: function(datanow1){
                          
                          window.location.reload();
                        }
                    });
                    
                    
                  }
                });
            });
            
            
            $(document).on("click", "#activebtn", function () {
                 var tickId = $(this).data('id');
                 
                 
                 $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {tickId : tickId, type : "tick"},
                  success: function(datanow){
                        window.location.reload();
                         
                    }
                });
                 
            });
            
            
            $(document).on("change", "#strucextra1", function () {
                 
                 $("#strucextra2 option").remove();
                 $("#strucextra5 option").remove(); 

                 $('#strucextra5').append($('<option>',
                    {
                    value: "no filter",
                    text : "no filter" 
                }));
                
                var gettablename = $('#strucextra1').find(":selected").text();
                
                $.ajax({
                  url: './processflow.php',
                  type: 'POST',
                  dataType: 'json',
                  data: {tablename : gettablename, type : "getfields"},
                  success: function(datanow){
                      var len = datanow.length;
                      console.log(datanow);
                      for(var i=0; i<len; i++){
                          var fields = datanow[i].COLUMN_NAME;
                          $('#strucextra2').append($('<option>',
                         {
                            value: fields,
                            text : fields 
                        }));

                        $('#strucextra5').append($('<option>',
                         {
                            value: fields,
                            text : fields 
                        }));

                      }
                    }
                });
                 
            });
            
            setInterval(function() {
                
                var content = editor.getValue(); //textarea text
                $('#getcoder').val(content);
                
            
            }, 100); // seconds to wait, miliseconds
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
        
        <script>
            var editor = CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
              mode: "htmlmixed",
              theme: "monokai"
            });
            editor.setValue('Add Your PHP Code Here......');
            editor.refresh();
        </script>
    

    </body>
</html>