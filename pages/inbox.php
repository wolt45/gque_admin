<div class="container body">
  <div class="main_container">
    <?php
    include "sideTopMenu.php";
    ?>

    <!-- page content -->
    <div class="right_col" role="main">


            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Inbox Design<small>User Mail</small></h2>
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
                      <div class="col-sm-3 mail_list_column">
                        <!-- <button ng-click="showComposeMessageModal()" class="btn btn-sm btn-success btn-block" type="button" >
                          COMPOSE
                        </button> -->
                        <a class="btn btn-app" ng-click="showComposeMessageModal()">
                          <i class="fa fa-pencil"></i> Compose
                        </a>
                        <a class="btn btn-app" ng-click="changeViewType('Inbox')">
                          <span class="badge bg-red" ng-show="newMessageItemSum > 0">
                            {{newMessageItemSum}}
                          </span>
                          <i class="fa fa-envelope-o"></i> Inbox
                        </a>

                        <a class="btn btn-app" ng-click="changeViewType('Drafts')">
                          <span class="badge bg-blue" ng-show="DraftMessagesListObj.length > 0">
                            {{DraftMessagesListObj.length}}
                          </span>
                          <i class="fa fa-file"></i> Drafts
                        </a>

                        <a class="btn btn-app" ng-click="changeViewType('Sent')">
                          <!-- <span class="badge bg-red">6</span> -->
                          <i class="fa fa-paper-plane"></i> Sent
                        </a>


                        <!-- <div class="btn-group">
                          <button class="btn btn-default" type="button" ng-click="changeViewType('Inbox')">
                            Inbox
                          </button>
                          <button class="btn btn-default" type="button" ng-click="changeViewType('Drafts')">
                            Drafts
                          </button>
                          <button class="btn btn-default" type="button">
                            Sent
                          </button>
                        </div> -->
                        <br>
                        <!-- Inbox Msg List-->
                        <div style="overflow: scroll; max-height: 500px; overflow-x: hidden;" >
                          <a ng-click="viewMessages(MessagesList)" ng-repeat="MessagesList in MessagesListObj | orderBy:['messageViewed', '-sysDateEntered']" ng-show="viewType == 'Inbox'">
                            <div class="mail_list">
                              <div class="left">
                                <i class="fa fa-circle" ng-show="MessagesList.messageViewed == 0 && MessagesList.byRID != 0"></i>
                                <i class="fa fa-circle-o" ng-show="MessagesList.messageViewed == 1 && MessagesList.byRID != 0"></i>
                                <i class="fa fa-paperclip" ng-show="MessagesList.messageAttachFileRID > 0"></i>
                                <i class="fa fa-edit" ng-show="MessagesList.byRID == 0"></i>
                              </div>
                              <div class="right">
                                <h3>{{MessagesList.pxNameSender}} 
                                  <small ng-if="dateNowDateOnly ==  MessagesList.sysDateEnteredDateOnly">
                                    {{MessagesList.sysDateEntered | date:'shortTime'}}
                                  </small>
                                  <small ng-if="dateNowDateOnly !=  MessagesList.sysDateEnteredDateOnly">
                                    {{MessagesList.sysDateEntered | date:'mediumDate'}}
                                  </small>
                                </h3>
                                <p>
                                  {{MessagesList.tempmessageContent | limitTo: 20 }} {{messageContent.length < 20 ? '' : '...'}}
                                </p>
                              </div>
                            </div>
                          </a>
                        </div>
                        


                        <!-- Drafts Msg List-->
                        <div style="overflow: scroll; max-height: 500px; overflow-x: hidden;" >
                          <a ng-click="viewMessages(DraftMessagesList)" ng-repeat="DraftMessagesList in DraftMessagesListObj | orderBy:['messageViewed', '-sysDateEntered']" ng-show="viewType == 'Drafts'">
                            <div class="mail_list">
                              <div class="left">
                                <i class="fa fa-circle" ng-show="DraftMessagesList.messageViewed == 0 && DraftMessagesList.byRID != 0"></i>
                                <i class="fa fa-circle-o" ng-show="DraftMessagesList.messageViewed == 1 && DraftMessagesList.byRID != 0"></i>
                                <i class="fa fa-paperclip" ng-show="DraftMessagesList.messageAttachFileRID > 0"></i>
                                <i class="fa fa-edit" ng-show="DraftMessagesList.byRID == 0"></i>

                              </div>
                              <div class="right">
                                <h3>{{DraftMessagesList.pxNameSender}} 
                                  <small ng-if="dateNowDateOnly ==  DraftMessagesList.sysDateEnteredDateOnly">
                                    {{DraftMessagesList.sysDateEntered | date:'shortTime'}}
                                  </small>
                                  <small ng-if="dateNowDateOnly !=  DraftMessagesList.sysDateEnteredDateOnly">
                                    {{DraftMessagesList.sysDateEntered | date:'mediumDate'}}
                                  </small>

                                </h3>
                                <p>
                                  {{DraftMessagesList.tempmessageContent | limitTo: 20 }} {{messageContent.length < 20 ? '' : '...'}}

                                </p>
                              </div>
                            </div>
                          </a>
                        </div>
                        
                      </div>
                      <!-- /MAIL LIST -->

                      <!-- CONTENT MAIL -->
                      <div class="col-sm-9 mail_view">
                        <div class="inbox-body" ng-show="MessagesObj != null">
                          <div class="mail_heading row">
                            <div class="col-md-8">
                              <div class="btn-group">
                                <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash" ng-click="deleteMessage(MessagesObj.messageBoxRID)"><i class="fa fa-trash-o"></i></button>
                              </div>
                            </div>
                            <div class="col-md-4 text-right">
                              <p class="date"> {{MessagesObj.sysDateEntered | date:'medium'}}</p>
                            </div>
                            <div class="col-md-12">
                              <h4> 
                                {{MessagesObj.messageSubject}}
                              </h4>
                            </div>
                          </div>
                          <div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>{{MessagesObj.pxNameSender}}</strong>
                                <!-- <span>(jon.doe@gmail.com)</span>  -->
                                to
                                
                                <strong ng-repeat="MessageRecipientList in MessageRecipientListObj">
                                  <span ng-show="MessageRecipientList.toRID == userPxRID">me</span>
                                  <span ng-show="MessageRecipientList.toRID != userPxRID">{{MessageRecipientList.pxName}}</span>
                                  ,
                                </strong>
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
                          <div class="view-mail">
                            <span ng-bind-html="getHtml(MessagesObj.messageContent)"></span>
                          </div>
                          <div class="attachment" ng-show="MessageAttachFileListObj.length > 0">
                            <p>
                              <span><i class="fa fa-paperclip"></i> {{MessageAttachFileListObj.length}} attachments — </span>
                              <a href="#">Download all attachments</a> |
                              <a href="#">View all images</a>
                            </p>
                            <ul>
                              <li ng-repeat="MessageAttachFileList in MessageAttachFileListObj">
                                <a href="#" class="atch-thumb">
                                  <img src="" alt="File Preview" />
                                </a>

                                <div class="file-name">
                                  {{MessageAttachFileList.origFileName}}
                                </div>


                                <div class="links">
                                  <a href="#">View</a> -
                                  <a href="#">Download</a>
                                </div>
                              </li>

                              

                            </ul>
                          </div>
                          <!-- <div class="btn-group">
                            <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                            <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                            <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                            <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                          </div> -->
                        </div>

                      </div>
                      <!-- /CONTENT MAIL -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

    </div>
    <!-- /page content -->



    <!-- compose -->
    <div class="compose col-md-6 col-xs-12">
      <div class="compose-header">
        New Message
        
        <button type="button" class="close" ng-click="deleteNewMessage(newMessageObj.messageBoxRID)">
          <span>×</span>
        </button>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        <button type="button" class="close" ng-click="closeComposeMessageModal()">
          <span>-</span>
        </button>
      </div>

      <div class="compose-body">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <table class="table" style="padding: 0px; margin: 0px;">
              <tr style="padding: 0px; margin: 0px;">
                <td width="1%" nowrap>
                  <p>To</p>
                </td>
                <td>
                  <div class="form-inline">
                    
                    
                    <span style="border: solid 1px grey; padding: 2px 2px 2px 2px; border-radius: 10px;" ng-repeat="NewMessageRecipientList in NewMessageRecipientListObj">
                      <small>
                        {{NewMessageRecipientList.pxName}}
                        <span class="glyphicon glyphicon-remove" style="cursor: pointer;" ng-click="removeremoveNewMessageRecipientReceiver(NewMessageRecipientList)"></span>
                      </small>
                    </span>
                    <input type="text" name="" class="form-control" ng-model="UserAccountsListFilter" style="border: none;">
                  </div>
                  
                  <div ng-show="UserAccountsListFilter != null && UserAccountsListFilter != ''" style="overflow: scroll; max-height: 100px; overflow-x: hidden;">
                    <table class="customTbl table-hover">
                      <tr ng-repeat="UserAccountsList in UserAccountsListObj | filter:UserAccountsListFilter" ng-click="selectReceiver(UserAccountsList)">
                        <td>
                          <span style="cursor: pointer;">
                            {{UserAccountsList.pxName}}
                          </span>
                        </td>
                      </tr>
                    </table>
                    
                  </div>
                </td>
              </tr>
              <tr style="padding: 0px; margin: 0px;">
                <td width="1%">
                  Subject
                </td>
                <td>
                  
                  <input type="text" name="" class="form-control" ng-model="newMessageObj.messageSubject" style="border: none;" ng-change="autoSaveNewMessage(newMessageObj)">
                </td>
              </tr>
              <!-- <tr style="padding: 0px; margin: 0px;">
                <td colspan="2">
                  <textarea data-ui-tinymce id="tinymce2" ng-model="newMessageObj.messageContent"></textarea>
                </td>
              </tr>
              <tr style="padding: 0px; margin: 0px;">
                <td colspan="2">
                  <ul style="list-style-type: none;">
                    <li ng-repeat="attachedFileList in attachedFileListObj">
                      <a>
                        {{attachedFileList.file.name}}
                        &emsp;
                        <span class="glyphicon glyphicon-remove" style="cursor: pointer;" ng-click="removeAttachedFile(attachedFileList)"></span>
                      </a>
                    </li>
                  </ul>
                </td>
              </tr> -->
            </table>


              <textarea data-ui-tinymce id="tinymce2" ng-model="newMessageObj.messageContent" ng-change="autoSaveNewMessage(newMessageObj)"></textarea>
              
              <div style="padding-top: 10px;">
                <ul style="list-style-type: none;">
                  <li ng-repeat="NewMessageAttachFileList in NewMessageAttachFileListObj">
                    <a>
                      {{NewMessageAttachFileList.origFileName}}
                      &emsp;
                      <span class="glyphicon glyphicon-remove" style="cursor: pointer;" ng-click="removeNewMessageAttachedFile(NewMessageAttachFileList.messageAttachFileRID)"></span>
                    </a>
                  </li>
                </ul>
              </div>

          </div>
        </div>
      </div>

      <div class="compose-footer">
        <button id="send" class="btn btn-sm btn-success" type="button" ng-click="sendMessage(newMessageObj)">
          Send
        </button>
        <label for="file-upload" class="custom-file-upload" >
            <i class="glyphicon glyphicon-paperclip" ></i>
        </label>

        <input id="file-upload" type='file' file-model='file' style="display: none;">
       

      </div>
    </div>
    <!-- /compose -->

    <?php
    include "footer.php";
    ?>
  </div>
</div>
