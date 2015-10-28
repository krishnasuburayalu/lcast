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
    <body class="sidebar-mini skin-blue fixed sidebar-collapse" ng-controller="SearchCtrl">
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
                                <h4 class="hidden-xs lead dhf-lead">D&HF  We've Got You Covered. Find Doctors, Hospitals or Other Providers Near You</h4>
                            </li>
                        </ul></div>
                    </nav>
                </header>
                <!-- =============================================== -->
                <!-- Left side column. contains the sidebar -->
                <aside class="main-sidebar">
                    <!-- sidebar: style can be found in sidebar.less -->
                    <section class="sidebar" ui-view="sidebar">
                    </section>
                    <!-- /.sidebar -->
                </aside>
                <!-- Left side column. contains the logo and sidebar -->
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Main content -->
                    <section class="content" >
                        <div class="box box-primary "   autoscroll="true">
                            <form ng-submit="doSearch(1)">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Plan</label>
                                            <select class="form-control select2" ng-model="req.network" ng-options="plan.val as plan.name for plan in plans" ng-change="doSearch(1)">
                                            </select>

                                        </div>
                                        <div class="col-md-3">

                                            <label>Doctor/Hospital Name </label>
                                            <!--input class="form-control my-colorpicker1 colorpicker-element" ng-model="req.lastname" type="text"-->
                                            <autocomplete ng-model="name" data="names" on-type="updateName" attr-input-class="form-control   my-colorpicker1 colorpicker-element"></autocomplete>

                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3">
                                            <label>Specialty / Service</label>
                                            <autocomplete ng-model="specialty_sel" data="specialties" on-type="updateSpecialty" attr-input-class="form-control  my-colorpicker1 colorpicker-element"></autocomplete>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3">

                                            <label>Location</label>
                                            <autocomplete ng-model="location" data="locations" on-type="updateLocation" attr-input-class="form-control  my-colorpicker1 colorpicker-element"></autocomplete>

                                        </div>
                                        <div class="col-md-3">


                                            <label>Group</label>
                                            <autocomplete ng-model="group_sel" data="groups" on-type="updateGroup" attr-input-class="form-control  my-colorpicker1 colorpicker-element"></autocomplete>
                                        </div>
                                        <div class="col-md-3">

                                            <br/>
                                            <button  type="submit"  class="btn bg-orange btn-flat"  ><span class="fa  fa-search"></span> Search</button>
                                            <button   class="btn bg-navy btn-flat" ng-click="clearSearch()"><span class="fa  fa-refresh"></span> Clear</button>

                                        </div>
                                    </div>
                                    <!-- /.row -->
                                    <loading></loading>
                                </div>
                                <!--div class="box-footer dhf-pad-0">
                                                                                <button  type="submit"  class="btn bg-orange btn-flat margin "  ><span class="fa  fa-search"></span> Search</button>
                                                                                <button   class="btn bg-navy btn-flat margin  " ng-click="clearSearch()"><span class="fa  fa-refresh"></span> Clear</button>
                            </div-->
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