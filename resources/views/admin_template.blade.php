<!DOCTYPE html>
<!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en" ng-app="dhf">
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
        <link href="{{ asset("/bower_components/AdminLTE/dist/css/skins/skin-blue.min.css")}}" rel="stylesheet" type="text/css" />
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
    <body class="skin-blue layout-boxed sidebar-collapse" ng-controller="SearchCtrl">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">
                <!-- Logo -->
                <a href="/" class="logo"><b>D&HF</b> <small>Beta</small></a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                    We've Got You Covered. <small>Find Doctors, Hospitals or Other Providers Near You</small>
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content" >
                    <div class="box box-primary "   autoscroll="true">
                        <form ng-submit="doSearch(1)">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Doctor/Hospital Name </label>
                                            <!--input class="form-control my-colorpicker1 colorpicker-element" ng-model="req.lastname" type="text"-->
                                            <autocomplete ng-model="name" data="names" on-type="updateName" attr-input-class="form-control my-colorpicker1 colorpicker-element"></autocomplete>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Specialty / Service</label>
                                                <autocomplete ng-model="specialty_sel" data="specialties" on-type="updateSpecialty" attr-input-class="form-control my-colorpicker1 colorpicker-element"></autocomplete>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Localtion</label>
                                                <autocomplete ng-model="location" data="locations" on-type="updateLocation" attr-input-class="form-control my-colorpicker1 colorpicker-element"></autocomplete>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <br/>
                                            <button  type="submit"  class="btn bg-orange btn-flat margin" ><span class="glyphicon glyphicon-search"></span> Search</button>
                                            <button   class="btn bg-navy btn-flat margin" ng-click="clearSearch()"><span class="glyphicon glyphicon-search"></span> Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </div>
                        </form>
                    </div>
                    <div class="row reveal-animation" ui-view="content"></div>
                </section>
                <!--section class="content"  class="view-animate" ng-include=”partials/list.tpl.html” ></section-->
                </div><!-- /.content-wrapper -->
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
                <script src="{{ asset ("/bower_components/AdminLTE/dist/js/app.min.js") }}" type="text/javascript"></script>
                <!-- AdminLTE App -->
                <script src="{{ asset ("/bower_components/AdminLTE/dist/js/app.min.js") }}" type="text/javascript"></script>
                <script src="{{ asset ("/js/pagination.js") }}" type="text/javascript"></script>
                <script src="{{ asset ("/js/app.js") }}" type="text/javascript"></script>
                <!--script src="http://maps.googleapis.com/maps/api/js"></script-->
                <!-- Optionally, you can add Slimscroll and FastClick plugins.
                                        Both of these plugins are recommended to enhance the
                user experience -->
            </body>
        </html>