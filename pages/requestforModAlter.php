<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

    <!-- page content -->
    <div class="right_col" role="main">

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="x_panel tile overflow_hidden">
            <div class="x_title">
              <h2>List of all Request for Modification/Alteration of data in GMMR</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a>
                    </li>
                    <li><a href="#">Settings 2</a>
                    </li>
                  </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="text-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#requestforModAlterModal" ng-click="newRequestForModifAlter()">
                  <span class="glyphicon glyphicon-file"></span>
                  New Request
                </button>
              </div>
              <input type="text" name="" class="form-control" placeholder="Search..." ng-model="searchRequestForModifAlter">

              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr>
                      <th>
                        Date Requested
                      </th>
                      <th nowrap>
                        Request Type
                      </th>
                      <th>
                        Description
                      </th>
                      <th>
                        Requested By
                      </th>
                      <th width="1%" nowrap>
                        Status
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="RequestForModifAlterList in RequestForModifAlterListObj | filter: searchRequestForModifAlter" data-toggle="modal" data-target="#requestforModAlterModal" ng-click="editRequestForModifAlter(RequestForModifAlterList)">
                      <td width="1%" nowrap>
                        {{RequestForModifAlterList.dateRequested}}
                      </td>
                      <td width="1%" nowrap>
                        {{RequestForModifAlterList.requestType}}
                      </td>
                      <td>
                        {{RequestForModifAlterList.requestDescription}}
                      </td>
                      <td width="1%" nowrap>
                        {{RequestForModifAlterList.RequestedByPxName}}
                      </td>
                      <td width="1%" nowrap class="text-center">
                        <div ng-if="RequestForModifAlterList.requestStatus == 0" class="bg-green">
                          Pending
                        </div>
                        <div ng-if="RequestForModifAlterList.requestStatus == 1" class="bg-blue">
                          Approved
                        </div>
                        <div ng-if="RequestForModifAlterList.requestStatus == 2" class="bg-red">
                          Disapproved
                        </div>
                       
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>


        

      <!-- Modal -->
      <div id="requestforModAlterModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Request for Modification/Alteration of data in GMMR</h4>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <tr>
                    <td colspan="4">
                      <label class="radio-inline">
                        <input type="radio" name="requestType" value="Create Account" ng-model="RequestForModifAlterObj.requestType">
                        Create Account
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="requestType" value="Modify Data" ng-model="RequestForModifAlterObj.requestType">
                        Modify Data
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="requestType" value="Others" ng-model="RequestForModifAlterObj.requestType">
                        Others
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" nowrap>
                      Description:
                    </td>
                    <td colspan="3">
                      <textarea class="form-control" rows="4" ng-model="RequestForModifAlterObj.requestDescription"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" nowrap>
                      Requested By:
                    </td>
                    <td>
                      <div ng-show="RequestForModifAlterObj.requestedBy > 0">
                        <img style="width: 100px;" src="{{RequestForModifAlterObj.RequestedByPxSign}}">
                        <br>
                        <small>{{RequestForModifAlterObj.RequestedByPxName}}</small>
                      </div>
                      <div class="input-group" id="DontPrint" ng-show="RequestForModifAlterObj.requestedBy == 0">
                        <input type="password" class=" form-control" placeholder="PIN..." ng-model="RequestedByPIN">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" ng-click="signRequestedByRequestForModifAlter(RequestedByPIN)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </span>
                      </div>
                    </td>
                    <td width="1%" nowrap>
                      Date Requested:
                    </td>
                    <td>
                      {{RequestForModifAlterObj.dateRequested}}
                    </td>
                  </tr>
                  <tr ng-show="!showOnlyToAccountWPriviledged">
                    <td colspan="4">
                      <label class="radio-inline">
                        <input type="radio" name="requestStatus" value="1" ng-model="RequestForModifAlterObj.requestStatus" ng-disabled="RequestForModifAlterObj.approvedBy > 0 || RequestForModifAlterObj.disApprovedBy > 0">
                        Approved
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="requestStatus" value="2" ng-model="RequestForModifAlterObj.requestStatus" ng-disabled="RequestForModifAlterObj.approvedBy > 0 || RequestForModifAlterObj.disApprovedBy > 0">
                        Disapproved
                      </label>
                    </td>
                  </tr>
                  <tr ng-show="RequestForModifAlterObj.requestStatus =='1'">
                    <td width="1%" nowrap>
                      Approved By:
                    </td>
                    <td>
                      <div ng-show="RequestForModifAlterObj.approvedBy > 0">
                        <img style="width: 100px;" src="{{RequestForModifAlterObj.ApprovedByPxSign}}">
                        <br>
                        <small>{{RequestForModifAlterObj.ApprovedByPxName}}</small>
                      </div>

                      <div class="input-group" id="DontPrint" ng-show="RequestForModifAlterObj.approvedBy == 0">
                        <input type="password" class=" form-control" placeholder="PIN..." ng-model="ApprovedByPIN">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" ng-click="signApprovedByRequestForModifAlter(ApprovedByPIN, RequestForModifAlterObj.requestStatus)" ng-disabled="showOnlyToAccountWPriviledged">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </span>
                      </div>
                    </td>
                    <td width="1%" nowrap>
                      Date Approved:
                    </td>
                    <td>
                      {{RequestForModifAlterObj.dateApproved}}
                    </td>
                  </tr>
                  <tr ng-show="RequestForModifAlterObj.requestStatus =='2'">
                    <td width="1%" nowrap>
                      Disapproved By:
                    </td>
                    <td>
                      <div ng-show="RequestForModifAlterObj.disApprovedBy > 0">
                        <img style="width: 100px;" src="{{RequestForModifAlterObj.DisapprovedByPxSign}}">
                        <br>
                        <small>{{RequestForModifAlterObj.DisapprovedByPxName}}</small>
                      </div>

                      <div class="input-group" id="DontPrint" ng-show="RequestForModifAlterObj.disApprovedBy == 0">
                        <input type="password" class=" form-control" placeholder="PIN..." ng-model="DisapprovedByPIN">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" ng-click="signDisapprovedByRequestForModifAlter(DisapprovedByPIN, RequestForModifAlterObj.requestStatus, RequestForModifAlterObj.disApprovedDescription)" ng-disabled="showOnlyToAccountWPriviledged">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </span>
                      </div>
                    </td>
                    <td width="1%" nowrap>
                      Date Disapproved:
                    </td>
                    <td>
                      {{RequestForModifAlterObj.dateDisApproved}}
                    </td>
                  </tr>
                  <tr ng-show="RequestForModifAlterObj.requestStatus =='2'">
                    <td>
                      Disapproved Reason:
                    </td>
                    <td colspan="3">
                      <textarea class="form-control" rows="4" ng-model="RequestForModifAlterObj.disApprovedDescription"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4" class="text-center">
                      
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" ng-click="insertRequestForModifAlter(RequestForModifAlterObj)" ng-disabled="RequestForModifAlterObj.requestedBy > 0">
                <span class="glyphicon glyphicon-save"></span>
                SAVE
              </button>
              <button type="button" class="btn btn-warning" data-dismiss="modal" ng-click="cancelRequestForModifAlter()">
                <span class="glyphicon glyphicon-ban-circle"></span>
                CANCEL
              </button>
            </div>
          </div>

        </div>
      </div>

    </div>
    <!-- /page content -->

    <!-- footer content -->
    <footer>
      <div class="pull-right">
        Gustilo Mobile Medical Record by <a href="#">GMMR Team</a>
      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
  </div>
</div>
