<?php
include 'config.php';
    global $link;
    if(isset($_POST['trigger'])) {
        $name    = mysqli_real_escape_string($link,$_POST['name']);
        $address = mysqli_real_escape_string($link,$_POST['address']);
        $dob     = mysqli_real_escape_string($link,$_POST['dob']);
        $purpose = mysqli_real_escape_string($link,$_POST['purpose']);
        $accounts_id    = $_SESSION['id'];
        $checking = mysqli_query($link,"SELECT * FROM forms_tbl WHERE category = 'Barangay Indigency'");
        $count = mysqli_num_rows($checking) + 1;
        $reference = '168-'.date('Y').'-'.str_pad($count, 5, "0", STR_PAD_LEFT);

        $query = mysqli_query($link,"INSERT INTO forms_tbl (accounts_id,reference,name,address,dob,purpose,category) VALUES ('$accounts_id','$reference','$name','$address','$dob','$purpose','Barangay Indigency')");
        if($query) {
            $message = base64_encode(urlencode('Barangay Indigency form has been submitted.'));
            header('location: user_barangay_indigency.php?success=true&message='.$message);
        } else {
            $message = base64_encode(urlencode('Something went wrong.'));
            header('location: user_barangay_indigency.php?success=false&message='.$message);
        }
        mysqli_close($link);
    }
?>
<!doctype html>
<html lang="en">

<head>
<title>168 Deparo | Indigency</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="icon" href="../admin/brgy.png" type="image/x-icon">

<!-- VENDOR CSS -->
<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="../assets/vendor/sweetalert/sweetalert.css"/>
<link rel="stylesheet" href="../assets/vendor/jquery-steps/jquery.steps.css">
<link rel="stylesheet" href="../assets/vendor/nestable/jquery-nestable.css"/>
<link rel="stylesheet" href="../assets/vendor/dropify/css/dropify.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="../admin/assets/css/main.css">
<link rel="stylesheet" href="../admin/assets/css/inbox.css">
<link rel="stylesheet" href="../admin/assets/css/color_skins.css">

<style type="text/css">
    .layout-fullwidth #wrapper #main-content {
    width: 100%;
    }

    .logo {
    line-height: 60px;
    color: #FD7E14;
    font-size: 28px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
    background: #FD7E14;
    color: #fff;
    cursor: default;
    }

    .center {
        text-align: center;
        cursor: default;
    }

    .underline {
        text-decoration: underline;
        font-size: 16px;
        padding-bottom: 15px;
        padding-top: 10px;
        text-align: center;
    }

    .asterisk {
        color: red;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #FD7E14;
        color: ffffff;
    }

    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #fd7e1482;
    }

    .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active {
        background: #FD7E14;
    }


</style>
</head>
<body class="theme-gray layout-fullwidth">

<!-- Overlay For Sidebars -->
<div class="overlay"></div>

