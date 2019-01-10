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
              <div class="row">
                <div class="col-sm-2 pull-left">
                  <label>PageSize:</label>
                  <select ng-model="RequestForModifAlterListObjdata_limit" class="form-control">
                      <option>10</option>
                      <option>20</option>
                      <option>50</option>
                      <option>100</option>
                  </select>
                </div>
                <div class="col-sm-6 pull-right">
                    <label>Search:</label>
                    <input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control" />
                </div>
              </div>
              <div class="table-responsive" ng-show="RequestForModifAlterListObjfilter_data > 0">
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
                      <th>
                        Approved By/Date
                      </th>
                      <th width="1%" nowrap>
                        Status
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="RequestForModifAlterList in searched = (RequestForModifAlterListObj | filter:search | orderBy : base :reverse) | beginning_data:(RequestForModifAlterListObjcurrent_grid-1)*RequestForModifAlterListObjdata_limit | limitTo:RequestForModifAlterListObjdata_limit" data-toggle="modal" data-target="#requestforModAlterModal" ng-click="editRequestForModifAlter(RequestForModifAlterList)">
                      <td width="1%" nowrap>
                        {{RequestForModifAlterList.dateRequested | date:"medium"}}
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
                      <td>
                        <div ng-show="RequestForModifAlterList.requestStatus == 1 || RequestForModifAlterList.requestStatus == 3">
                          {{RequestForModifAlterList.ApprovedByPxName}}
                          <br>
                          {{RequestForModifAlterList.dateApproved | date:"medium"}}
                        </div>
                        <div ng-show="RequestForModifAlterList.requestStatus == 2">
                          {{RequestForModifAlterList.DisapprovedByPxName}}
                          <br>
                          {{RequestForModifAlterList.dateDisApproved | date:"medium"}}
                        </div>
                      </td>

                      <td width="1%" nowrap class="text-center">
                        <div ng-show="RequestForModifAlterList.requestStatus == 1 || RequestForModifAlterList.requestStatus == 0" style="background-color: blue; color: white;">
                          {{RequestForModifAlterList.requestStatusDesc}}
                        </div>
                        <div ng-show="RequestForModifAlterList.requestStatus == 2" style="background-color: red; color: white;">
                          {{RequestForModifAlterList.requestStatusDesc}}
                        </div>
                        <div ng-show="RequestForModifAlterList.requestStatus == 3" style="background-color: green; color: white;">
                          {{RequestForModifAlterList.requestStatusDesc}}
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-12" ng-show="RequestForModifAlterListObjfilter_data == 0">
                  <div class="col-md-12">
                      <h4>No records found..</h4>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="col-md-6 pull-left">
                      <h5>Showing {{ searched.length }} of {{RequestForModifAlterListObjentire_user}} entries</h5>
                  </div>
                  <div class="col-md-6" ng-show="RequestForModifAlterListObjfilter_data > 0">
                      <ul uib-pagination total-items="RequestForModifAlterListObjfilter_data" ng-model="RequestForModifAlterListObjcurrent_grid" max-size="RequestForModifAlterListObjdata_limit" on-select-page="page_position(page)" items-per-page="RequestForModifAlterListObjdata_limit" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
                  </div>
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
                      <label class="radio-inline" ng-show="RequestForModifAlterObj.requestStatus != 3">
                        <input type="radio" name="requestStatus" value="1" ng-model="RequestForModifAlterObj.requestStatus" ng-disabled="RequestForModifAlterObj.approvedBy > 0 || RequestForModifAlterObj.disApprovedBy > 0">
                        Approved
                      </label>
                      <label class="radio-inline" ng-show="RequestForModifAlterObj.requestStatus != 3">
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
