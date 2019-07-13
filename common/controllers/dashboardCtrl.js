gmmrApp.controller('dashboardCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, dbServices){

	var decrypteduserPxRID = localStorage.getItem("gmmrCentraluserPxRID");
  	if (decrypteduserPxRID) {
    	decrypteduserPxRID = CryptoJS.AES.decrypt(decrypteduserPxRID, "Passphrase").toString(CryptoJS.enc.Utf8);
  	}

  	var decrypteduserTypeRID = localStorage.getItem("gmmrCentraluserTypeRID"); 
  	if (decrypteduserPxRID) {
    	decrypteduserTypeRID = CryptoJS.AES.decrypt(decrypteduserTypeRID, "Passphrase").toString(CryptoJS.enc.Utf8);
  	}
    
});




