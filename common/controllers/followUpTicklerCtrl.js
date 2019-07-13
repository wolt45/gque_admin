gmmrApp.controller('followUpTicklerCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $sce, $filter, $timeout, dbServices){

    var decrypteduserPxRID = localStorage.getItem("gmmrCentraluserPxRID");
    if (decrypteduserPxRID) {
      decrypteduserPxRID = CryptoJS.AES.decrypt(decrypteduserPxRID, "Passphrase").toString(CryptoJS.enc.Utf8);
    }

    var decrypteduserTypeRID = localStorage.getItem("gmmrCentraluserTypeRID"); 
    if (decrypteduserPxRID) {
      decrypteduserTypeRID = CryptoJS.AES.decrypt(decrypteduserTypeRID, "Passphrase").toString(CryptoJS.enc.Utf8);
    }
    $scope.userPxRID = decrypteduserPxRID; 
    $scope.userTypeRID = decrypteduserTypeRID;
  

  

  $scope.getAllFollowUpSched = function () {
    // $scope.AllFollowUpSchedListObj = [];
    $scope.AllFollowUpSchedListObj2 = [];
    dbServices.getAllFollowUpSched()
    .then(function success(response) {
      
      $scope.AllFollowUpSchedListObj = response.data;


      $scope.AllFollowUpSchedListObjcurrent_grid = 1;
      $scope.AllFollowUpSchedListObjdata_limit = 100;
      $scope.AllFollowUpSchedListObjfilter_data = $scope.AllFollowUpSchedListObj.length;
      $scope.AllFollowUpSchedListObjentire_user = $scope.AllFollowUpSchedListObj.length;
      console.log($scope.AllFollowUpSchedListObj);
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


    $scope.getAllFollowUpSchedNotes = function () {
    // $scope.AllFollowUpSchedListObj = [];
    $scope.AllFollowUpSchedListObj2 = [];
    dbServices.getAllFollowUpSchedNotes()
    .then(function success(response) {

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
      // console.log($scope.AllFollowUpSchedListObj2);

    });


  };

  // $scope.getAllFollowUpSchedNotes();

  $scope.changeStatFlag = function (wrid, columnValue, columnToChange) {
    dbServices.changeStatFlag(wrid, columnValue, columnToChange)
    .then(function success(response) {
      // console.log(response);
      $scope.getAllFollowUpSched();
      $scope.getAllFollowUpSchedNotes();
    });
  };


});




