<!DOCTYPE html>
<html lang="en" ng-app="gmmrApp">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GMMR Central</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">

    <!-- JS (load angular, ui-router, and our custom js file) -->

    <script src="lib/angular-1.7.2/angular.min.js"></script>
    <script src="lib/angular-ui-router.js"></script>

    <!-- Route -->
    <script src="common/route/loginApp.js"></script>

    <!-- Service -->
    <script src="common/service/scriptService.js"></script>

    <!-- Directive -->
    <script src="common/directives/directives.js"></script>

    <!-- Controllers -->
    <script src="common/controllers/loginCtrl.js"></script>
    <script src="common/controllers/registerCtrl.js"></script>

    <!-- Factory -->
    <script src="common/factory/factory.js"></script>

  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        
        <ui-view></ui-view>
        
      </div>
    </div>
  </body>
</html>
