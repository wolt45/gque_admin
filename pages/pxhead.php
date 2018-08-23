 <div class="row" ng-controller="PXDetailCtrl" id="DontPrint">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content" >
                <table class= "table table-bordered">
                   <tr>
                        <td rowspan="2" width="1%">
                           <img ng-src="../../dump_px/{{clinixItem.foto ? clinixItem.foto : default.jpg}}" width="75" style="padding: 0 0 0 0;" >  
                        </td>
                        <td width="1%" nowrap class="text-center">
                            Appt: {{clinixItem.ClinixRID}}
                            <br>
                            {{clinixItem.AppDateSet | date }}
                            <br>
                            <span style="color:{{clinixItem.preForeColor}}; background-color: {{clinixItem.preBackColor}};">
                                {{clinixItem.TrnStts}}
                            </span>
                        </td>
                        <td >
                            <small>
                                {{clinixItem.PxRID}}
                            </small>
                            {{clinixItem.pxName}}
                            <br>
                            {{clinixItem.AppDateSet | date}}
                        </td>
                        <td class="text-center" class="text-center" width="1%">
                            {{clinixItem.Occupation}}
                        </td>
                   </tr>
                   <tr>
                       <td colspan="2">
                           {{clinixItem.Sex}} / {{clinixItem.pxAge}} / {{clinixItem.MaritalStatus}} /{{clinixItem.Occupation}}
                            <small>{{clinixItem.pxAddress}}</small>
                       </td>
                       <td nowrap>
                           {{clinixItem.Dok}}
                       </td>
                   </tr>
                </table>
            </div>
        </div>
    </div>
</div>


