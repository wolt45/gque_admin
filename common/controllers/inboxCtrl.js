gmmrApp.controller('inboxCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $timeout, $filter, $sce, Idle, Keepalive, $uibModal, ngToast, $interval, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID");
  $scope.dateNow = new Date;
  $scope.dateNowDateOnly = $filter('date')(new Date($scope.dateNow), 'yyyy-MM-dd');
  $scope.newMessageItemSum= 0;

  $interval(function(){
    // $scope.getMessages($scope.userPxRID);
    // $scope.getDraftMessages($scope.userPxRID);
  },10000);

  $scope.loadScript = function (url){
    // console.log('Javascript Loading...');
    let node = document.createElement('script');
    node.src = url;
    node.type = 'text/javascript';
    document.getElementsByTagName('head')[0].appendChild(node);   
  }
  $scope.loadScript('build/js/customComposeMessage.js'); 


  $scope.getHtml = function(html){
      return $sce.trustAsHtml(html);
  };

  $scope.viewType = 'Inbox';
  $scope.changeViewType = function(viewType) {
    $scope.viewType = viewType;
    $scope.MessagesObj = null;
  };

  $scope.showComposeMessageModal = function() {
    $scope.showComposeMessageModalDialog(true);
    $scope.createNewMessage();
  };

  $scope.closeComposeMessageModal = function() {
    $scope.showComposeMessageModalDialog(false);
  };

  $scope.showComposeMessageModalDialog = function(flag) {
    jQuery("#compose .compose-close");
    $('.compose').slideToggle();
  };

  $scope.createNewMessage = function(){
    dbServices.createNewMessage($scope.userPxRID)
      .then(function success(response) {
        // console.log(response);
        $scope.NewMessageAttachFileListObj = [];
        $scope.NewMessageRecipientListObj = [];
        $scope.newMessageObj = response.data;
    });
  }

  $scope.autoSaveNewMessage = function(newMessageObj){
    dbServices.autoSaveNewMessage(newMessageObj)
      .then(function success(response) {
        // console.log(response);
    });
    
  }



  $scope.getNewMessageAttachFile = function(messageBoxRID){
    dbServices.getNewMessageAttachFile(messageBoxRID)
      .then(function success(response) {
        // console.log(response);
        $scope.NewMessageAttachFileListObj = response.data;
    });
    
  }

  $scope.getNewMessageRecipient = function(messageBoxRID){
    dbServices.getNewMessageRecipient(messageBoxRID)
      .then(function success(response) {
        console.log(response);
        $scope.NewMessageRecipientListObj = response.data;
    });
    
  }

  $scope.sendMessage = function(newMessageObj){
    for (var i = 0; i < $scope.NewMessageRecipientListObj.length; i++) {
      
      dbServices.sendMessage($scope.userPxRID, $scope.NewMessageRecipientListObj[i].toRID, newMessageObj)
        .then(function success(response) {
          // console.log(response);
          // console.log(response.config.data.toRID);
          // $scope.getMessageWhereToAttachFile(response.config.data.toRID);
      });
    }
    ngToast.show("Message successfully sent!", 'top');
    $scope.closeComposeMessageModal();
    $scope.newMessageObj = {};
    $scope.NewMessageAttachFileListObj = [];
    $scope.NewMessageRecipientListObj = [];
    
  }


  // $scope.getMessageWhereToAttachFile = function(PxRID){
  //   dbServices.getMessageWhereToAttachFile($scope.userPxRID, PxRID)
  //     .then(function success(response) {
  //       // console.log(response);
  //      $scope.sendAttachFileToMessage(response.data.messageBoxRID);
  //   });
    
  // }


  // $scope.sendAttachFileToMessage = function(messageBoxRID){
  //   // console.log($scope.attachedFileListObj.length);
  //   // console.log(messageBoxRID);
  //   var tempIndex;
  //   for (var i = 0; i < $scope.attachedFileListObj.length; i++) {
  //   //   console.log($scope.attachedFileListObj[i]);
  //     tempIndex = $scope.attachedFileListObj[i];

  //     var index = $scope.attachedFileListObj.indexOf(tempIndex);
  //         $scope.attachedFileListObj.splice(index, 1); 
          
  //     dbServices.sendAttachFileToMessage(messageBoxRID, $scope.attachedFileListObj)
  //       .then(function success(response) {
  //         console.log(response);
  //     });
  //   }
  // }


  

  $scope.getUserAccounts = function(){
    dbServices.getUserAccounts()
      .then(function success(response) {
        // console.log(response);
        $scope.UserAccountsListObj = response.data;
    });
  };

  $scope.getUserAccounts();

  $scope.NewMessageRecipientListObj = [];

  $scope.selectReceiver = function(selectReceiver){


    dbServices.insertNewMessageRecipient($scope.newMessageObj.messageBoxRID, selectReceiver.PxRID)
      .then(function success(response) {
        // console.log(response);
        $scope.UserAccountsListFilter = null;
        $scope.getNewMessageRecipient($scope.newMessageObj.messageBoxRID);
    });

  };

  $scope.removeNewMessageRecipient = function(NewMessageRecipientList){
    dbServices.removeNewMessageRecipient(messageRecipientRID)
      .then(function success(response) {
        // console.log(response);
        $scope.getNewMessageRecipient($scope.newMessageObj.messageBoxRID);
    });
  };



  $scope.attachedFileListObj = [];
  $scope.file = null;
      
  $scope.$watch('file', function (newVal) {
    if (newVal){
      var file = newVal;
      // console.log(newVal);
      // console.log(file);
      

      $scope.attachedFileListObj.push(file);
      // $scope.file = null;
      // console.log($scope.attachedFileListObj);
      dbServices.sendAttachFileToMessage($scope.newMessageObj.messageBoxRID, $scope.attachedFileListObj)
        .then(function success(response) {
          // console.log(response);
          $scope.attachedFileListObj = [];
          $scope.getNewMessageAttachFile($scope.newMessageObj.messageBoxRID);
      });

    }
  });

  $scope.removeNewMessageAttachedFile = function(messageAttachFileRID){
    dbServices.removeNewMessageAttachedFile(messageAttachFileRID)
      .then(function success(response) {
        // console.log(response);
        $scope.getNewMessageAttachFile($scope.newMessageObj.messageBoxRID);
    });
  };



  $scope.getMessages = function(userPxRID){
    $scope.MessagesListObj = [];
    $scope.newMessageItemSum = 0;
    dbServices.getMessages(userPxRID)
      .then(function success(response) {
        // console.log(response);

        for (var i = 0; i < response.data.length; i++) {

          var byRID = response.data[i].byRID;
          var fotoSender = response.data[i].fotoSender;
          var messageBoxRID = response.data[i].messageBoxRID;
          var messageSubject = response.data[i].messageSubject;
          var messageContent = response.data[i].messageContent;
          if (messageContent != null) {
            var tempmessageContent = messageContent.replace(/<[^>]+>/gm, '');
          }

          var messageGroupRID = response.data[i].messageGroupRID;
          var messageViewed = response.data[i].messageViewed;
          var pxNameSender = response.data[i].pxNameSender;
          var sysDateEntered = moment(response.data[i].sysDateEntered).format();
          var sysDateEnteredDateOnly = moment(response.data[i].sysDateEntered).format();
          sysDateEnteredDateOnly = $filter('date')(new Date(sysDateEnteredDateOnly), 'yyyy-MM-dd');

          var toRID = response.data[i].toRID;
          var messageAttachFileRID = response.data[i].messageAttachFileRID;

          if (messageViewed == 0) {
            $scope.newMessageItemSum += 1;
          }

          newRecord = {
            byRID : byRID
            , fotoSender : fotoSender
            , messageBoxRID : messageBoxRID
            , messageSubject : messageSubject
            , messageContent : messageContent
            , tempmessageContent : tempmessageContent
            , messageGroupRID : messageGroupRID
            , messageViewed : messageViewed
            , pxNameSender : pxNameSender
            , sysDateEntered : sysDateEntered
            , sysDateEnteredDateOnly : sysDateEnteredDateOnly
            , toRID : toRID
            , messageAttachFileRID : messageAttachFileRID
          };


          $scope.MessagesListObj.push(newRecord);
          // console.log($scope.MessagesListObj); 

        }
    });
  };

  $scope.getMessages($scope.userPxRID);



  $scope.getDraftMessages = function(userPxRID){
    $scope.DraftMessagesListObj = [];
    dbServices.getDraftMessages(userPxRID)
      .then(function success(response) {
        // console.log(response);

        for (var i = 0; i < response.data.length; i++) {

          var byRID = response.data[i].byRID;
          var fotoSender = response.data[i].fotoSender;
          var messageBoxRID = response.data[i].messageBoxRID;
          var messageSubject = response.data[i].messageSubject;
          var messageContent = response.data[i].messageContent;
          if (messageContent != null) {
            var tempmessageContent = messageContent.replace(/<[^>]+>/gm, '');
          }

          var messageGroupRID = response.data[i].messageGroupRID;
          var messageViewed = response.data[i].messageViewed;
          var pxNameSender = response.data[i].pxNameSender;
          var sysDateEntered = moment(response.data[i].sysDateEntered).format();
          var sysDateEnteredDateOnly = moment(response.data[i].sysDateEntered).format();
          sysDateEnteredDateOnly = $filter('date')(new Date(sysDateEnteredDateOnly), 'yyyy-MM-dd');

          var toRID = response.data[i].toRID;
          var messageAttachFileRID = response.data[i].messageAttachFileRID;


          newRecord = {
            byRID : byRID
            , fotoSender : fotoSender
            , messageBoxRID : messageBoxRID
            , messageSubject : messageSubject
            , messageContent : messageContent
            , tempmessageContent : tempmessageContent
            , messageGroupRID : messageGroupRID
            , messageViewed : messageViewed
            , pxNameSender : pxNameSender
            , sysDateEntered : sysDateEntered
            , sysDateEnteredDateOnly : sysDateEnteredDateOnly
            , toRID : toRID
            , messageAttachFileRID : messageAttachFileRID
          };


          $scope.DraftMessagesListObj.push(newRecord);
          // console.log($scope.DraftMessagesListObj); 

        }
    });
  };

  $scope.getDraftMessages($scope.userPxRID);


  // $scope.checkedMessageAttachFile = function(messageBoxRID){
  //   var attachFile = 0;

  //   dbServices.getNewMessageAttachFile(messageBoxRID)
  //     .then(function success(response) {
  //       // console.log(response);
  //       if (response.data.length > 0) {
  //         attachFile = 1;
  //       }
  //       return  attachFile;
  //   });

    
  // };

  $scope.viewMessages = function(MessagesList){
    if (MessagesList.byRID == 0) {
      $scope.showComposeMessageModalDialog(true);
      $scope.newMessageObj = MessagesList;

      $scope.getNewMessageAttachFile(MessagesList.messageBoxRID);
      $scope.getNewMessageRecipient(MessagesList.messageBoxRID);
    }else{
      $scope.MessagesObj = MessagesList;
      $scope.getMessageAttachFile(MessagesList.messageBoxRID);
      $scope.getMessageRecipient(MessagesList.messageBoxRID);

      if (MessagesList.messageViewed == 0) {
        dbServices.viewMessages(MessagesList.messageBoxRID, $scope.userPxRID)
          .then(function success(response) {
            // console.log(response);
            $scope.getMessages($scope.userPxRID);
            $scope.getDraftMessages($scope.userPxRID);
        });
      }
    }

    
    

  };



  $scope.getMessageAttachFile = function(messageBoxRID){
    dbServices.getNewMessageAttachFile(messageBoxRID)
      .then(function success(response) {
        // console.log(response);
        $scope.MessageAttachFileListObj = response.data;
    });
    
  }

  $scope.getMessageRecipient = function(messageBoxRID){
    dbServices.getNewMessageRecipient(messageBoxRID)
      .then(function success(response) {
        // console.log(response);
        $scope.MessageRecipientListObj = response.data;
    });
    
  }

  $scope.deleteMessage = function(messageBoxRID){

    if (confirm("Are you sure to delete this message?")) {
      dbServices.deleteMessage(messageBoxRID)
        .then(function success(response) {
          // console.log(response);
          $scope.MessagesObj = null;
          $scope.getMessages($scope.userPxRID);
          $scope.getDraftMessages($scope.userPxRID);
          ngToast.show("Message successfully deleted!", 'top');

      });
    }

  };


  $scope.deleteNewMessage = function(messageBoxRID){

    if (confirm("Are you sure to delete this message?")) {
      dbServices.deleteMessage(messageBoxRID)
        .then(function success(response) {
          // console.log(response);
          $scope.MessagesObj = null;
          $scope.getMessages($scope.userPxRID);
          $scope.getDraftMessages($scope.userPxRID);
          // ngToast.show("Message successfully deleted!", 'top');
          $scope.closeComposeMessageModal();
      });
    }

  };


    
});




