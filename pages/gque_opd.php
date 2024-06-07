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
            <div class="x_titleZZZ" style="font-family:arial; color: green; ">
              <h1 class="text-center">OPD PRIORITY LIST</h1>
            </div>
          </div>

        </div>
      </div>


      <div ng-show="loading">
          <span style="font-size: 34px;">Please wait... </span>
          <img width="250" src="pages/images/loading.gif" >
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="x_panel tile overflow_hidden">

              <table class="table table-bordered table-hover table-striped">
                <tr class="info" style="color: black; font-weight: bold">
                  <th width="1%" class="text-center" nowrap>SEQ #</th>
                  <th width="1%" class="text-center" nowrap>Last Name</th>
                  <th width="1%" class="text-center" nowrap>First Name</th>
                  <th width="1%" class="text-center" nowrap>Middle Name</th>
                  <th width="1%" class="text-center" nowrap></th>
                  <th class="text-center" nowrap></th>
                  <th width="1%" class="text-center">SERVE</td>
                  <th width="1%" class="text-center">SKIP</td>
                  <th width="1%" class="text-center">DONE</td>
                  <th width="1%" class="text-center">STATUS</td>
                </tr>

                <tr ng-repeat="queOBJItem in queOBJ" style="font-size: 14px; color: black; font-weight: bold">
                    <td class="text-center" nowrap><span style="font-size: 20px">{{queOBJItem.qregsRID}}</span></td>
                    <td nowrap><span style="font-size: 20px; color: black; font-weight: bold"> {{queOBJItem.LastName}} </span></td>
                    <td nowrap>{{queOBJItem.FirstName}}</td>
                    <td nowrap>{{queOBJItem.MiddleName}}</td>
                    <!-- <td class="text-center">{{queOBJItem.purpose}}</td> -->
                    <td class="text-center" nowrap>{{queOBJItem.DateEntered}}</td>
                    <td class="text-center"></td>

                    <td class="text-center" nowrap><a class="btn btn-success">
                      <!-- <span class="glyphicon glyphicon-flag black" title="waiting" ng-click="queAction(queOBJItem.qregsRID, 0)"></span></a> -->
                    </td>
                    <td class="text-center" nowrap><a class="btn btn-danger">
                      <!-- <span class="glyphicon glyphicon-eye-close" title="skip" ng-click="queAction(queOBJItem.qregsRID, 13)"></span></a> -->
                    </td>
                    <td class="text-center" nowrap><a class="btn btn-success">
                      <!-- <span class="glyphicon glyphicon-off" title="done" ng-click="queAction(queOBJItem.qregsRID, 9)"></span></a> -->
                    </td>

                    <td class="text-center" nowrap>{{queOBJItem.StatusDesc}}</td>
                </tr>

                <tr>
                  <td colspan="6"></td>
                  <td colspan="4" class="text-center">

                    <a class="btn btn-danger"><span class="fa fa-heartbeat black" title="resset tp 1" ng-click="queRESET()"> RESET QUE </span></a>
                    
                  </td>
                </tr>

              </table>


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
