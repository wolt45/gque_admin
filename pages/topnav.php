<div class="top_nav"  ng-controller="PXDetailCtrl" id="DontPrint">

    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

                        
                        <img src="../../dump_px/{{userItem.foto}}" alt="">
                        {{userItem.shortUserPxName}}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a ui-sref="userProfile"> Profile</a></li>
                        <li>
                            <a href="javascript:;">
                                <span class="badge bg-red pull-right">50%</span>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a ng-click="gotoMessage()">
                                <span>Message</span>
                            </a>
                        </li>
                        <li><a ui-sref="aboutUs">About Us</a></li>
                        <li><a href="../../rbgmain" ng-click="logout()"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>
                <li class="">
                  <a href="#">
                    <i> <img style="max-width: 80px;" src="images/gmmrlogo.png" ></i>
                  </a>
                </li>


                
            </ul>
        </nav>
    </div>
</div>

 <!-- <button type="button" class="btn btn-primary" >Large modal</button> -->

<?php
    // include "labRequestForm.php";
?>
