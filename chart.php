<?php
require('includes/class/Autoload.php');

Session::start();
if (!User::check_login(new Database())) {
    header("Location: login.html?next=chart.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Charts">
    <meta name="author" content="Michael Beutler">

    <link rel="shortcut icon" href="img/favicon_1.ico">

    <title>iperka - Chart</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
                <li class="active"><a href="chart.php"><i class="ion-stats-bars"></i> <span class="badge badge-warning float-right">NEW</span><span class="nav-label">Charts</span></a></li>
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
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Charts for <?php echo date("Y"); ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div id="canvas-holder" class="col-md-8">
                                    <canvas id="canvas-area-bar"></canvas>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div id="canvas-holder" class="col-md-12">
                                            <canvas id="chart-area"></canvas>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div id="canvas-holder" class="col-md-12">
                                            <canvas id="chart-area2"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>


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
    <script src="assets/chartjs/chart.min.js"></script>
    <script src="js/getContingent.js"></script>

    <script>
        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        $(document).ready(function() {
            getContingent(<?php echo date('Y'); ?>, function(vacation) {
                var config = {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: [
                                vacation.data.used_days,
                                vacation.data.left_days
                            ],
                            backgroundColor: [
                                window.chartColors.blue,
                                window.chartColors.green
                            ],
                            label: 'Vacations'
                        }],
                        labels: [
                            'Used Days',
                            'Left Days'
                        ]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: true,
                            position: 'right'
                        }
                    }
                };


                getNotAccepted(function(data) {
                    var config2 = {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: [
                                    data.accepted,
                                    data.pending,
                                    data.refused,
                                    data.canceled
                                ],
                                backgroundColor: [
                                    window.chartColors.blue,
                                    window.chartColors.green,
                                    window.chartColors.red,
                                    window.chartColors.orange
                                ],
                                label: 'Vacations'
                            }],
                            labels: [
                                'Accepted',
                                'Pending',
                                'Refused',
                                'Canceled'
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: true,
                                position: 'right'
                            }
                        }
                    };

                    var ctx2 = document.getElementById('chart-area2').getContext('2d');
                    window.myPie = new Chart(ctx2, config2);
                })


                var ctx = document.getElementById('chart-area').getContext('2d');
                window.myPie = new Chart(ctx, config);

                var colorNames = Object.keys(window.chartColors);
            })

            var colorNames = Object.keys(window.chartColors);

            getMonthData(function(data) {
                var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                var color = Chart.helpers.color;
                var barChartData = {
                    labels: MONTHS,
                    datasets: [{
                        label: '<?php echo date('Y'); ?> accepted',
                        backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                        borderColor: window.chartColors.green,
                        borderWidth: 1,
                        data: [
                            data.accepted[0],
                            data.accepted[1],
                            data.accepted[2],
                            data.accepted[3],
                            data.accepted[4],
                            data.accepted[5],
                            data.accepted[6],
                            data.accepted[7],
                            data.accepted[8],
                            data.accepted[9],
                            data.accepted[10],
                            data.accepted[11]
                        ]
                    }, {
                        label: '<?php echo date('Y'); ?> pending',
                        backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                        borderColor: window.chartColors.red,
                        borderWidth: 1,
                        data: [
                            data.pending[0],
                            data.pending[1],
                            data.pending[2],
                            data.pending[3],
                            data.pending[4],
                            data.pending[5],
                            data.pending[6],
                            data.pending[7],
                            data.pending[8],
                            data.pending[9],
                            data.pending[10],
                            data.pending[11]
                        ]
                    }]

                };


                var ctx3 = document.getElementById('canvas-area-bar').getContext('2d');
                window.myBar = new Chart(ctx3, {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        },
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Vacations per Month'
                        }
                    }
                });

            });
        });

        function getNotAccepted(callback) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./includes/new/vacation.inc.php",
                data: {
                    action: 'GET_ALL_VACATIONS',
                    view: 'PENDING'
                },
                async: true,
                contentType: "application/json; charset=utf-8",
                success: function(data) {
                    callback(data.data);
                }
            });
        }

        function getMonthData(callback) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "./includes/new/vacation.inc.php",
                data: {
                    action: 'GET_ALL_VACATIONS',
                    view: 'MONTH_STATISTIC'
                },
                async: true,
                contentType: "application/json; charset=utf-8",
                success: function(data) {
                    callback(data.data);
                }
            });
        }
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136503205-1"></script>
    <script>
        $(document).ready(function() {
            if (document.addEventListener) {
                document.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                }, false);
            } else {
                document.attachEvent('oncontextmenu', function() {
                    window.event.returnValue = false;
                });
            }
        })
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-136503205-1');
    </script>

</body>

</html>