<div id="wrapper">

    <?php include 'topbar.php';?>

    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        
                    </ul>
                </div>
            </div>
        </div>     
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-3 col-md-12">
                    <div class="card">
                        <div class="header bline">
                            <h2>Dashboard</h2>
                        </div>
                        <div class="body theme-orange">
                            <nav id="left-sidebar-nav" class="sidebar-nav" style="margin: 0;">
                                <ul id="main-menu" class="metismenu">
                                    <li><a href="home.php" class="sidebar"><i class="icon-home"></i><span>Home</span></a></li>
                                    <li>
                                        <a href="#" class="has-arrow"><i class="icon-docs"></i><span>Application Form</span></a>
                                        <ul class="collapse in">
                                            <li><a href="user_barangay_id.php">Barangay ID</a></li>
                                            <li><a href="user_barangay_clearance.php">Barangay Clearance</a></li>
                                            <li class="active"><a href="user_barangay_indigency.php">Barangay Indigency</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="user_blotter.php"><i class="fa fa-legal"></i><span>Blotter</span></a></li>
                                    <li><a href="user_request_status.php"><i class="icon-refresh"></i><span>Request Status</span></a></li>
                                    <li><a href="user_setting.php"><i class="icon-settings"></i><span>Setting</span></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Barangay Indigency Form</h2>
                            <?php if(isset($_GET['message'])) { ?>
                                <div class="alert mt-2 mb-0 alert-info"><?=urldecode(base64_decode($_GET['message']))?></div>
                            <?php } ?>
                        </div>
                        <div class="body wizard_validation">
                            <form id="wizard_with_validation" class="indigency_form" method="POST">
                            <input type="hidden" id="trigger" name="trigger">
                                <h3>Fill Up Form</h3>
                                <fieldset>
                                    <div class="row clearfix justify-content-center">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                              <label for="name">Name <span class="asterisk">*</span></label>
                                              <input type="name" class="form-control" id="name" name="name" placeholder="Fullname" required>
                                            </div>
                                            <div class="form-group">
                                              <label for="address">Address <span class="asterisk">*</span></label>
                                              <input type="address" class="form-control" id="address" name="address" placeholder="Complete address" required>
                                            </div>
                                            <div class="form-group">
                                              <label for="dob">Date of Birth <span class="asterisk">*</span></label>
                                              <input class="form-control" type="date" id="datepicker" name="dob" required>
                                            </div>
                                            <div class="form-group">
                                              <label for="purpose">Purpose <span class="asterisk">*</span></label>
                                              <input type="purpose" class="form-control" id="purpose" name="purpose" placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label>Requirements<span class="asterisk">*</span></label><br>
                                                 <label style="font-weight: normal;">Any of these following ID with latest address:</label> 
                                                 <label style="font-style: italic; font-weight: normal;">*Make sure it is addressed to barangay 168 to avoid declined applications.</label><br>
                                                 <p>
                                                    •   PhilHealth ID<br>
                                                    •   TIN ID<br>
                                                    •   Social Security System (SSS) Card<br>
                                                    •   Government Service Insurance System (GSIS) Card<br>
                                                    •   Unified Multi-Purpose Identification (UMID) Card<br>
                                                    •   Land Transportation Office (LTO) Driver’s License.<br>
                                                    •   Senior Citizen ID<br>
                                                    •   School ID<br>
                                                    •   Voter's ID<br>
                                                    •   Postal ID <br>
                                                 </p>
                                                 <label style="font-style: italic; font-weight: normal;">*For tenant (Nangungupahan) - if you are a tenant and have been here for at least 6 months, the homeowners or HOA needs to endorse you before to apply barangay id and other certificates.</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="card">
                                                    <div class="header">
                                                        <h2>Upload here<small>*please upload here the following requirements.</small></h2>
                                                    </div>
                                                    <div class="body">
                                                        <input type="file" class="dropify">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <h3>Payment Method</h3>
                                <fieldset>
                                    <div class="row clearfix justify-content-center" style="padding-top: 15%;">
                                        <div class="col-lg-7 col-md-12">
                                            <img class="mx-auto d-block" src="assets/images/free.png" style="width: 50%; height: auto;"><br>
                                            <h1 style="text-align: center;">No Fee!</h1>
                                            <h5 style="text-align: center;">Barangay Indigency is Free, you can proceed now to the next step.</h5>
                                        </div>
                                    </div>
                                </fieldset>
                                <h3>Finish</h3>
                                <fieldset>
                                    <div class="row clearfix justify-content-center" style="padding-top: 15%;">
                                        <div class="col-lg-7 col-md-12">
                                            <img class="mx-auto d-block" src="assets/images/done.png" style="width: 50%; height: auto;"><br>
                                            <h1 style="text-align: center;">Done!</h1>
                                            <h5 style="text-align: center;">Barangay will notify you or you can check in <a href="#" style="text-decoration: underline;">Request Status</a>, once the application is ready to pick up, <br><br><br>Please click finish to submit.</h5>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>  
            </div>    
        </div>
    </div>


<!-- Javascript -->
<script src="../admin/assets/bundles/libscripts.bundle.js"></script>    
<script src="../admin/assets/bundles/vendorscripts.bundle.js"></script>

<script src="../assets/vendor/sweetalert/sweetalert.min.js"></script><!-- SweetAlert Plugin Js --> 

<script src="../admin/assets/bundles/mainscripts.bundle.js"></script>
<script src="../admin/assets/js/pages/ui/dialogs.js"></script>

<!-- Stepper -->
<script src="../assets/vendor/jquery-validation/jquery.validate.js"></script><!-- Jquery Validation Plugin Css -->
<script src="../assets/vendor/jquery-steps/jquery.steps.js"></script><!-- JQuery Steps Plugin Js -->


<!-- Side nav -->
<script src="../assets/vendor/nestable/jquery.nestable.js"></script><!-- Jquery Nestable -->
<script src="../admin/assets/js/pages/ui/sortable-nestable.js"></script> 

<!-- Upload image -->
<script src="../assets/vendor/dropify/js/dropify.min.js"></script>
<script src="../admin/assets/js/pages/forms/dropify.js"></script>


<script>
$(function () {
    "use strict";
    //Horizontal form basic
    $('#wizard_horizontal').steps({
        headerTag: 'h2',
        bodyTag: 'section',
        transitionEffect: 'slideLeft',
        onInit: function (event, currentIndex) {
            setButtonWavesEffect(event);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        }
    });

    //Vertical form basic
    $('#wizard_vertical').steps({
        headerTag: 'h2',
        bodyTag: 'section',
        transitionEffect: 'slideLeft',
        stepsOrientation: 'vertical',
        onInit: function (event, currentIndex) {
            setButtonWavesEffect(event);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        }
    });

    //Advanced form with validation
    var form = $('#wizard_with_validation').show();
    form.steps({
        headerTag: 'h3',
        bodyTag: 'fieldset',
        transitionEffect: 'slideLeft',        
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) { return true; }

            if (currentIndex < newIndex) {
                form.find('.body:eq(' + newIndex + ') label.error').remove();
                form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
            }

            form.validate().settings.ignore = ':disabled,:hidden';
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ':disabled';
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            $('#trigger').val(1);
            $('.indigency_form').submit()
        }
    });

    form.validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        rules: {
            'confirm': {
                equalTo: '#password'
            }
        }
    });
});

function setButtonWavesEffect(event) {
    $(event.currentTarget).find('[role="menu"] li a').removeClass('');
    $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('');
}

</script>

</body>
</html>