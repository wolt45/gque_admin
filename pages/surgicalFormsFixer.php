<style type="text/css">
    @media print {
        .modal {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 0;
            overflow: visible!important;
        }
        #tablePrint tbody tr td,#tablePrint tbody tr td span,#tablePrint tr td ,#tablePrint tr td div label {
         padding: 2px;
         line-height: 15px;
        }
        #tablePrint thead tr th,#tablePrint tr th label ,#tablePrint tr th b{
         padding: 2px;
         line-height: 15px;      
        }
        #DontPrint
        {
            color: white;
            display: none;
            text-decoration:none;
        }
    }

</style>

<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

    <!-- page content -->
    <div class="right_col" role="main">

      <div class="row" style="color: #000;">
          <div class="col-md-12 col-sm-12 col-xs-12">

              <div class="x_panel"ng-show="!surgerySchedulePanel" >
                <div class="x_title">
                  <h2>All Surgery Schedules</h2>
                  <ul class="nav navbar-right panel_toolbox" id="DontPrint">
                    <li>
                      <button class="btn btn-sm btn-info pull-right" style="color: #000;" ng-click="showAllWithOrcaseNum()">
                        View Final List with OR Case Number
                      </button>
                    </li>
                  </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <!-- <div>
                      <input type="text" name="" class="form-control" placeholder="Search..." ng-model="OperatingRoomScheduleListSearch">
                  </div> -->

                  <div class="row" style="padding-bottom: 0; margin-bottom: 0;" id="DontPrint">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <table class="table table-responsive">
                        <tr>
                          <td>
                            <div class="pull-left form-inline">
                              <label>PageSize:</label>
                              <select ng-model="OperatingRoomScheduleListAllObjdata_limit" class="form-control">
                                  <option>10</option>
                                  <option>20</option>
                                  <option>50</option>
                                  <option>100</option>
                              </select>
                            </div>
                          </td>
                          <td>
                            <div class="text-right form-inline">
                                <span>
                                  <input type="text" ng-model="OperatingRoomScheduleListSearch" ng-change="filterAll()" placeholder="Search.. " class="qrcode-text" style="background-color: yellow;font-size:20px;">

                                  <a href="#" class="btn btn-success btn-md" style="margin-bottom: 5px;">
                                    <span class="glyphicon glyphicon-search"></span>
                                  </a>
                                </span>
                            </div>
                          </td>
                          <td  width="1%" nowrap class="form-inline">
                            From: 
                            <input type="date" name="" class="form-control" ng-model="fromDate">
                            To: 
                            <input type="date" name="" class="form-control" ng-model="toDate">
                              <button class="btn btn-info" ng-click="getOperatingRoomScheduleReportAllList(fromDate, toDate)">
                                  GO
                              </button>
                          </td>
                          <td  width="1%" nowrap class="form-inline">
                            <button class="btn btn-success btn-sm" ng-click="Reload()">
                              <span class="glyphicon glyphicon-refresh"></span> Reset
                            </button>
                             <button class="btn btn-primary btn-sm" onclick="window.print();">
                                <span class="glyphicon glyphicon-print"></span>
                                PRINT
                            </button>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>

                  <div class="table-responsive" id="DontPrint">
                    <small id="DontShowOnScreen" class="pull-right" ng-show="fromDate != '' && fromDate != null">{{fromDate | date}} - {{toDate | date}}</small>
                      <table class="table table-bordered table-hover" id="tablePrint3">
                          <thead>
                              <tr>
                                  <th class="text-center" width="1%" nowrap>
                                      #
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Image</small>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Chart#</small>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Hosp#</small>
                                      &nbsp;<a ng-click="sort_withAll(HospRID)" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>ORcase#</small>
                                      &nbsp;<a ng-click="sort_withAll(orCaseRID)" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Patient</small>
                                      &nbsp;<a ng-click="sort_withAll('pxName')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Diagnosis</small>
                                      &nbsp;<a ng-click="sort_withAll('diagnosis')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="20%">
                                      <small>Surgery</small>
                                      &nbsp;<a ng-click="sort_withAll('SurgeryType')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" nowrap>
                                      <small>Date/Time</small>
                                      &nbsp;<a ng-click="sort_withAll('SurgeryDate')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" nowrap>
                                      <small>Start & <br>end time</small>
                                      &nbsp;<a ng-click="sort_withAll('SurgeryTime')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="15%" nowrap>
                                      <small>Surgeon & Asst. surgeon</small>
                                      &nbsp;<a ng-click="sort_withAll('Surgeon')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th width="15%" nowrap>
                                      <small>1. Internist <br>
                                      2. Anesthesiologist <br>
                                      3. Type of anesthesia</small>
                                  </th>
                                  <th width="15%" nowrap>
                                      <small>1. Scrub nurse <br>
                                      2. Circulating nurse</small>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Operating <br> Room</small>
                                      &nbsp;<a ng-click="sort_withAll('operatingRoom')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Status</small>
                                      &nbsp;<a ng-click="sort_withAll('surgeryStatus')" id="DontPrint"><i class="glyphicon glyphicon-sort"></i></a>
                                  </th>
                                  <th class="text-center" width="1%" nowrap>
                                      <small>Doc & <br> Nurse Sign</small>
                                  </th>
                                  <th id="DontPrint">Update other<br>
                                   surgical forms</th>
                              </tr>
                          </thead>
                          <tbody>
                              <!-- <tr ng-repeat="OperatingRoomScheduleList in OperatingRoomScheduleListAllObj | filter:OperatingRoomScheduleListSearch | orderBy: [ 'surgeryStatus', '-SurgeryDate', 'SurgeryTime']" > -->
                              <tr ng-repeat="OperatingRoomScheduleList in searched = (OperatingRoomScheduleListAllObj | filter:OperatingRoomScheduleListSearch | orderBy  : base : reverse) | beginning_data:(OperatingRoomScheduleListAllObjcurrent_grid-1)*OperatingRoomScheduleListAllObjdata_limit | limitTo:OperatingRoomScheduleListAllObjdata_limit" >
                                  <td>
                                      {{$index+1}}
                                  </td>
                                  <td>
                                      <img ng-src="../dump_px/{{OperatingRoomScheduleList.foto ? OperatingRoomScheduleList.foto : default.jpg}}" width="75" style="padding: 0 0 0 0;" > 
                                  </td>
                                  <td>
                                      <small>{{OperatingRoomScheduleList.PxRID}}</small>
                                  </td>
                                  <td>
                                      <small> {{OperatingRoomScheduleList.HospRID}}</small>
                                  </td>
                                  <td class="text-center">
                                       <b>{{OperatingRoomScheduleList.orCaseRID}}</b> <br>
                                      <span ng-show="OperatingRoomScheduleList.orCaseRID > 0">
                                        <button class="btn btn-xs btn-primary" ng-click="showAddOrCaseNumber(OperatingRoomScheduleList)">
                                           Update case#
                                        </button>
                                      </span>
                                      <span ng-show="OperatingRoomScheduleList.orCaseRID == 0">
                                        <button class="btn btn-xs btn-success" ng-click="showAddOrCaseNumber(OperatingRoomScheduleList)">
                                           Add case#
                                        </button>
                                      </span>

                                  </td>
                    
                                  <td>
                                      <small> {{OperatingRoomScheduleList.pxName}} </small>
                                  </td>
                                  <td>
                                      <small> {{OperatingRoomScheduleList.diagnosis}} </small>
                                  </td>
                                  <td>
                                      <small> {{OperatingRoomScheduleList.SurgeryType}} </small>
                                  </td>
                                  <td>
                                      <small ng-show="OperatingRoomScheduleList.SurgeryDate !== '0000-00-00'">
                                      {{OperatingRoomScheduleList.SurgeryDate | date}}
                                      </small>
                                  </td>
                                  <td>
                                      <small>
                                          <span ng-show="OperatingRoomScheduleList.SurgeryTime !== '00:00:00'">{{OperatingRoomScheduleList.SurgeryTime | date:'mediumTime'}}</span>
                                          -
                                          <span ng-show="OperatingRoomScheduleList.SurgeryTimeEnd !== '00:00:00'">{{OperatingRoomScheduleList.SurgeryTimeEnd | date:'mediumTime'}}</span>
                                      </small>
                                  </td>
                                  <td>
                                      {{OperatingRoomScheduleList.Surgeon}} <br><br>
                                      <small>* Asst. <br> {{OperatingRoomScheduleList.Assistant}} </small>
                                  </td>
                                  <td>
                                      <small>1. {{OperatingRoomScheduleList.Cardio}}</small> <br>
                                      <small>2. {{OperatingRoomScheduleList.Anesthesio}}</small> <br>
                                      <small>3. {{OperatingRoomScheduleList.AnesthesiaType}}</small>
                                  </td>
                                  <td>
                                      <small>1. {{OperatingRoomScheduleList.scrubNurse}}</small> <br>
                                      <small>2. {{OperatingRoomScheduleList.circulatingNurse}}</small> 
                                  </td>
                                  <td>
                                      <small>{{OperatingRoomScheduleList.operatingRoom}}</small>
                                  </td>
                                  <td>
                                     <small> {{OperatingRoomScheduleList.surgeryStatusDesc}}</small>
                                  </td>
                                  <td>
                                      <small> {{OperatingRoomScheduleList.singedByName}}</small> <br>
                                      <small>{{OperatingRoomScheduleList.orNurseName}}</small>
                                  </td>
                                  <td id="DontPrint">
                                    <button class="btn btn-sm btn-warning" ng-click="showupdateOtherSurgicalForms(OperatingRoomScheduleList)" style="color: #333333;">
                                      <span class="glyphicon glyphicon-eye-open"></span> {{OperatingRoomScheduleList.wrid}}
                                    </button>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>

                  <div class="col-md-12" ng-show="OperatingRoomScheduleListAllObjfilter_data == 0">
                      <div class="col-md-12">
                          <h4>No records found..</h4>
                      </div>
                  </div>
                  <div class="col-md-12"  id="DontPrint">
                      <div class="col-md-6 pull-left">
                          <h5>Showing {{ searched.length }} of {{OperatingRoomScheduleListAllObjentire_user}} entries</h5>
                          <button class="btn btn-xs btn-info" style="color: #000;" ng-click="showAllWithOrcaseNum()">
                            View Final List with OR Case Number
                          </button>
                      </div>
                      <div class="col-md-6 text-right" ng-show="OperatingRoomScheduleListAllObjfilter_data > 0">
                          <ul uib-pagination total-items="OperatingRoomScheduleListAllObjfilter_data" ng-model="OperatingRoomScheduleListAllObjcurrent_grid" max-size="OperatingRoomScheduleListAllObjdata_limit" on-select-page="page_positionAll(page)" items-per-page="OperatingRoomScheduleListAllObjdata_limit" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
                      </div>
                  </div>
                  
                </div>
              </div>
            </div>
      </div>

    </div>
    <!-- /page content -->

    <!-- allwithOrcaseNumModal -->
    <div id="allwithOrcaseNumModal">
        <div class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" style="width: 100%; color: #000;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" id="DontPrint">
                        <button type="button" class="close" ng-click="cancelAllOrCaseNum()" style="margin-left: 50px;">&times;</button> 
                        <button class="btn btn-primary btn-xs pull-right" onclick="window.print();">
                          <span class="glyphicon glyphicon-print"></span>PRINT
                        </button>
                
                        <h4 class="modal-title">FINAL LIST</h4>
                    </div>
                    <div class="modal-body">
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover" id="tablePrint">
                              <tbody>
                                  <tr class="bg-success" style="font-size: 14px; font-weight: bold;">
                                      <td class="text-center" width="1%" nowrap>
                                          #
                                      </td>
                                      <td class="text-center" width="1%" nowrap>
                                          <small>Image</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap>
                                          <small>Chart#</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap>
                                          <small>Hosp#</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap>
                                          <small>ORcase#</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap>
                                          <small>Patient</small>
                                      </td>
                                      <td class="text-center" width="15%" nowrap>
                                          <small>Diagnosis</small>
                                      </td>
                                      <td class="text-center" width="20%">
                                          <small>Surgery</small>
                                      </td>
                                      <td class="text-center" nowrap>
                                          <small>Date/Time</small>
                                      </td>
                                      <td class="text-center" nowrap>
                                          <small>Start & <br>end time</small>
                                      </td>
                                      <td class="text-center" width="15%" nowrap>
                                          <small>Surgeon & Asst. surgeon</small>
                                      </td>
                                      <td width="15%" nowrap id="DontPrint">
                                          <small>1. Internist <br>
                                          2. Anesthesiologist <br>
                                          3. Type of anesthesia</small>
                                      </td>
                                      <td width="15%" nowrap id="DontPrint">
                                          <small>1. Scrub nurse <br>
                                          2. Circulating nurse</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap id="DontPrint">
                                          <small>Operating <br> Room</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap id="DontPrint">
                                          <small>Status</small>
                                      </td>
                                      <td class="text-center" width="1%" nowrap id="DontPrint">
                                          <small>Doc & <br> Nurse Sign</small>
                                      </td>
                                      <td id="DontPrint">
                                        wrid
                                      </td>
                                  </tr>

                                  <tr ng-repeat="allOrCaseList in allOrCaseListObj" >
                                      <td>
                                          {{$index+1}}
                                      </td>
                                      <td>
                                          <img ng-src="../dump_px/{{allOrCaseList.foto ? allOrCaseList.foto : default.jpg}}" width="75" style="padding: 0 0 0 0;" > 
                                      </td>
                                      <td>
                                          <small>{{allOrCaseList.PxRID}}</small>
                                      </td>
                                      <td>
                                          <small> {{allOrCaseList.HospRID}}</small>
                                      </td>
                                      <td class="text-center bg-info">
                                           <b>{{allOrCaseList.orCaseRID}}</b> <br>
                                      </td>
                                      <td>
                                          <small> {{allOrCaseList.pxName}} </small>
                                      </td>
                                      <td>
                                          <small> {{allOrCaseList.diagnosis}} </small>
                                      </td>
                                      <td>
                                          <small> {{allOrCaseList.SurgeryType}} </small>
                                      </td>
                                      <td>
                                          <small ng-show="allOrCaseList.SurgeryDate !== '0000-00-00'">
                                          {{allOrCaseList.SurgeryDate | date}}
                                          </small>
                                      </td>
                                      <td>
                                          <small>
                                              <span ng-show="allOrCaseList.SurgeryTime !== '00:00:00'">{{allOrCaseList.SurgeryTime | date:'mediumTime'}}</span>
                                              -
                                              <span ng-show="allOrCaseList.SurgeryTimeEnd !== '00:00:00'">{{allOrCaseList.SurgeryTimeEnd | date:'mediumTime'}}</span>
                                          </small>
                                      </td>
                                      <td>
                                          {{allOrCaseList.Surgeon}} <br><br>
                                          <small>* Asst.<br> {{allOrCaseList.Assistant}} </small>
                                      </td>
                                      <td id="DontPrint">
                                          <small>1. {{allOrCaseList.Cardio}}</small> <br>
                                          <small>2. {{allOrCaseList.Anesthesio}}</small> <br>
                                          <small>3. {{allOrCaseList.AnesthesiaType}}</small>
                                      </td>
                                      <td id="DontPrint">
                                          <small>1. {{allOrCaseList.scrubNurse}}</small> <br>
                                          <small>2. {{allOrCaseList.circulatingNurse}}</small> 
                                      </td>
                                      <td id="DontPrint">
                                          <small>{{allOrCaseList.operatingRoom}}</small>
                                      </td>
                                      <td id="DontPrint">
                                         <small> {{allOrCaseList.surgeryStatusDesc}}</small>
                                      </td>
                                      <td id="DontPrint">
                                          <small> {{allOrCaseList.singedByName}}</small> <br>
                                          <small>{{allOrCaseList.orNurseName}}</small>
                                      </td>
                                      <td id="DontPrint">
                                         <small> {{allOrCaseList.wrid}}</small>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                    </div>
                    <div class="modal-footer" id="DontPrint">
                        <button class="btn btn-warning" ng-click="cancelAllOrCaseNum()">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            CANCEL
                        </button> 
                    </div>
                </div>

            </div>
        </div>
    </div>



    <!-- updateOtherSurgicalFormsModal -->
    <div id="updateOtherSurgicalFormsModal">
        <div class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" style="width: 60%; color: #000;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="cancelupdateOtherSurgicalForms()">&times;</button>
                        <h5>
                          This action will <u> relate </u> all the other surgical consent/forms of this patient to this Surgery Schedule.
                        </h5>
                    </div>
                    <div class="modal-body">
                      <div class="table-responsive">
                        <table class="table">
                          <tr>
                            <td width="50%">
                               <table class="table table-bordered">
                                <tr>
                                   <td>
                                      <b>OR CASE # </b>
                                   </td>
                                   <td>
                                     <b>{{relateSurgerySchedOBJ.orCaseRID}}</b>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td>
                                      wrid
                                   </td>
                                   <td>
                                     {{relateSurgerySchedOBJ.wrid}}
                                   </td>
                                 </tr>
                                 <tr>
                                   <td>
                                      Hospital #
                                   </td>
                                   <td>
                                     {{relateSurgerySchedOBJ.HospRID}}
                                   </td>
                                 </tr>
                                 <tr>
                                   <td>
                                      PX Name
                                   </td>
                                   <td>
                                     {{relateSurgerySchedOBJ.pxName}}
                                   </td>
                                 </tr>
                                 <tr>
                                   <td width="1%" nowrap>
                                      Surgery Type
                                   </td>
                                   <td>
                                     {{relateSurgerySchedOBJ.SurgeryType}}
                                   </td>
                                 </tr>
                                 <tr>
                                   <td>
                                      Date
                                   </td>
                                   <td>
                                     {{relateSurgerySchedOBJ.SurgeryDate | date}}
                                   </td>
                                 </tr>
                                 <tr>
                                   <td>
                                      Surgeon
                                   </td>
                                   <td>
                                     {{relateSurgerySchedOBJ.Surgeon}}
                                   </td>
                                 </tr>
                               </table>
                            </td>
                            <td width="50%">
                                <b>Surgical consents/forms are the ff. </b>
                                 <ol>
                                   <li>
                                     HX & PE Surgical
                                   </li>
                                   <li>
                                     Consent for surgery
                                   </li>
                                   <li>
                                     consent for administration of anesthesia
                                   </li>
                                   <li>
                                     cardio-pulmonary clearance
                                   </li>
                                   <li>
                                     pre-op evaluation
                                   </li>
                                   <li>
                                     pre-OP checklist
                                   </li>
                                   <li>
                                     Surgical safety checklist
                                   </li>
                                   <li>
                                     anesthesia record
                                   </li>
                                   <li>
                                     Sponge & instrument count
                                   </li>
                                   <li>
                                     PACU record
                                   </li>
                                   <li>
                                     Operative record
                                   </li>
                                   <li>
                                     record of operation
                                   </li>
                                   <li>
                                     pre-op orders
                                   </li>
                                   <li>
                                     operative report - replacement
                                   </li>
                                   <li>
                                     post-OP orders
                                   </li>
                                 </ol>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <br>
                      <br>
                      <div class="text-center">
                        <b>Click GO to continue.</b>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" ng-click="updateOtherSurgicalFormsAction(relateSurgerySchedOBJ)">
                            <span class="glyphicon glyphicon-ok-sign"></span>
                            GO
                        </button>
                        <button class="btn btn-warning" ng-click="cancelupdateOtherSurgicalForms()">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            CANCEL
                        </button> 
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="DontPrint">
        <a id="bottom"></a>
    </div>


    <?php
    include "footer.php";
    ?>
  </div>
