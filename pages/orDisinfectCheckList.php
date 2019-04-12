

<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

    <!-- page content -->
    <div class="right_col" role="main">
      
      <div class="row" id="DontPrint">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">
              <div class="x_title bg-success" id="divPrint">
                <h2>Operating Room Disinfection Checklist</h2>
                <ul class="nav navbar-right panel_toolbox" id="DontPrint">
                  <li>
                    <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                    </a>
                  </li>
                  
                  <li>
                    <a class="close-link">
                      <i class="fa fa-close"></i>
                    </a>
                  </li>
                </ul>
                <div class="clearfix" id="DontPrint"></div>
              </div>

              <div class="x_content">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover table-striped">
                        <thead>
                          <tr>
                            <th class="text-center">
                              <b>Room</b>
                            </th>
                            <th width="1%" colspan="2" class="text-center">
                              <button class="btn btn-sm btn-success" ng-click="newOperatingRoomDisinfection()">
                                <span class="glyphicon glyphicon-plus"></span>
                              </button>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr ng-repeat="OperatingRoomDisinfectionList in OperatingRoomDisinfectionListObj">
                            <td>
                              {{OperatingRoomDisinfectionList.room}}
                            </td>
                            <td>
                              <button class="btn btn-sm btn-warning" ng-click="viewOperatingRoomDisinfection(OperatingRoomDisinfectionList)">
                                <span class="glyphicon glyphicon-eye-open"></span>
                              </button>
                            </td>
                            <td>
                              <button class="btn btn-sm btn-danger" ng-click="removeOperatingRoomDisinfection(OperatingRoomDisinfectionList.operatingDisinfectCheckRID)">
                                <span class="glyphicon glyphicon-trash"></span>
                              </button>
                            </td>
                          </tr>
                        </tbody>
                        
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row" ng-show="OperatingRoomDisinfectionObj">
                  <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <div class="table-responsive">
                      <table class="table table-bordered" id="tablePrint">
                        <tr>
                          <td>
                            Room:
                          </td>
                          <td colspan="15">
                            <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObj.room">
                          </td>
                        </tr>
                          <tr>
                            <td>
                              Date
                            </td>
                            <td>
                              Time
                            </td>
                            <td>
                              Wall
                            </td>
                            <td>
                              Anesthesia Machine
                            </td>
                            <td nowrap>
                              OR Bed
                            </td>
                            <td>
                              Suction Machine
                            </td>
                            <td>
                              Electrocautery Machine
                            </td>
                            <td>
                              OR Light
                            </td>
                            <td>
                              Supplies Cabinet
                            </td>
                            <td>
                              Equipment Cabinet
                            </td>
                            <td>
                              Floor
                            </td>
                            <td>
                              Others
                            </td>
                            <td nowrap>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              Initial
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                            <td>
                              REMARKS
                            </td>
                            <td colspan="2" id="DontPrint"></td>
                          </tr>
                          <tr>
                            <td colspan="16" class="text-center">
                              DISINFECTANT
                            </td>
                          </tr>
                          <tr id="DontPrint">
                            <td>
                              <input type="datetime-local" date-input name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.dateTimeEntered">
                            </td>

                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.wall">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.anesthesiaMachine">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.orBed">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.suctionMachine">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.electrocauteryMachine">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.orLight">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.suppliesCabinet">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.equipmentCabinet">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.floor">
                            </td>
                            <td>
                              <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.others">
                            </td>
                            <td>
                              <!-- <input type="text" name="" class="form-control" ng-model="OperatingRoomDisinfectionObjDetails.initialPxRID"> -->
                            </td>
                            <td>
                              <textarea rows="4" ng-model="OperatingRoomDisinfectionObjDetails.remarks">
                                {{OperatingRoomDisinfectionObjDetails.remarks}}
                              </textarea>
                            </td>
                            <td colspan="2" width="1%" class="text-center">
                              <button class="btn btn-success" ng-click="insertOperatingRoomDisinfectionDetail(OperatingRoomDisinfectionObjDetails)">
                                <span class="glyphicon glyphicon-plus"></span>
                              </button>
                            </td>
                          </tr>
                          <tr ng-repeat="OperatingRoomDisinfectionList in OperatingRoomDisinfectionListOBJ">
                            <td>
                              {{OperatingRoomDisinfectionList.dateTimeEntered | date:'medium'}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.wall}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.anesthesiaMachine}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.orBed}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.suctionMachine}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.electrocauteryMachine}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.orLight}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.suppliesCabinet}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.equipmentCabinet}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.floor}}
                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.others}}
                            </td>
                            <td>
                              <div class="form-inline text-right">
                                <div ng-show="OperatingRoomDisinfectionList.initialPxRID > 0">
                                  <img style="width: 100px;" src="../dump_dsig/{{OperatingRoomDisinfectionList.initialSign}}">
                                  <br>
                                </div>
                                  
                                  <small>{{OperatingRoomDisinfectionList.initialName}}</small>
                                  

                                  <div class="input-group" id="DontPrint" ng-show="OperatingRoomDisinfectionList.initialPxRID == 0" id="DontPrint">
                                      <input type="password" class=" form-control" placeholder="PIN..." ng-model="PIN">
                                      <span class="input-group-btn">
                                          <button type="button" class="btn btn-primary" ng-click="signOperatingRoomDisinfection(PIN, OperatingRoomDisinfectionList.operatingDisinfectCheckRID)">
                                              <span class="glyphicon glyphicon-pencil"></span>
                                          </button>
                                      </span>
                                  </div>
                              </div>

                            </td>
                            <td>
                              {{OperatingRoomDisinfectionList.remarks}}
                            </td>
                            <td id="DontPrint" width="1%">
                              <button class="btn btn-warning" ng-click="editOperatingRoomDisinfection(OperatingRoomDisinfectionList)" ng-disabled="OperatingRoomDisinfectionList.initialPxRID > 0">
                                <span class="glyphicon glyphicon-edit"></span>
                              </button>
                            </td>
                            <td id="DontPrint" width="1%">
                              <button class="btn btn-danger" ng-click="removeOperatingRoomDisinfectionDetail(OperatingRoomDisinfectionList.operatingDisinfectCheckDetailRID)" ng-disabled="OperatingRoomDisinfectionList.initialPxRID > 0">
                                <span class="glyphicon glyphicon-trash"></span>
                              </button>
                            </td>
                          </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" id="divPrint">
                    <b>DISINFECTANT LEGENDS</b>
                    <br>
                    SW - soap & water
                    <br>
                    L - Lysol
                    <br>
                    C - Chloride
                    <br>
                    A - Alcohol
                    <br>
                    SM - Sanosil Misting
                  </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="DontPrint">
                      <button class="btn btn-success" ng-click="insertOperatingRoomDisinfection(OperatingRoomDisinfectionObj)">
                          <span class="glyphicon glyphicon-save"></span> SAVE
                      </button>
                      <button class="btn btn-primary" onclick="window.print();">
                          <span class="glyphicon glyphicon-print"></span> PRINT
                      </button>
                    </div>
                </div>
                
              </div>
            </div>
              

        </div>
      </div>


    </div>
    <!-- /page content -->

    <?php
      include "footer.php";
    ?>
  </div>
</div>
