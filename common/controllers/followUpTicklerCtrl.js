gmmrApp.controller('followUpTicklerCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $sce, $filter, $timeout, dbServices){

  $scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 
  $scope.userTypeRID = localStorage.getItem("gmmrCentraluserTypeRID");
  

  

  $scope.getAllFollowUpSched = function () {
    // $scope.AllFollowUpSchedListObj = [];
    $scope.AllFollowUpSchedListObj2 = [];
    dbServices.getAllFollowUpSched()
    .then(function success(response) {
      console.log(response);
      $scope.AllFollowUpSchedListObj = response.data;


      $scope.AllFollowUpSchedListObjcurrent_grid = 1;
      $scope.AllFollowUpSchedListObjdata_limit = 100;
      $scope.AllFollowUpSchedListObjfilter_data = $scope.AllFollowUpSchedListObj.length;
      $scope.AllFollowUpSchedListObjentire_user = $scope.AllFollowUpSchedListObj.length;
      // console.log($scope.AllFollowUpSchedListObj);

      // for (var i = 0; i < response.data.length; i++) {
      //   if (response.data[i].NoteItem == 'Follow Up') {
      //     var ClinixRID = response.data[i].ClinixRID;
      //     var followUpDate = response.data[i].NoteValue;

      //     newrecord = {
      //       ClinixRID : ClinixRID
      //       , followUpDate : followUpDate
      //     };

      //     $scope.AllFollowUpSchedListObj.push(newrecord);
      //   }

      // }

      for (var i = 0; i < response.data.length; i++) {
        if (response.data[i].NoteItem == 'Discussions/Notes: ') {
          var ClinixRID = response.data[i].ClinixRID;
          var followUpNote = response.data[i].NoteValue;

          newrecord = {
            ClinixRID : ClinixRID
            , followUpNote : followUpNote
          };

          $scope.AllFollowUpSchedListObj2.push(newrecord);

        }


      }
      console.log($scope.AllFollowUpSchedListObj2);

      // console.log($scope.AllFollowUpSchedListObj);

      // for (var i = 0; i < response.data.length; i++) {
      //   if (response.data[i].NoteItem == 'Discussions/Notes: ') {
      //     var ClinixRID = response.data[i].ClinixRID;
      //     var followUpNote = response.data[i].NoteValue;
          
      //     console.log(response.data[i]);
          // for (var i = 0; i < $scope.AllFollowUpSchedListObj.length; i++) {
          //   if ($scope.AllFollowUpSchedListObj[i].ClinixRID == ClinixRID) {
              
          //     newrecord = {
          //       ClinixRID : ClinixRID
          //       , followUpNote : followUpNote
          //       // , followUpDate : $scope.AllFollowUpSchedListObj[i].followUpDate
          //     };



          //     // var index = $scope.AllFollowUpSchedListObj.indexOf($scope.AllFollowUpSchedListObj[i]);
          //     // $scope.AllFollowUpSchedListObj.splice(index, 1);
            
          //     // $scope.AllFollowUpSchedListObj.push(newrecord);

          //   }
            
          // }
          
      //   }

      // }

      // console.log($scope.AllFollowUpSchedListObj);
    });


  };

  $scope.getAllFollowUpSched();



  $scope.page_position = function(page_number) {
      $scope.AllFollowUpSchedListObjcurrent_grid = page_number;
  };
  $scope.filter = function() {
      $timeout(function() {
          $scope.AllFollowUpSchedListObjfilter_data = $scope.searched.length;
      }, 20);
  };
  $scope.sort_with = function(base) {
      $scope.base = base;
      $scope.reverse = !$scope.reverse;
  };


  $scope.changeStatFlag = function (wrid, columnValue, columnToChange) {
    console.log(wrid);
    console.log(columnToChange);
    dbServices.changeStatFlag(wrid, columnValue, columnToChange)
    .then(function success(response) {
      console.log(response);
      $scope.getAllFollowUpSched();
    });
  };


});




