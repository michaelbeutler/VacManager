<?php
require('bin/checkLogin.php');
if (!check_login()) {
    header("Location: login.html");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard">
    <meta name="author" content="Michael Beutler">

    <link rel="shortcut icon" href="img/favicon_1.ico">

    <title>iperka - Dashboard</title>




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
            <a href="index.html" class="logo-expanded">
                <i class="ion-social-buffer"></i>
                <span class="nav-label">iperka.com</span>
            </a>
        </div>
        <!-- / brand -->

        <!-- Navbar Start -->
        <nav class="navigation">
            <ul class="list-unstyled">
                <li class="active"><a href="index.php"><i class="ion-home"></i> <span class="nav-label">Dashboard</span></a></li>
                <li><a href="index.php"><i class="ion-calendar"></i> <span class="nav-label">Calendar</span></a></li>
                <li><a href="index.php"><i class="fa fa-star"></i> <span class="nav-label">Vacation</span></a></li>
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
                    <ul class="dropdown-menu extended pro-menu fadeInUp animated" tabindex="5003" style="overflow: hidden; outline: none;">
                        <!-- <li><a href="profile.html"><i class="fa fa-briefcase"></i>Profile</a></li>
                            <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                            <li><a href="#"><i class="fa fa-bell"></i> Friends <span class="label label-info pull-right mail-info">5</span></a></li> -->
                        <li><a href="bin/logout.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
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
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-panel widget-style-2 white-bg">
                        <i class="fa fa-star text-warning"></i>
                        <h2 class="m-0 counter" id="vacDaysUsedPanel">ERR</h2>
                        <div>Used vacation days this year</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-panel widget-style-2 white-bg">
                        <i class="ion-checkmark text-success" id="vacDaysLeftIcon"></i>
                        <h2 class="m-0 counter" id="vacDaysLeftPanel">ERR</h2>
                        <div>Vacation days left this year</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-panel widget-style-2 white-bg">
                        <i class="fa fa-building text-info"></i>
                        <h2 class="m-0 counter">
                            <?php echo $_SESSION['employer_shortname'];?>
                        </h2>
                        <div>Employer</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-panel widget-style-2 white-bg">
                        <i class="ion-calendar text-pink"></i>
                        <h2 class="m-0 counter">
                            <?php echo date('Y'); ?>
                        </h2>
                        <div>Year</div>
                    </div>
                </div>
            </div> <!-- end row -->

            <div class="row">

                <div id='calendar' class="col-lg-7"></div>
                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Striped rows Table</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-striped" id="vacationTable">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Ferien</td>
                                                <td>01.01.2019</td>
                                                <td>15.01.2019</td>
                                                <td>14</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <button class="btn btn-default m-b-5" data-toggle="modal" data-target="#vac-add-modal">
                                    <i class="ion-plus"></i> <span style="margin-left: 10px;">Add
                                        Vacation</span> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page end-->

            <div id="vac-add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Add Vacation</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" role="form">

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label class="col-md-2 control-label">Description</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label class="col-md-2 control-label">Start</label>
                                        <div class="col-md-10">
                                            <input type="date" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label class="col-md-2 control-label">End</label>
                                        <div class="col-md-10">
                                            <input type="date" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label class="col-md-2 control-label">Type</label>
                                        <div class="col-md-10">
                                            <select class="form-control">
                                                <option>Ferien</option>
                                                <option>Militär</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info">Save changes</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->


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

    <script src="assets/fullcalendar/moment.min.js"></script>
    <script src="assets/fullcalendar/fullcalendar.min.js"></script>
    <!--dragging calendar event-->

    <script src="assets/fullcalendar/calendar-init.js"></script>

    <script src="js/jquery.app.js"></script>

    <script src="js/getContingent.js"></script>
    <script src="js/index.js"></script>
</body>

</html>