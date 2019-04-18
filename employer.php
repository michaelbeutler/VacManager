<?php
require('includes/class/Autoload.php');

Session::start();
if (!User::check_login(new Database()) || !EmployerPriv::check_employer_priv(new Database(), User::getCurrentUser(new Database())->employer, new Priv(Priv::GENERAL))) {
    header("Location: login.html?next=index.php");
    die();
}

$database = new Database();
$user = User::getCurrentUser($database);
$employerPriv = EmployerPriv::construct_user($database, $user, $user->employer);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Employer">
    <meta name="author" content="Michael Beutler">

    <link rel="shortcut icon" href="img/favicon_1.ico">

    <title>iperka - Employer</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">

    <!--calendar css-->
    <link href="assets/fullcalendar/fullcalendar.css" rel="stylesheet" />
    <link href="css/bootstrap-reset.css" rel="stylesheet">

    <!--Animation css-->
    <link href="css/animate.css" rel="stylesheet">

    <!--Icon-fonts css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/ionicon/css/ionicons.min.css" rel="stylesheet" />

    <!-- Plugins css -->
    <link href="assets/notifications/notification.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/sweet-alert/sweet-alert.min.css">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->

</head>


<body>

    <!-- Aside Start-->
    <aside class="left-panel">

        <!-- brand -->
        <div class="logo">
            <a href="index.php" class="logo-expanded">
                <i class="ion-social-buffer"></i>
                <span class="nav-label">iperka.com</span>
            </a>
        </div>
        <!-- / brand -->

        <!-- Navbar Start -->
        <nav class="navigation">
            <ul class="list-unstyled">
                <li><a href="index.php"><i class="ion-home"></i> <span class="nav-label">Dashboard</span></a></li>
                <!--<li><a href="calendar.php"><i class="ion-calendar"></i> <span class="badge badge-warning float-right">NEW</span><span class="nav-label">Calendar</span></a></li>-->
                <li><a href="vacation.php"><i class="fa fa-star"></i> <span class="nav-label">Vacation</span></a></li>
                <li><a href="chart.php"><i class="ion-stats-bars"></i> <span class="badge badge-warning float-right">NEW</span><span class="nav-label">Charts</span></a></li>
                <?php if (EmployerPriv::check_employer_priv(new Database(), User::getCurrentUser(new Database())->employer, new Priv(Priv::GENERAL))) {
                    echo '<li><a href="employer.php"><i class="fa fa-building"></i> <span class="badge badge-warning float-right">NEW</span><span class="nav-label">Employer</span></a></li>';
                } ?>
                <li><a href="account.php"><i class="fa fa-lock"></i> <span class="badge badge-warning float-right">NEW</span><span class="nav-label">Account</span></a></li>
                <?php if ($_SESSION['user_is_admin'] == 1) {
                    echo '
                <li><a href="admin.php"><i class="fa fa-gavel"></i> <span class="nav-label">Admin</span></a></li>
                ';
                } ?>
            </ul>
        </nav>

    </aside>
    <!-- Aside Ends-->


    <!--Main Content Start -->
    <section class="content">

        <!-- Header -->
        <header class="top-head container-fluid">
            <button type="button" class="navbar-toggle pull-left">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Right navbar -->
            <ul class="list-inline navbar-right top-menu top-right-menu">
                <!-- user login dropdown start-->
                <li class="dropdown text-center">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <!-- <img alt="" src="img/avatar-2.jpg" class="img-circle profile-img thumb-sm"> -->
                        <span class="username">
                            <?php echo $_SESSION['user_username']; ?></span> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu extended pro-menu fadeInUp animated" taincludesdex="5003" style="overflow: hidden; outline: none;">
                        <!-- <li><a href="profile.html"><i class="fa fa-briefcase"></i>Profile</a></li>
                            <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                            <li><a href="#"><i class="fa fa-bell"></i> Friends <span class="label label-info pull-right mail-info">5</span></a></li> -->
                        <li><a href="includes/logout.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
                    </ul>
                </li>
                <!-- user login dropdown end -->
            </ul>
            <!-- End right navbar -->

        </header>
        <!-- Header Ends -->

        <!-- Page Content Start -->
        <!-- ================== -->

        <div class="wraper container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs">
                        <li class="">
                            <a href="#list-tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                <span class="hidden-xs">List</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="#calendar-tab" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs">Calendar</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="list-tab">
                            <?php if (EmployerPriv::check_employer_priv(new Database(), User::getCurrentUser(new Database())->employer, new Priv(Priv::CAN_ACCEPT))) echo '
                                <br>
                                <h4>' . $_SESSION['employer_name'] . ' - Vacation requests</h4>
                                <table class="table table-striped table-bordered" id="tableVacationRequests">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Days</th>
                                            <th>Request date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6">no requests pending</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                            '; ?>
                        </div>
                        <div class="tab-pane active" id="calendar-tab">
                            <div class="row">
                                <div id="calendar" class="col-lg-7 col-sm-12"></div>
                                <div class="col-lg-5 col-sm-12">
                                    <table class="table table-striped table-bordered" id="tableVacationRequests1">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Date</th>
                                                <th>Days</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4">no requests pending</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->
        </div>
        <!-- Page Content Ends -->
        <!-- ================== -->

        <!-- Footer Start -->
        <footer class="footer">
            <?php echo date('Y'); ?> © iperka.com.
        </footer>
        <!-- Footer Ends -->



    </section>
    <!-- Main Content Ends -->


    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="js/pace.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="js/jquery.app.js"></script>
    <script src="assets/sweet-alert/sweet-alert.min.js"></script>
    <script src="assets/sweet-alert/sweet-alert.init.js"></script>

    <?php if (EmployerPriv::check_employer_priv(new Database(), User::getCurrentUser(new Database())->employer, new Priv(Priv::GENERAL))) echo '
        <script src="assets/fullcalendar/moment.min.js"></script>
        <script src="assets/fullcalendar/fullcalendar.min.js"></script>
        <script src="assets/fullcalendar/calendar-employer-init.js"></script>
        <script src="js/getNotAcceptedVacations.js"></script>
    '; ?>


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136503205-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-136503205-1');
    </script>

</body>

</html>