</div>


<!-- thisORCaseNumberModal -->
    <div id="thisORCaseNumberModal">
        <div class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" style="width: 70%;">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="cancelOrCaseNumber()">&times;</button>
                        <h4 class="modal-title">Enter the OR case number</h4>
                    </div>
                    <div class="modal-body">
                      <div>
                        <table class="table table-bordered" id="tablePrint">
                                <tr>
                                    <td width="1%" nowrap class="active">
                                        OR Case #. <br>
                                        {{surgerySchedOBJ.wrid}}
                                    </td>
                                    <td colspan="4">
                                      <input type="number" ng-value="lastOrCaseNumberobj" style="border: 1px solid black !important; width: 200px;font-size: 20px; font-size: bold;" ng-show="surgerySchedOBJ.orCaseRID == 0">
                                      <input type="number" string-to-number name="" class="form-control" ng-model="surgerySchedOBJ.orCaseRID" min="{{lastOrCaseNumberobj.orCaseRID}}" ng-value="lastOrCaseNumberobj" style="border: 1px solid black !important; width: 200px; font-size: 20px; font-size: bold;" ng-show="surgerySchedOBJ.orCaseRID > 0">
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td width="1%" nowrap class="active">
                                        Diagnosis<br>
                                        <button class="btn btn-sm btn-warning" ng-click="showLkupICD10()" id="DontPrint">
                                          <small>ICD10</small>
                                        </button>
                                    </td>
                                    <td colspan="3">
                                      <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.diagnosis">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="1%" nowrap class="active">
                                        Surgery Type <br>
                                        <button class="btn btn-sm btn-warning" ng-click="showRvsModal()" id="DontPrint">
                                          Search RVS
                                        </button>
                                    </td>
                                    <td colspan="3">
                                      <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.SurgeryType">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">Date</td>
                                    <td>
                                        <input type="date" date-input name="" class="form-control" ng-model="surgerySchedOBJ.SurgeryDate">
                                    </td>
                                    <td class="active" width="1%" nowrap>
                                        Time Start
                                    </td>
                                    <td>
                                      <input type="time" date-input name="" class="form-control" ng-model="surgerySchedOBJ.SurgeryTime">
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td class="active">Surgeon</td>
                                    <td>
                                        <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.Surgeon" list="surgeonsList">
                                        <datalist id="surgeonsList">
                                          <option value="Dr. Ramon B. Gustilo">
                                          <option value="Dr. Arlan H. Troncillo">
                                          <option value="Dr. Rogelio P. Abitong Jr.">
                                          <option value="Dr. John P. Alejano">
                                        </datalist>
                                    </td>
                                    <td class="active" width="1%" nowrap>
                                        Time End
                                    </td>
                                    <td>
                                      <input type="time" date-input name="" class="form-control" ng-model="surgerySchedOBJ.SurgeryTimeEnd">
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td class="active">Internist or Cardiologist</td>
                                    <td>
                                        <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.Cardio" list="cardiologistList">
                                        <datalist id="cardiologistList">
                                          <option value="Dr. Richard P. Garlitos">
                                          <option value="Dr. James Rafael E. Bilbao">
                                        </datalist>
                                    </td>
                                    <td class="active" width="1%" nowrap>
                                        Assistant/s
                                    </td>
                                    <td>
                                        <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.Assistant" list="AsstsurgeonsList">
                                        <datalist id="AsstsurgeonsList">
                                          <option value="Dr. Arlan H. Troncillo / Dr. Rogelio P. Abitong Jr.">
                                          <option value="Dr. Arlan H. Troncillo">
                                          <option value="Dr. Rogelio P. Abitong Jr.">
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">Anesthesiologist</td>
                                    <td>
                                        <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.Anesthesio" list="anesthesiologistList">
                                        <datalist id="anesthesiologistList">
                                          <option value="Dr. Angel Joaquin M. Gomez">
                                          <option value="Dr. Roger R. Alburo">
                                          <option value="Dr. Mario John Judith">
                                        </datalist>
                                    </td>
                                    <td class="active" width="1%" nowrap>
                                        Type of Anesthesia
                                    </td>
                                    <td>
                                        <select class="form-control" ng-model="surgerySchedOBJ.AnesthesiaType">
                                          <option value=""> (select one) </option>
                                          <option value="Local"> Local </option>
                                          <option value="Spinal"> Spinal </option>
                                          <option value="Epidural"> Epidural </option>
                                          <option value="Nerve Blocks"> Nerve Blocks </option>
                                          <option value="General"> General (Inhalational) </option>
                                          <option value="General Tiva"> General (Tiva) </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">
                                      Scrub Nurse
                                    </td>
                                    <td>
                                       <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.scrubNurse" list="scrubnurseList">
                                       <datalist id="scrubnurseList">
                                          <option value="Jorge Caniendo, RN">
                                          <option value="Virgil Enar T. Balmoria, RN">
                                          <option value="Bea Villanueva, RN">
                                          <option value="Romeo Victor Valderrama, RN">
                                          <option value="Neil Roque, RN">
                                          <option value="Louigie Fernandez, RN">
                                          <option value="Romeo Victor Valderrama, RN">
                                        </datalist>
                                    </td>
                                    <td class="active" width="1%" nowrap>
                                        Circulating Nurse
                                    </td>
                                    <td>
                                        <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.circulatingNurse" list="circulatingnurseList">
                                        <datalist id="circulatingnurseList">
                                          <option value="Fritz Gerald Gloriba, RN">
                                          <option value="Joven Robles, RN">
                                          <option value="Gio Seballos, RN">
                                          <option value="Bea Villanueva, RN">
                                          <option value="Arlyn Caberoy, RN">
                                          <option value="Virgil Enar T. Balmoria, RN">
                                          <option value="Neil Roque, RN">
                                          <option value="Louigie Fernandez, RN">
                                          <option value="Romeo Victor Valderrama, RN">
                                          <option value="Graceymae Gulmatico, RN">
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">
                                      Operating Room
                                    </td>
                                    <td>
                                       <select ng-model="surgerySchedOBJ.operatingRoom" class="form-control">
                                         <option value="" disabled>Select...</option>
                                         <option value="Operating 1">Operating 1</option>
                                         <option value="Operating 2">Operating 2</option>
                                       </select>
                                    </td>
                                    <td colspan="2" id="DontPrint">
                                      <a href="" ng-click="showOperatingRoomSchedule()">
                                        <span class="glyphicon glyphicon-new-window"></span>
                                        Operating Room(s) Schedule
                                      </a>
                                      
                                    </td>
                                </tr>
                                <tr>
                                    <td class="active">Other Info</td>
                                    <td colspan="3">
                                      <input type="text" name="" class="form-control" ng-model="surgerySchedOBJ.Others">
                                    </td>
                                </tr>
                            </table>
                      </div>

                      <div class="row" ng-show="surgerySchedOBJ.HospRID > 0">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6 text-center">
                           <div class="form-inline form-group">
                                <div ng-show="surgerySchedOBJ.orNursePxRID > 0">
                                  
                                    <img style="width: 100px;" src="../dump_dsig/{{surgerySchedOBJ.orNurseSign}}">

                                    <br>
                                    <small><u>{{surgerySchedOBJ.orNurseName}}</u></small>
                                    <br>
                                </div>

                                <div class="input-group" id="DontPrint" ng-show="surgerySchedOBJ.orNursePxRID == 0">
                                    <input type="password" class=" form-control" placeholder="PIN..." ng-model="orNursePIN" >
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger" ng-click="signOrNurseSurgerySchedule(orNursePIN, surgerySchedOBJ)">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </span>
                                </div>
                                <br>
                               
                                <label>
                                    OR Nurse Signature over Printed Name
                                </label>                                 
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6 text-center">
                            <div class="form-inline form-group">
                                <div ng-show="surgerySchedOBJ.signedPxRID > 0">
                                  
                                    <img style="width: 100px;" src="../dump_dsig/{{surgerySchedOBJ.singedBySign}}">

                                    <br>
                                    <small><u>{{surgerySchedOBJ.singedByName}}</u></small>
                                    <br>
                                </div>

                                <div class="input-group" id="DontPrint" ng-show="surgerySchedOBJ.signedPxRID == 0">
                                    <input type="password" class=" form-control" placeholder="PIN..." ng-model="PIN" >
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger" ng-click="signSurgerySchedule(PIN, surgerySchedOBJ)">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </span>
                                </div>
                                <br>
                               
                                <label>
                                    Surgeon Signature over Printed Name
                                </label>                                 
                            </div>
                        </div>

                        <div>
                            <div class="alert alert-warning alert-dismissible fade in" role="alert" ng-show="alert == 1" style="color:#000;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <span>{{missingRequired}}</span>
                              </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" ng-click="insertSurgerySchedule(surgerySchedOBJ, lastOrCaseNumberobj)">
                            <span class="glyphicon glyphicon-save"></span>
                            SAVE
                        </button>
                        <button class="btn btn-warning" ng-click="cancelOrCaseNumber()">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            CANCEL
                        </button> 
                    </div>
                    <div style="color: #000;">
                      <p> <b>Hospital Chart Diagnosis (Face sheet): </b> {{PxpreopDiagnosisobj.diagnosis}}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


