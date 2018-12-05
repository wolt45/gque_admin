<div class="col-md-3 left_col" ng-controller="PXDetailCtrl">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="#" class="site_title"><i class="fa fa-hospital-o"></i> 
                <span>GMMR Central</span>
            </a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <!-- <div class="profile clearfix">
            <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>Dr. Ramon Gustilo</h2>
            </div>
        </div> -->
        <!-- /menu profile quick info -->

        <!-- <br /> -->

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">

                <ul class="nav side-menu">
                  <li ng-show="userTypeRID == 1 || userTypeRID == 2 || userTypeRID == 0 || userTypeRID == 3 || userTypeRID == 4 || userTypeRID == 5">
                    <a href="#" ng-click="toRBGReg()">
                      <i class="fa fa-registered"></i> 
                        Registration 
                    </a>
                  </li>

                  <li ng-show="userTypeRID == 1 || userTypeRID == 2 || userTypeRID == 0">
                    <a href="#" ng-click="toGMMR2()">
                      <i class="fa fa-user-md"></i> 
                        OPD Orthopedics
                    </a>
                  </li>
                  
                  <li ng-show="userTypeRID == 1 || userTypeRID == 2 || userTypeRID == 0">
                    <a href="#" ng-click="toRBGGenMed()">
                      <i class="fa fa-user-md"></i> 
                        OPD
                    </a>
                  </li>
                  

                  <li ng-show="userTypeRID == 1 || userTypeRID == 2 || userTypeRID == 0">
                    <a href="#" ng-click="toGMMR3()">
                      <i class="fa fa-user-md"></i> 
                        IN-Patient
                    </a>
                  </li>

                  <li ng-show="userTypeRID == 1 || userTypeRID == 4 || userTypeRID == 5">
                    <a href="#" ng-click="toDiagnostix()">
                      <i class="fa fa-medkit"></i> 
                        Diagnostix 
                    </a>
                  </li>


                  <li ng-show="userTypeRID == 1 || userTypeRID == 2 || userTypeRID == 0">
                    <a>
                      <i class="fa fa-edit"></i> 
                      Maintenance <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="nav child_menu">
                      <li>
                        <a href="#" ng-click="toMedManager()">
                          Media Manager
                        </a>
                      </li>
                      <li>
                        <a href="#" ng-click="toBulkUploader()">
                          Bulk Uploader
                        </a>
                      </li>
                      <li>
                        <a href="#" ng-click="toICD10()">
                          ICD10 Codes
                        </a>
                      </li>
                      <li>
                        <a href="#" ng-click="toPhilRVS()">
                          RVS Codes
                        </a>
                      </li>
                      <li>
                        <a href="#" ng-click="toBillMgr()">
                          Billing Codes
                        </a>
                      </li>

                      
                      <li ng-show="userTypeRID == 1">
                        <a href="#" ng-click="toBackUp()">
                          Back-up
                        </a>
                      </li>
                      
                      
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
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="../../rbgmain" ng-click="logout()">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>







 

