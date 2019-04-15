<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

    <!-- page content -->
    <div class="right_col" role="main">

      <div class="row">
        <div class="col-md-12">
            <div class="x_panel tile">
              <div class="x_title">
                <h2>Medicine List</h2>
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
                    <select ng-model="DrugListObjdata_limit" class="form-control">
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

                <div class="table-responsive" style="max-height: 650px;" ng-show="DrugListObjfilter_data > 0">
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr>

                        <th>
                          Generic Name
                        </th>
                        <th>
                          Brand Name
                        </th>
                        <th>
                          Qty. OnHand
                        </th>
                        <th>
                          Special Precautions
                        </th>
                        <th>
                          Adveres Drug Reactions
                        </th>
                        <th>
                          Manufacturer
                        </th>
                        <th>
                          Distributor
                        </th>
                        
                        <th>
                          <a href="" data-toggle="modal" data-target="#sysDoorKeysModal" ng-click="newMedicine()">
                            <span class="glyphicon glyphicon-file green"></span>
                          </a>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr ng-repeat="DrugList in searched = (DrugListObj | filter:search | orderBy : base :reverse) | beginning_data:(DrugListObjcurrent_grid-1)*DrugListObjdata_limit | limitTo:DrugListObjdata_limit" ng-click="editMedicine(DrugList)">

                        <td>
                          {{DrugList.GenericName}}
                        </td>
                        <td>
                          {{DrugList.BrandName}}
                        </td>
                        <td>
                          {{DrugList.qtyOnHand}}
                        </td>
                        <td>
                          {{DrugList.SpecialPrecautions}}
                        </td>
                        <td>
                          {{DrugList.AdverseDrugReactions}}
                        </td>
                        <td>
                          {{DrugList.Manufacturer}}
                        </td>
                        <td>
                          {{DrugList.Distributor}}
                        </td>
                        <td>
                          <a href="" data-toggle="modal" data-target="#sysDoorKeysModal" disabled>
                            <span class="glyphicon glyphicon-pencil orange"></span>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                    
                  </table>
                 
                </div>
                <div class="col-md-12" ng-show="DrugListObjfilter_data == 0">
                    <div class="col-md-12">
                        <h4>No records found..</h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 pull-left">
                        <h5>Showing {{ searched.length }} of {{DrugListObjentire_user}} entries</h5>
                    </div>
                    <div class="col-md-6" ng-show="DrugListObjfilter_data > 0">
                        <ul uib-pagination total-items="DrugListObjfilter_data" ng-model="DrugListObjcurrent_grid" max-size="DrugListObjdata_limit" on-select-page="page_position(page)" items-per-page="DrugListObjdata_limit" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
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
          <div class="modal-content" >
              <div class="modal-header">
                  <button type="button" class="close" ng-click="closeMedicine()">&times;</button>
                  <h4 class="modal-title">Medicine</h4>
              </div>
              <div class="modal-body" >
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <td width="1%" nowrap>
                          Generic Name
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.GenericName">
                        </td>
                        <td width="1%" nowrap>
                          Brand Name
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.BrandName">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Qty. OnHand
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.qtyOnHand">
                        </td>
                        <td>
                          On Order
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.OnOrder">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Re-Order Point
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.ReOrderPoint">
                        </td>
                        <td>
                          Packaging
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Packaging">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Preparation Qty
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.PreparationQty">
                        </td>
                        <td>
                          Preparation Unit
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.PreparationUnit">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Preparation Qty
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.AdvertiserTag">
                        </td>
                        <td>
                          DefDosage
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.DefDosage">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          DefMedBagnosis
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.DefMedBagnosis">
                        </td>
                        <td>
                          DefXDays
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.DefXDays">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Manufacturer
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Manufacturer">
                        </td>
                        <td>
                          Distributor
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Distributor">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Marketer
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Marketer">
                        </td>
                        <td>
                          Contents
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Contents">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Indications
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Indications">
                        </td>
                        <td>
                          Dosage
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Dosage">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Dosage
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Dosage">
                        </td>
                        <td>
                          Overdosage
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Overdosage">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Administration
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Administration">
                        </td>
                        <td>
                          Contraindications
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Contraindications">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          SpecialPrecautions
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.SpecialPrecautions">
                        </td>
                        <td>
                          AdverseDrugReactions
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.AdverseDrugReactions">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          PregnancyCategory
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.PregnancyCategory">
                        </td>
                        <td>
                          Storage
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Storage">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Description
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Description">
                        </td>
                        <td>
                          MechanismofAction
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.MechanismofAction">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          ATCClassification
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.ATCClassification">
                        </td>
                        <td>
                          PoisonSchedule
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.PoisonSchedule">
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Presentation
                        </td>
                        <td>
                          <input type="text" name="" class="form-control" ng-model="DrugObj.Presentation">
                        </td>
                        <td>
                          DeptCode
                        </td>
                        <td>
                          <select class="form-control" ng-model="DrugObj.DeptCode">
                            <option value="0" disabled>Select...</option>
                            <option value="{{DrugDepartmenList.DeptCode}}" ng-repeat="DrugDepartmenList in DrugDepartmenListObj">
                              {{DrugDepartmenList.DeptDesc}}
                            </option>
                          </select>
                        </td>
                      </tr>

                    </table>
                  </div>
              </div>
            <div class="modal-footer">
              <button class="btn btn-success" ng-click="insertMedicine(DrugObj)">
                  <span class="glyphicon glyphicon-file"></span>
                  SAVE
              </button> 
              <button class="btn btn-warning" ng-click="closeMedicine()">
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
