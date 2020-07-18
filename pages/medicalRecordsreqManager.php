<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

    <!-- page content -->
    <div class="right_col" role="main">

      <div class="row" style="color: #000;">
        <div class="col-md-12">
            <div class="x_panel tile">
              <div class="x_title">
                <h2>Medical Forms Request List</h2>
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
                <div class="row">
                  <div class="col-sm-2 pull-left">
                    <label>PageSize:</label>
                    <select ng-model="medRequestListObjdata_limit" class="form-control">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                  </div>
                  <div class="col-sm-6 pull-right">
                      <label>Search:</label>
                      <input type="text" ng-model="search" ng-change="filter()" class="form-control" style="background-color: yellow;" placeholder="Search..."/>
                  </div>
                </div>

                <div class="table-responsive" ng-show="medRequestListObjfilter_data > 0">
                      <table class="table table-bordered table-hover" id="tablePrint3">
                          <thead>
                              <tr>

                                <th width="1%">
                                  #
                                </th>
                                <th class="text-center" width="1%" nowrap>
                                      <small>Image</small>
                                  </th>
                                <th width="1%">
                                  RID
                                </th>
                                <th width="1%">
                                  PxRID
                                </th>
                                <th>
                                  ClinixRID
                                </th>
                                <th>
                                  Name
                                </th>
                                <th>
                                  Date
                                  &nbsp;<a ng-click="sort_with(medDate)" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                </th>
                                <th>
                                  Attending Doc
                                </th>
                                <th>
                                  Request
                                </th>
                                <th>
                                  purpose
                                </th>
                                <th>
                                  Req. By
                                </th>
                                <th>
                                  OR #
                                </th>
                                <th>
                                  Request <br> Status
                                  &nbsp;<a ng-click="sort_with(paymentStatus)" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                </th>

                                <th>
                                  RELEASE <br> Status
                                  &nbsp;<a ng-click="sort_with(releaseStatus)" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr ng-repeat="MedReqList in searched = (medRequestListObj | filter:search | orderBy : base :reverse) | beginning_data:(medRequestListObjcurrent_grid-1)*medRequestListObjdata_limit | limitTo:medRequestListObjdata_limit" ng-click="editMedicine(MedReqList)">

                                <td>
                                    {{$index+1}}
                                </td>
                                <td>
                                    <img ng-src="../dump_px/{{MedReqList.foto ? MedReqList.foto : default.jpg}}" width="55" style="padding: 0 0 0 0;" > 
                                </td>
                                <td>
                                  {{MedReqList.requestmedRecordRID}}
                                </td>
                                <td>
                                  <small>{{MedReqList.PxRID}}</small>
                                </td>
                                <td>
                                  <small>{{MedReqList.ClinixRID}} | {{MedReqList.AppDateSet | date}}</small>
                                </td>
                                <td style="font-size: 14px;">
                                  <b>{{MedReqList.PxName}}</b>
                                </td>
                                <td>
                                  {{MedReqList.medDate | date}}
                                </td>
                                <td>
                                  {{MedReqList.attendingDoc}}
                                </td>
                                <td><b>
                                    <span ng-show="MedReqList.medicalAbstract == 1">Medical abstract</span>
                                    <span ng-show="MedReqList.medicalCert == 1">Medical certificate</span>
                                    <span ng-show="MedReqList.medicalReport == 1">Medical report</span>
                                    <span ng-show="MedReqList.OthersText != ''">{{MedReqList.OthersText}}</span></b>
                                </td>
                                <td>
                                  {{MedReqList.purpose}}
                                </td>
                                <td>
                                  {{MedReqList.requestedBy}}
                                </td>
                                <td>
                                  {{MedReqList.ORno}}
                                </td>
                                <td>
                                    <small ng-show="MedReqList.paymentStatus == 0" style="color:black;">Unsigned</small>
                                    <small ng-show="MedReqList.paymentStatus == 8"><span style="color:red;">cancelled</span></small>
                                    <small ng-show="MedReqList.paymentStatus == 9 || MedReqList.paymentStatus == 10"><span style="background-color: blue; color: white;">{{MedReqList.paymentStatusDesc}}</span></small>
                                </td>

                                <td>
                                    <small ng-show="MedReqList.releaseStatus == 0 && MedReqList.paymentStatus != 0 && MedReqList.paymentStatus != 8">Underway</small>
                                    <small ng-show="MedReqList.releaseStatus == 13"><span style="color:red;">Cancelled</span></small>
                                    <small ng-show="MedReqList.releaseStatus == 12"><span style="background-color: red; color: black;">Unreleased</span></small>
                                    <small ng-show="MedReqList.releaseStatus == 11"><span style="background-color: green; color: white;">RELEASED</span></small>
                                    <small ng-show="MedReqList.releaseStatus == 14"><span style="background-color: #000; color: white;">PRINTED</span></small>
                                </td>
                                <!-- <td>
                                  <button class="btn btn-dark btn-xs" ng-click="gotoRequestPrint(MedReqList)">
                                    <span class="glyphicon glyphicon-print"></span>
                                  </button>
                                </td> -->
                              </tr>
                            </tbody>
                      </table>
                  </div>


                <div class="col-md-12" ng-show="medRequestListObjfilter_data == 0">
                    <div class="col-md-12">
                        <h4>No records found..</h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 pull-left">
                        <h5>Showing {{ searched.length }} of {{medRequestListObjentire_user}} entries</h5>
                    </div>
                    <div class="col-md-6" ng-show="medRequestListObjfilter_data > 0">
                        <ul uib-pagination total-items="medRequestListObjfilter_data" ng-model="medRequestListObjcurrent_grid" max-size="medRequestListObjdata_limit" on-select-page="page_position(page)" items-per-page="medRequestListObjdata_limit" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
                    </div>
                </div>
                
                
              </div>
            </div>
        </div>
      </div>

    </div>
    <!-- /page content -->

    <div id="MedicineModal">
      <div class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

          <!-- Modal content-->
          <div class="modal-content" style="color:#000;">
              <div class="modal-header">
                  <button type="button" class="close" ng-click="closeMedicine()">&times;</button>
                  <h4 class="modal-title">Release Status</h4>
              </div>
              <div class="modal-body" >
                <table class="table">
                  <tr>
                    <td class="text-left form-inline" width="40%">
                       Date:
                       <input class="form-control" type="date" date-input ng-model="requestmedRecordOBJ.releaseDate" id="DontPrint">
                    </td>

                    <td class="text-left">
                        <select class="form-control" ng-model="requestmedRecordOBJ.releaseStatus">
                          <option value="">Select Status...</option>
                          <option value="11">Released</option>
                          <option value="12">Unreleased</option>
                          <option value="13">Cancelled</option>
                          <option value="14">Printed</option>
                        </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="form-inline text-right">
                      <div class="input-group" >
                          <input type="password" class=" form-control" placeholder="Billing PIN..." ng-model="requestmedRecordOBJ.SignedPin" >
                          <span class="input-group-btn">
                              <button type="button" class="btn btn-danger" ng-click="ReleaseSignRequest(requestmedRecordOBJ)">
                                  <span class="glyphicon glyphicon-pencil"></span>
                              </button>
                          </span>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            <div class="modal-footer">
              
              <button class="btn btn-warning btn-sm" ng-click="closeMedicine()">
                  <span class="glyphicon glyphicon-ban-circle"></span>
                  CLOSE
              </button> 
            </div>
          </div>

        </div>
      </div>
  </div>

    <?php
    include "footer.php";
    ?>
  </div>
</div>
