<div ng-controller="sideTopCtrl">
<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a ui-sref="dashboard" class="site_title"><i class="fa fa-hospital-o"></i> <span>GMMR-CENTRAL</span></a>
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
          <li ng-show="registrationSidemenu">
            <a href="../rbgregv3" >
              <i class="fa fa-registered"></i> 
                Registration 
            </a>
          </li>

          <li ng-show="opdOrthopedicsSidemenu">
            <a href="../gmmr2">
              <i class="fa fa-user-md"></i> 
                OPD Orthopedics
            </a>
          </li>
          
          <li ng-show="opdSidemenu">
            <a href="../rbg_genmed">
              <i class="fa fa-user-md"></i> 
                OPD
            </a>
          </li>
          

          <li ng-show="inPatientSidemenu">
            <a href="../gmmr3">
              <i class="fa fa-user-md"></i> 
                In-Patient
            </a>
          </li>

          <li ng-show="diagnostixSidemenu">
            <a href="../diagnostix/www">
              <i class="fa fa-medkit"></i> 
                Diagnostix 
            </a>
          </li>


          <li >
            <a href="../telemetry">
              <i class="fa fa-medkit"></i>
              Telemetry
            </a>
          </li>


          <!-- <li class="sub_menu">
            <a>
              <i class="fa fa-edit"></i> 
              OR Manager  <span class="fa fa-chevron-down"></span>
            </a>
                  

            <ul class="nav child_menu">
              <li ng-show="mediaManagerSidemenu">
                <a class="btn btn-danger" ui-sref="operatingroomDisinfectChecklist" >
                    OR Disinfection Checklist
                </a>
              </li>
              <li ng-show="mediaManagerSidemenu">
                <a class="btn btn-danger" ui-sref="operatingroomDisinfectChecklist" >
                    Surgery Schedules
                </a>
              </li>
            </ul>
          </li> -->


          <li ng-show="mediaManagerSidemenu || bulkUploaderSidemenu || icd10CodeSidemenu || rvsCodeSidemenu || billingCodeSidemenu">
            <a>
              <i class="fa fa-edit"></i> 
              Maintenance <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li ng-show="mediaManagerSidemenu">
                <a class="btn btn-success" href="../SoftMedlib/MedLibMain.php">
                  Media Manager
                </a>
              </li>

              <li ng-show="bulkUploaderSidemenu">
                <a class="btn btn-success" href="../SoftMedlib">
                  Bulk Uploader
                </a>
              </li>

              <li ng-show="icd10CodeSidemenu">
                <a class="btn btn-success" href="../icd10">
                  ICD10 Codes
                </a>
              </li>

              <li ng-show="rvsCodeSidemenu">
                <a class="btn btn-success" href="../PhilRVS">
                  RVS Codes
                </a>
              </li>

              <li ng-show="billingCodeSidemenu">
                <a class="btn btn-success" href="../billingmgr">
                  Billing Codes
                </a>
              </li>

              <li ng-show="adminPanelSidemenu">
                <a class="btn btn-success" href="../adminPanel">
                  Admin Panel
                </a>
              </li>

              <li ng-show="adminPanelSidemenu">
                <a class="btn btn-success" ui-sref="drugsManager">
                  Drugs Manager
                </a>
              </li>

              <li>
                <a class="btn btn-success">
                  <i class="fa fa-edit"></i> 
                  OR Manager  <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                  <li>
                    <a ui-sref="orDisinfectCheckList" >
                        OR Disinfection Checklist
                    </a>
                  </li>
                </ul>
              </li>

              <li>
                <a class="btn btn-success">
                  <i class="fa fa-edit"></i> 
                  Sys Config  <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                  <li>
                    <a ui-sref="followUpTickler" >
                        Follow up Ticler
                    </a>
                  </li>
                </ul>
              </li>

             

              <!-- <li ng-show="userTypeRID == 1">
                <a href="#">
                  Back-up
                </a>
              </li> -->
              
            </ul>
          </li>

          
        </ul>
      </div>
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
                          <span class=" fa fa-angle-down"></span>
                      </a>
                      <ul class="dropdown-menu dropdown-usermenu pull-right">
                          <li>
                            <a ui-sref="profile"> 
                              Profile
                            </a>
                          </li>
                         <!--  <li>
                              <a href="javascript:;">
                                  <span class="badge bg-red pull-right">50%</span>
                                  <span>Settings</span>
                              </a>
                          </li> -->
                          <li>
                              <a ui-sref="requestForModAlter">
                                  <span>Request for M/A, <small>Comments & Suggestions</small></span>
                              </a>
                          </li>
                          <li>
                              <a ui-sref="inbox">
                                  <span>Messages</span>
                              </a>
                          </li>
                          <li>
                            <a ui-sref="aboutUs">
                              About Us
                            </a>
                          </li>
                          <li>
                            <a href="../rbgmain" ng-click="logout()">
                              <i class="fa fa-sign-out pull-right"></i> 
                              Log Out
                            </a>
                          </li>
                      </ul>
                  </li>

                  <li role="presentation" class="dropdown">
                      <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                          <i class="fa fa-bell-o"></i>
                          <span class="badge bg-orange">{{notifItemSum}}</span>
                      </a>
                      <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                          <li ng-repeat="notifItems in notifItem">
                              <a>
                                  <span class="image">
                                      <img src="../dump_px/{{notifItems.foto ? notifItems.foto : 'default.jpg'}}" alt="Profile Image" />
                                  </span>
                                  <span>
                                      <span>{{notifItems.pxName}}</span>
                                      <!-- <span class="time">3 mins ago</span> -->
                                  </span>
                                  <span class="message">
                                      {{notifItems.Event}}
                                  </span>
                              </a>
                          </li>
                          <li ng-repeat="notifItemBirthday in notifItemBirthdays">
                              <a>
                                  <span class="image">
                                      <img src="../dump_px/{{notifItemBirthday.foto ? notifItemBirthday.foto : 'default.jpg'}}" alt="Profile Image" />
                                  </span>
                                  <span>
                                      <span class="fa fa-birthday-cake red"></span>
                                      <b>{{notifItemBirthday.pxName}}</b>
                                  </span>
                              </a>
                          </li>
                          <li ng-repeat="NotificationsRequestForModifAlterList in NotificationsRequestForModifAlterListObj">
                            <a ui-sref="requestForModAlter">
                              <span class="image"><img src="../dump_px/{{NotificationsRequestForModifAlterList.foto ? NotificationsRequestForModifAlterList.foto : 'default.jpg'}}" alt="Profile Image" /></span>
                              <span>
                                <span>{{NotificationsRequestForModifAlterList.requestType}}</span>
                                <span class="time">3 mins ago</span>
                              </span>
                              <span class="message">
                                {{NotificationsRequestForModifAlterList.requestDescription}}
                              </span>
                            </a>
                          </li>
                          <li>
                              <div class="text-center">
                                  <a>
                                      <strong>See All Alerts</strong>
                                      <i class="fa fa-angle-right"></i>
                                  </a>
                              </div>
                          </li>
                      </ul>
                  </li>

                  <li role="presentation" class="dropdown">
                      <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                          <i class="fa fa-envelope-o"></i>
                          <span class="badge bg-green">{{messageItemSum}}</span>
                      </a>
                      <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                          <li ng-repeat="NewMessagesList in NewMessagesListObj">
                              <a ng-click="viewMessages(NewMessagesList)">
                                  <span class="image">
                                      <img src="../../dump_px/{{NewMessagesList.fotoSender}}" alt="Profile Image" />
                                  </span>
                                  <span>
                                      <span>{{NewMessagesList.pxNameSender}}</span>
                                      <!-- <span class="time">3 mins ago</span> -->
                                  </span>
                                  <span class="message" >
                                      {{NewMessagesList.tempmessageContent | limitTo: 20 }} {{tempmessageContent.length < 20 ? '' : '...'}}
                                  </span>
                              </a>
                          </li>
                          <li>
                              <div class="text-center">
                                  <a ui-sref="inbox">
                                      <strong>See All Messages</strong>
                                      <i class="fa fa-angle-right"></i>
                                  </a>
                              </div>
                          </li>
                      </ul>
                  </li>

                  <li role="presentation" class="dropdown">
                      <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                          <i class="fa fa-users"></i>
                          <span class="badge bg-green">{{notifFollowItemSum}}</span>
                      </a>
                      <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu" style="overflow: scroll; max-height: 600px;">
                          <li ng-repeat="NotificationsFollowUpSchedList in NotificationsFollowUpSchedListObj">
                              <a>
                                  <span class="image">
                                      <img src="../dump_px/{{NotificationsFollowUpSchedList.foto}}" alt="Profile Image" />
                                  </span>
                                  <span>
                                      <span>{{NotificationsFollowUpSchedList.pxName}}</span>
                                      <!-- <span class="time">3 mins ago</span> -->
                                  </span>
                                  <span class="message">
                                      {{NotificationsFollowUpSchedList.followUpDate | date:"longDate"}}
                                  </span>
                              </a>
                          </li>
                          <li>
                              <div class="text-center">
                                  <a ui-sref="followUpTickler">
                                      <strong>See All Follow-up</strong>
                                      <i class="fa fa-angle-right"></i>
                                  </a>
                              </div>
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