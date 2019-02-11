<?php
 	require_once("Rest.inc.php");
	class API extends REST {
	
		public $data = "";
		
		// const DB_SERVER = "localhost";
		// const DB_USER = "softmo_admin";

		const DB_SERVER = "192.168.1.92";
		const DB_USER = "softmo_admin";
		const DB_PASSWORD = "MedixMySqlServerBox1";
		const DB = "ipadrbg";

		private $db = NULL;
		private $mysqli = NULL;
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		

		/*
		 *  Connect to Database
		*/
		private function dbConnect(){
			$this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
		}
		
		/*
		 * Dynmically call the method based on the query string
		 */
		public function processApi() {
			$func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class "Page not found".
		}

		private static function get_user_agent() {
			return  $_SERVER['HTTP_USER_AGENT'];
		}
		public static function get_ip() {
			$mainIp = '';
			if (getenv('HTTP_CLIENT_IP'))
				$mainIp = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$mainIp = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$mainIp = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$mainIp = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
				$mainIp = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$mainIp = getenv('REMOTE_ADDR');
			else
				$mainIp = 'UNKNOWN';
			return $mainIp;
		}
		public static function get_os() {
			$user_agent = self::get_user_agent();
			$os_platform    =   "Unknown OS Platform";
			$os_array       =   array(
				'/windows nt 10/i'     	=>  'Windows 10',
				'/windows nt 6.3/i'     =>  'Windows 8.1',
				'/windows nt 6.2/i'     =>  'Windows 8',
				'/windows nt 6.1/i'     =>  'Windows 7',
				'/windows nt 6.0/i'     =>  'Windows Vista',
				'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
				'/windows nt 5.1/i'     =>  'Windows XP',
				'/windows xp/i'         =>  'Windows XP',
				'/windows nt 5.0/i'     =>  'Windows 2000',
				'/windows me/i'         =>  'Windows ME',
				'/win98/i'              =>  'Windows 98',
				'/win95/i'              =>  'Windows 95',
				'/win16/i'              =>  'Windows 3.11',
				'/macintosh|mac os x/i' =>  'Mac OS X',
				'/mac_powerpc/i'        =>  'Mac OS 9',
				'/linux/i'              =>  'Linux',
				'/ubuntu/i'             =>  'Ubuntu',
				'/iphone/i'             =>  'iPhone',
				'/ipod/i'               =>  'iPod',
				'/ipad/i'               =>  'iPad',
				'/android/i'            =>  'Android',
				'/blackberry/i'         =>  'BlackBerry',
				'/webos/i'              =>  'Mobile'
			);
			foreach ($os_array as $regex => $value) {
				if (preg_match($regex, $user_agent)) {
					$os_platform    =   $value;
				}
			}   
			return $os_platform;
		}
		public static function  get_browser() {
			$user_agent= self::get_user_agent();
			$browser        =   "Unknown Browser";
			$browser_array  =   array(
				'/msie/i'       =>  'Internet Explorer',
				'/Trident/i'    =>  'Internet Explorer',
				'/firefox/i'    =>  'Firefox',
				'/safari/i'     =>  'Safari',
				'/chrome/i'     =>  'Chrome',
				'/edge/i'       =>  'Edge',
				'/opera/i'      =>  'Opera',
				'/netscape/i'   =>  'Netscape',
				'/maxthon/i'    =>  'Maxthon',
				'/konqueror/i'  =>  'Konqueror',
				'/ubrowser/i'   =>  'UC Browser',
				'/mobile/i'     =>  'Handheld Browser'
			);
			foreach ($browser_array as $regex => $value) {
				if (preg_match($regex, $user_agent)) {
					$browser    =   $value;
				}
			}
			return $browser;
		}
		public static function  get_device(){
			$tablet_browser = 0;
			$mobile_browser = 0;
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
				$tablet_browser++;
			}
			if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
				$mobile_browser++;
			}
			if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
				$mobile_browser++;
			}
			$mobile_ua = strtolower(substr(self::get_user_agent(), 0, 4));
			$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda ','xda-');
			if (in_array($mobile_ua,$mobile_agents)) {
				$mobile_browser++;
			}
			if (strpos(strtolower(self::get_user_agent()),'opera mini') > 0) {
				$mobile_browser++;
		            //Check for tablets on opera mini alternative headers
				$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
				if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
					$tablet_browser++;
				}
			}
			if ($tablet_browser > 0) {
		           // do something for tablet devices
				return 'Tablet';
			}
			else if ($mobile_browser > 0) {
		           // do something for mobile devices
				return 'Mobile';
			}
			else {
		           // do something for everything else
				return 'Computer';
			}   
		}
				
		private function apiLogin(){
			if($this->get_request_method() != "POST"){
			    $this->response('',406);
			}
			$UserData = json_decode(file_get_contents("php://input"),true);

			$email  = (string)$UserData['Username'];
			$password  = (string)$UserData['Password'];


			if($email!="" AND $password!=""){
			    // if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			        $query="SELECT 
			        users.PxRID 
			        , users.UserType 
			        ,CONCAT ( px_data.FirstName,' ',px_data.MiddleName, '. ', px_data.LastName) as pxName
			        ,px_dok.PRC
			        , users.userTypeRID 
			        FROM users 
			        INNER JOIN px_data ON users.PxRID = px_data.PxRID 
			        INNER JOIN px_dok ON users.PxRID = px_dok.PxRID 
			        WHERE UserName = '$email' AND PassWD = '".md5($password)."' LIMIT 1" ;
			        $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			        if($r->num_rows > 0) {
			            // $result = $r->fetch_assoc();

			            while($row = $r->fetch_assoc()){
			            	$result = $row;

							$PxRID = $row['PxRID'];
							$LogIP = $this->get_ip();
							$ComputerName =$this->get_device();
							$OS = $this->get_os();
							$browser =$this->get_browser();

				            $query2="INSERT INTO users_log SET 
								PxRID ='$PxRID'
								, LogIP = '$LogIP'
								, ComputerName ='$ComputerName'
								, OS ='$OS'
								, browser ='$browser'
								";

							$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
						}

						// If success everythig is good send header as "OK" and user details
			            $this->response($this->json($result), 200);
			        }
			        $error = array('status' => "Failed", "msg" => "Wrong Username or Password");
			        $this->response($this->json($error), 404);
			        // $this->response('', 204);	// If no records "No Content" status
			    // }
			}

			$error = array('status' => "Failed", "msg" => "Empty data");
			$this->response($this->json($error), 400);
		}


		private function apiLogout(){
			if($this->get_request_method() != "POST"){
			    $this->response('',406);
			}
			$UserData = json_decode(file_get_contents("php://input"),true);

			$userID  = (string)$UserData['userID'];

			$query = "UPDATE users SET
				userActive = 0
			WHERE userID= '$userID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiGetUserProfile (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];
			
				$query="SELECT px_data.*
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as userPxName
				, CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
				, px_data.FirstName as shortUserPxName
				FROM  px_data
				WHERE PxRID = '$userPxRID' ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetNotifications (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];
			date_default_timezone_set('Asia/Manila');
			$DateNow= date("Y-m-d");

				$query="SELECT dbcal.*
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
				, px_data.foto
				FROM  dbcal
				LEFT JOIN px_data ON px_data.PxRID = dbcal.PxRID
				WHERE dbcal.DokPxRID = '$userPxRID' AND DBDate = '$DateNow'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetNotificationsBirthdays (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

				$query="SELECT 
					px_data.foto
					, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
					FROM users
					LEFT JOIN px_data ON px_data.PxRID = users.PxRID
				 	WHERE DAY(px_data.DOB) = DAY(CURDATE())
				   	AND MONTH(px_data.DOB) = MONTH(CURDATE())";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetNotificationsRequestForModifAlter (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['userPxRID'];

			if ($PxRID == 0) {
				$qAdd ="";
			}else{
				$qAdd = "AND EnteredBy = '$PxRID'";
			}

			$query="SELECT zh_requestAlterationModification.*
			, px_dataRequestedBy.foto
			FROM  zh_requestAlterationModification
			LEFT JOIN px_data AS px_dataRequestedBy ON px_dataRequestedBy.PxRID = zh_requestAlterationModification.requestedBy 
			WHERE zh_requestAlterationModification.requestStatus = 0 AND zh_requestAlterationModification.Deleted = 0 $qAdd";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCheckAccount (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$username = (string)$this->_request['username'];
			$userPassword = (string)$this->_request['userPassword'];
			$userPxRID = (string)$this->_request['userPxRID'];
			
				$query="SELECT *
				FROM  users
				WHERE PxRID = '$userPxRID' AND UserName = '$username' AND PassWD = '".md5($userPassword)."'" ;
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiRenewAccount(){
			if($this->get_request_method() != "POST"){
			    $this->response('',406);
			}
			$UserData = json_decode(file_get_contents("php://input"),true);

			$userPxRID  = (int)$UserData['userPxRID'];
            $username  = (string)$UserData['username'];
            $userPassword  = (string)$UserData['userPassword'];
            $md5userPassword = md5($userPassword);

        	$query = "UPDATE users SET
				username = '$username'
	            , PassWD = '$md5userPassword'
	            , PassRT = '$userPassword'
			WHERE PxRID = '$userPxRID'";
          
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiCheckPxDsig()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			// $UserPxRID = (string)$this->_request['UserPxRID'];
			$PIN = (string)$this->_request['PIN'];

			$query="SELECT * FROM px_dsig 
				WHERE PIN = '$PIN'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204); // no content
		}

		private function apiCheckPxDsigAcct()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PIN = (string)$this->_request['PIN'];
			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * FROM px_dsig 
				WHERE PIN = '$PIN' AND PxRID = '$PxRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204); // no content
		}

		private function apiRenewCheckDuplicatePxDsigAcct()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PIN = (string)$this->_request['PIN'];

			$query="SELECT * FROM px_dsig 
				WHERE PIN = '$PIN'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204); // no content
		}

		private function apiRenewCheckPxDsigAcct(){
			if($this->get_request_method() != "POST"){
			    $this->response('',406);
			}
			$UserData = json_decode(file_get_contents("php://input"),true);

			$PIN  = (int)$UserData['PIN'];
            $PxRID  = (int)$UserData['PxRID'];
            
        	$query = "UPDATE px_dsig SET
				PIN = '$PIN'
			WHERE PxRID = '$PxRID'";
          
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiCheckSysDoorKeys (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
				$PxRID = (int)$this->_request['PxRID'];
				$DoorKnob = (int)$this->_request['DoorKnob'];

				$query="SELECT sys_doorkeys.*
				, sys_doors.DoorSign
				FROM  sys_doorkeys
				LEFT JOIN sys_doors ON sys_doorkeys.DoorKnob = sys_doors.DoorKnob 
				WHERE PxRID = '$PxRID' AND sys_doorkeys.DoorKnob = '$DoorKnob' AND sys_doorkeys.Deleted = 0";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCheckAcctSysDoorKeys (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
				$PxRID = (int)$this->_request['PxRID'];

				$query="SELECT sys_doorkeys.*
				, sys_doors.DoorSign
				FROM  sys_doorkeys
				LEFT JOIN sys_doors ON sys_doorkeys.DoorKnob = sys_doors.DoorKnob 
				WHERE PxRID = '$PxRID' AND sys_doorkeys.Deleted = 0";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetRequestForModifAlter (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
				$PxRID = (int)$this->_request['PxRID'];

				if ($PxRID == 0) {
					$qAdd ="";
				}else{
					$qAdd = "AND EnteredBy = '$PxRID'";
				}
				$query="SELECT zh_requestAlterationModification.*
				, CONCAT(px_dataRequestedBy.FirstName,' ',SUBSTRING(px_dataRequestedBy.MiddleName, 1, 1),'. ',px_dataRequestedBy.LastName) AS RequestedByPxName
				, px_dsigRequestedBy.b64a AS RequestedByPxSign

				, CONCAT(px_dataApprovedBy.FirstName,' ',SUBSTRING(px_dataApprovedBy.MiddleName, 1, 1),'. ',px_dataApprovedBy.LastName) AS ApprovedByPxName
				, px_dsigApprovedBy.b64a AS ApprovedByPxSign

				, CONCAT(px_dataDisapprovedBy.FirstName,' ',SUBSTRING(px_dataDisapprovedBy.MiddleName, 1, 1),'. ',px_dataDisapprovedBy.LastName) AS DisapprovedByPxName
				, px_dsigDisapprovedBy.b64a AS DisapprovedByPxSign

				FROM  zh_requestAlterationModification
				LEFT JOIN px_data AS px_dataRequestedBy ON px_dataRequestedBy.PxRID = zh_requestAlterationModification.requestedBy 
				LEFT JOIN px_dsig AS px_dsigRequestedBy ON px_dsigRequestedBy.PxRID = zh_requestAlterationModification.requestedBy 

				LEFT JOIN px_data AS px_dataApprovedBy ON px_dataApprovedBy.PxRID = zh_requestAlterationModification.approvedBy 
				LEFT JOIN px_dsig AS px_dsigApprovedBy ON px_dsigApprovedBy.PxRID = zh_requestAlterationModification.approvedBy 

				LEFT JOIN px_data AS px_dataDisapprovedBy ON px_dataDisapprovedBy.PxRID = zh_requestAlterationModification.disApprovedBy 
				LEFT JOIN px_dsig AS px_dsigDisapprovedBy ON px_dsigDisapprovedBy.PxRID = zh_requestAlterationModification.disApprovedBy 

				WHERE  zh_requestAlterationModification.Deleted = 0 $qAdd ORDER BY requestStatus";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiInsertRequestForModifAlter(){
			if($this->get_request_method() != "POST"){
			    $this->response('',406);
			}
			$UserData = json_decode(file_get_contents("php://input"),true);

			$EnteredBy  = (int)$UserData['EnteredBy'];
            $requestAlterModRID  = (string)$UserData['requestAlterModRID'];
            $requestType  = (string)$UserData['requestType'];
            $requestDescription  = (string)$UserData['requestDescription'];
            $disApprovedDescription  = (string)$UserData['disApprovedDescription'];

            if ($requestAlterModRID > 0) {
            	$query = "UPDATE zh_requestAlterationModification SET
					EnteredBy = '$EnteredBy'
		            , requestType = '$requestType'
		            , requestDescription = '$requestDescription'
		            , disApprovedDescription = '$disApprovedDescription'
				WHERE requestAlterModRID = '$requestAlterModRID'";
            }else{
            	$query = "INSERT INTO zh_requestAlterationModification SET
					EnteredBy = '$EnteredBy'
		            , requestType = '$requestType'
		            , requestDescription = '$requestDescription'
		            , disApprovedDescription = '$disApprovedDescription'
				";
            }
			

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignRequestedByRequestForModifAlter(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$RequestForModifAlterData = json_decode(file_get_contents("php://input"),true);

			$requestAlterModRID  = (int)$RequestForModifAlterData['requestAlterModRID'];
			$PxRID= (string)$RequestForModifAlterData['PxRID'];
			date_default_timezone_set('Asia/Manila');
			$dateRequested= date("Y-m-d H:i:s");


			$query = "UPDATE zh_requestAlterationModification SET
				requestedBy= '$PxRID'
				, dateRequested= '$dateRequested'

			WHERE requestAlterModRID = '$requestAlterModRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiSignApprovedByRequestForModifAlter(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$requestAlterModRID  = (int)$ConsentSurgItem['requestAlterModRID'];
			$requestStatus= (string)$ConsentSurgItem['requestStatus'];
			$requestStatusDesc= (string)$ConsentSurgItem['requestStatusDesc'];
			$PxRID= (string)$ConsentSurgItem['PxRID'];
			date_default_timezone_set('Asia/Manila');
			$dateApproved= date("Y-m-d H:i:s");

			$query = "UPDATE zh_requestAlterationModification SET
				approvedBy= '$PxRID'
				, dateApproved= '$dateApproved'
				, requestStatus= '$requestStatus'
				, requestStatusDesc= '$requestStatusDesc'

			WHERE requestAlterModRID = '$requestAlterModRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiSignDisapprovedByRequestForModifAlter(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$requestAlterModRID  = (int)$ConsentSurgItem['requestAlterModRID'];
			$PxRID= (string)$ConsentSurgItem['PxRID'];
			$requestStatus= (string)$ConsentSurgItem['requestStatus'];
			$requestStatusDesc= (string)$ConsentSurgItem['requestStatusDesc'];
			$disApprovedDescription= (string)$ConsentSurgItem['disApprovedDescription'];
			date_default_timezone_set('Asia/Manila');
			$dateDisApproved= date("Y-m-d H:i:s");


			$query = "UPDATE zh_requestAlterationModification SET
				disApprovedBy= '$PxRID'
				, dateDisApproved= '$dateDisApproved'
				, requestStatus= '$requestStatus'
				, disApprovedDescription= '$disApprovedDescription'
				, requestStatusDesc= '$requestStatusDesc'

			WHERE requestAlterModRID = '$requestAlterModRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

	


		# API Floor

		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>