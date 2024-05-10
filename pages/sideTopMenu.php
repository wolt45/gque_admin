<div ng-controller="sideTopCtrl">
<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a ui-sref="dashboard" class="site_title"><i class="fa fa-hospital-o"></i> <span>GUSTILO Q</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="../dump_px/{{userItem.foto}}" alt="User Img." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2>{{userItem.shortUserPxName}}</h2>
      </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <!-- <h3>General</h3> -->
        <ul class="nav side-menu">
          <li>
            <a ui-sref="gque_opd" >
              <i class="fa fa-user-md"></i> 
                OPD 
            </a>
          </li>

<!--           <li>
            <a ui-sref="gque_get">
              <i class="fa fa-user-md"></i> 
                GET QUE
            </a>
          </li> -->

        </ul>
      </div>
    </div>

    <div style="position: fixed; text-align: right; bottom: 40%; right: 0%;" ng-controller="topBottomCtrl" id="DontPrint">
    
          <a ng-click="gotoTop()">
              <!-- <small style="font-size: 12px;">Top</small> -->
              <br>
              <span class="glyphicon glyphicon-triangle-top" style="font-size: 24px;"></span>
          </a>
          <br>

          <br>
          <a ng-click="gotoBottom()">
              <span class="glyphicon glyphicon-triangle-bottom" style="font-size: 24px;"></span>
              <br>
              <!-- <small style="font-size: 12px;">Bottom</small> -->
          </a>
          
      </div>

    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout" ng-click="logout()">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>


  <!-- top navigation -->
  <div class="top_nav" id="DontPrint">
      <div class="nav_menu">
          <nav>
              <div class="nav toggle">
                  <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <ul class="nav navbar-nav navbar-right">
                  <li class="">
                      <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          <img src="../dump_px/{{userItem.foto? userItem.foto:'default.jpg'}}" alt="">
                          {{userItem.shortUserPxName}}
                          <span class="fa fa-angle-down"></span>
                      </a>
                      <ul class="dropdown-menu dropdown-usermenu pull-right">
                          <li>
                            <a href="../rbgmain" ng-click="logout()">
                              <i class="fa fa-sign-out pull-right"></i> 
                              Log Out
                            </a>
                          </li>
                      </ul>
                  </li>
              </ul>
          </nav>
      </div>
  </div>
  <!-- /top navigation -->

  <script type="text/ng-template" id="warning-dialog.html">
      <div class="modal-header">
       <h3>You're Idle. Do Something!</h3>
      </div>
      <div idle-countdown="countdown" ng-init="countdown=5" class="modal-body">
       <uib-progressbar max="5" value="5" animate="false" class="progress-striped active">You'll be logged out in {{countdown}} second(s).</uib-progressbar>
      </div>
    </script>

    <script type="text/ng-template" id="timedout-dialog.html">
      <div class="modal-header">
       <h3>You've Timed Out!</h3>
      </div>
      <div class="modal-body">
       <p>
          You were idle too long.
       </p>
     </div>
    </script>

    
</div>