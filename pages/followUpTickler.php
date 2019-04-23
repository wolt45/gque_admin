

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
                <h2>Follow-up Schedules</h2>
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
                  <div class="col-sm-2 pull-left">
                    <label>PageSize:</label>
                    <select ng-model="AllFollowUpSchedListObjdata_limit" class="form-control">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                  </div>
                  <div class="col-sm-6 pull-right">
                      <label>Search:</label>
                      <input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control" style="background-color: yellow;" placeholder="Search..."/>
                  </div>
                </div>
                <div class="table-responsive" ng-show="AllFollowUpSchedListObjfilter_data > 0">
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th class="tex-center">
                          Patient
                        </th>
                        <th width="1%" nowrap>
                          Follow-Up Date
                        </th>
                        <th nowrap>
                          Follow-Up NOte
                        </th>
                        <th>
                          
                        </th>

                      </tr>
                    </thead>
                    <tbody>
                      <tr ng-repeat="AllFollowUpSchedList in searched = (AllFollowUpSchedListObj | filter:search | orderBy : base :reverse) | beginning_data:(AllFollowUpSchedListObjcurrent_grid-1)*AllFollowUpSchedListObjdata_limit | limitTo:AllFollowUpSchedListObjdata_limit" data-toggle="modal" data-target="#requestforModAlterModal" ng-click="editAllFollowUpSched(AllFollowUpSchedList)" ng-show="AllFollowUpSchedList.NoteItem == 'Follow Up'">
                        <td nowrap width="1%">
                          {{AllFollowUpSchedList.pxName}}
                        </td>
                        <td width="1%" nowrap>
                          {{AllFollowUpSchedList.NoteValue | date:"longDate"}}
                        </td>
                        <td >
                          <div ng-repeat="AllFollowUpSchedList2 in AllFollowUpSchedListObj2" ng-if="AllFollowUpSchedList2.ClinixRID == AllFollowUpSchedList.ClinixRID">
                            {{AllFollowUpSchedList2.followUpNote}}
                          </div>
                        </td>
                        <td width="1%" nowrap>
                          <label>
                            <input type="checkbox" name="" ng-model="AllFollowUpSchedList.followUpFlagCalledBySec" ng-true-value="'1'" ng-false-value="'0'" ng-change="changeStatFlag(AllFollowUpSchedList.wrid, AllFollowUpSchedList.followUpFlagCalledBySec, 'followUpFlagCalledBySec')">
                            Called by Secretary
                          </label>
                          <br>
                          <label>
                            <input type="checkbox" name="" ng-model="AllFollowUpSchedList.followUpFlagVisited" ng-true-value="'1'" ng-false-value="'0'" ng-change="changeStatFlag(AllFollowUpSchedList.wrid, AllFollowUpSchedList.followUpFlagVisited, 'followUpFlagVisited')">
                            Visited
                          </label>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-12" ng-show="AllFollowUpSchedListObjfilter_data == 0">
                    <div class="col-md-12">
                        <h4>No records found..</h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 pull-left">
                        <h5>Showing {{ searched.length }} of {{AllFollowUpSchedListObjentire_user}} entries</h5>
                    </div>
                    <div class="col-md-6" ng-show="AllFollowUpSchedListObjfilter_data > 0">
                        <ul uib-pagination total-items="AllFollowUpSchedListObjfilter_data" ng-model="AllFollowUpSchedListObjcurrent_grid" max-size="AllFollowUpSchedListObjdata_limit" on-select-page="page_position(page)" items-per-page="AllFollowUpSchedListObjdata_limit" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
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
