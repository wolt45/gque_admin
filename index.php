<!DOCTYPE html>
<html lang="en" ng-app="gmmrApp">
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
       <link rel="icon" href="production/images/img.jpg" type="image/ico" />

      <title>GMMR Central</title>

      <!-- Bootstrap -->
      <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome -->
      <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <!-- NProgress -->
      <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
      <!-- iCheck -->
      <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">

      <!-- bootstrap-progressbar -->
      <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
      <!-- JQVMap -->
      <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
      <!-- bootstrap-daterangepicker -->
      <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

      <!-- FullCalendar -->
      <link href="vendors/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
      <link href="vendors/fullcalendar/dist/fullcalendar.print.css" rel="stylesheet" media="print">

      <!-- Custom Theme Style -->
      <link href="build/css/custom.min.css" rel="stylesheet">

      <!-- JS (load angular, ui-router, and our custom js file) -->

      <script src="lib/angular-1.7.2/angular.min.js"></script>
      <script src="lib/angular-ui-router.js"></script>

      <!-- Route -->
      <script src="common/route/app.js"></script>

      <!-- Service -->
      <script src="common/service/scriptService.js"></script>

      <!-- Directive -->
      <script src="common/directives/directives.js"></script>

      <!-- Controllers -->
      <script src="common/controllers/loginCtrl.js"></script>
      <script src="common/controllers/sideTopCtrl.js"></script>
      <script src="common/controllers/dashboardCtrl.js"></script>
      <script src="common/controllers/requestForModAlterCtrl.js"></script>

      <!-- Factory -->
      <script src="common/factory/factory.js"></script>

  </head>
  <body class="nav-md" >

    <ui-view></ui-view>
    

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="vendors/Flot/jquery.flot.js"></script>
    <script src="vendors/Flot/jquery.flot.pie.js"></script>
    <script src="vendors/Flot/jquery.flot.time.js"></script>
    <script src="vendors/Flot/jquery.flot.stack.js"></script>
    <script src="vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="vendors/moment/min/moment.min.js"></script>
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- FullCalendar -->
    <script src="vendors/fullcalendar/dist/fullcalendar.min.js"></script>
    <!-- Custom Theme Scripts -->
    <!-- <script src="build/js/custom.min.js"></script> -->
	
  </body>
</html>
