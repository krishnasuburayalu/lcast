<!DOCTYPE html>
<!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en" ng-app="adhf">
    <head>
        <meta charset="UTF-8">
        <title>Doctor & Hospital Finder</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="{{ asset("/css/ionicons.min.css")}}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
                                            page. However, you can choose any other skin. Make sure you
                                            apply the skin class to the body tag so the changes take effect.
        -->
        <link href="{{ asset("/bower_components/AdminLTE/dist/css/skins/_all-skins.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/css/dhf.css")}}" rel="stylesheet" type="text/css" />
        <!-- jQuery 2.1.3 -->
        <script src="{{ asset ("/bower_components/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
        <!-- angularjs 1.4.5 JS -->
        <script src="{{ asset ("/js/angular.1.4.6.min.js") }}"></script>
        <script src="{{ asset ("/js/angular-ui-router.min.js") }}"></script>
        <script src="{{ asset ("/js/angular-route.1.4.5.min.js") }}"></script>
        <script src="{{ asset ("/js/angular-sanitize.1.4.6.min.js") }}"></script>
        <script src="{{ asset ("/js/angular-animate.1.4.6.min.js") }}"></script>
        <script src="{{ asset ("/js/angular-cookies.1.4.6.min.js") }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset ("/js/autocomplete.js") }}" type="text/javascript"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="sidebar-mini skin-yellow-light fixed sidebar-collapse" >
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">
                <a href="/" class="logo"><b>D&HF</b> <small>Beta</small></a> 
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Navbar Right Menu -->
                    <!-- Header Navbar: style can be found in header.less -->

                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-header">
                    <ul class="nav navbar-nav ">
                        <li class="   ">
                    <h4 class="hidden-xs lead dhf-lead"> Admin</h4>
              </li>
              </ul></div>
                </nav>
            </header>
            <!-- =============================================== -->
            <!-- Left side column. contains the sidebar -->
            <span ng-controller="HomeCtrl">
            <aside class="main-sidebar"><section class="sidebar" ui-view="sidebar"></section></aside>
            <div class="content-wrapper reveal-animation" ui-view="content"></div>
            </span>
 
  <!-- /.content-wrapper -->
                <!-- Main Footer -->
                <footer class="main-footer">
                    <!-- To the right -->
                    <!-- Default to the left -->
                    Copyright © 2015 <a href="http://horizonblue.com">Horizon Blue Cross Blue Shield of New Jersey</a>. All rights reserved.
                </footer>
                </div><!-- ./wrapper -->
                <!-- REQUIRED JS SCRIPTS -->
                <!-- Bootstrap 3.3.2 JS -->
                <script src="{{ asset ("/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
                <!-- AdminLTE App -->
                <!-- AdminLTE App -->
                <script src="{{ asset ("/bower_components/AdminLTE/dist/js/app.min.js") }}" type="text/javascript"></script>
                <script src="{{ asset ("/bower_components/AdminLTE/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script>
                <script src="{{ asset ("/js/pagination.js") }}" type="text/javascript"></script>
                <script src="{{ asset ("/js/admin-app.js") }}" type="text/javascript"></script>
                <!--script src="http://maps.googleapis.com/maps/api/js"></script-->
                <!-- Optionally, you can add Slimscroll and FastClick plugins.
                                                                        Both of these plugins are recommended to enhance the
                user experience -->
            </body>
        </html>