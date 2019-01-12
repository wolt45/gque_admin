<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

        <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
               <div class="clearfix"></div>

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>User Profile</h2>
                   
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <div class="row">
                      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_content">
                            <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <img src="../../dump_px/{{userItem.foto}}" style="max-width: 150px; border: 1px solid #000;">
                                <h4>{{userItem.userPxName}}</h4>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               
                                <span class="fa fa-map-marker"></span> {{userItem.pxAddress}}
                                <br>
                                <span class="fa fa-briefcase"></span> {{userItem.Occupation}}
                                <br>
                                <span class="fa fa-envelope-o"></span> {{userItem.Email}}
                              </div>
                            </div>
                             <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button class="btn btn-success" data-toggle="modal" data-target="#accountModal">
                                  <span class="fa fa-edit"></span> Edit Account
                                </button>
                                <button class="btn btn-warning" data-toggle="modal" data-target="#pinModal">
                                  <span class="fa fa-edit"></span> Change Signature PIN
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4 col-sm-12">
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- /page content -->

    <!-- accountModal -->
    <div id="accountModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- accountModal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Account</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tr ng-show="!newAccountShow">
                  <td>
                    Old Username
                  </td>
                  <td>
                    <input type="text" name="" class="form-control" ng-model="oldAccountObj.username">
                  </td>
                  <td>
                    Old Password
                  </td>
                  <td>
                    <input type="password" name="" class="form-control" ng-model="oldAccountObj.userPassword">
                  </td>
                  <td>
                    <button class="btn btn-success btn-sm" ng-click="checkAccount(oldAccountObj)">
                      GO
                    </button>
                  </td>
                </tr>
                <tr ng-show="newAccountShow">
                  <td>
                    New Username
                  </td>
                  <td>
                    <input type="text" name="" class="form-control" ng-model="newAccountObj.username">
                  </td>
                  <td>
                    New Password
                  </td>
                  <td>
                    <input type="password" name="" class="form-control" ng-model="newAccountObj.userPassword">
                  </td>
                  <td>
                    <button class="btn btn-primary btn-sm" ng-click="renewAccount(newAccountObj)">
                      Save
                    </button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">
              <span class="glyphicon glyphicon-ban-circle"></span>
              Close
            </button>
          </div>
        </div>

      </div>
    </div>


    <!-- pinModal -->
    <div id="pinModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- pinModal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Account</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tr ng-show="!newPINShow">
                  <td>
                    Old PIN
                  </td>
                  <td>
                    <input type="password" name="" class="form-control" ng-model="oldPIN">
                  </td>
                 
                  <td width="1%" nowrap>
                    <button class="btn btn-success btn-sm" ng-click="checkPxDsigAcct(oldPIN)">
                      GO
                    </button>
                  </td>
                </tr>
                <tr ng-show="newPINShow">
                  <td>
                    New PIN
                  </td>
                  <td>
                    <input type="password" name="" class="form-control" ng-model="newPIN">
                  </td>
                 
                  <td width="1%" nowrap>
                    <button class="btn btn-primary btn-sm" ng-click="renewCheckPxDsigAcct(newPIN)">
                      Save
                    </button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">
              <span class="glyphicon glyphicon-ban-circle"></span>
              Close
            </button>
          </div>
        </div>

      </div>
    </div>

   
    <?php
      include "footer.php";
    ?>
    
  </div>
</div>
