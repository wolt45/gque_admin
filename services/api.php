<?php
 	require_once("Rest.inc.php");

	class API extends REST {
		
		public $data = "";

		public static $myAccess = "";
		private $q_path = "../../myConfig/myAccess.txt";

		const DB_SERVER = "localhost";			//not in use (replace myAccess)
		const DB_USER = "softmo_admin";
		const DB_PASSWORD = "MedixMySqlServerBox1";
		const DB = "ipadrbg";


		private $db = NULL;
		private $mysqli = NULL;
		public function __construct(){
			$this->myAccess = file_get_contents($this->q_path, "r");

			parent::__construct();		// Init parent contructor
			$this->dbConnect();			// Initiate Database connection
		}
		
		/*
		 *  Connect to Database
		*/
		private function dbConnect(){
			$this->mysqli = new mysqli($this->myAccess, self::DB_USER, self::DB_PASSWORD, self::DB);
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
		
		private static function getmyIP() {
			return  $_SERVER['HTTP_USER_AGENT'];
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


		private function apiGetUserAccounts (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}	

				$query="SELECT users.*
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
				, CONCAT(px_dataRegBy.FirstName,' ',SUBSTRING(px_dataRegBy.MiddleName, 1, 1),'. ',px_dataRegBy.LastName) as pxNameRegBy
				, px_data.foto
				, lkup_userType.userDescription
				FROM  users
				INNER JOIN px_data ON users.PxRID = px_data.PxRID
				LEFT JOIN lkup_userType ON lkup_userType.userTypeRID = users.userTypeRID
				LEFT JOIN px_data AS px_dataRegBy ON px_dataRegBy.PxRID = users.RegBy
				";
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


		private function apiGetNotificationsFollowUpSched()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			// $HospRID = (int)$this->_request['HospRID'];
			date_default_timezone_set('Asia/Manila');
			$today=date('Y-m-d');
			$before_date= date('Y-m-d', strtotime($today. ' + 90 days'));
			$after_date= date('Y-m-d', strtotime($today. ' - 90 days'));

			$query="SELECT zipad_diagsnotes.*
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
				, px_data.foto
			    FROM zipad_diagsnotes
			    LEFT JOIN clinix ON clinix.ClinixRID = zipad_diagsnotes.ClinixRID
			    INNER JOIN px_data ON px_data.PxRID = clinix.PxRID

			    WHERE zipad_diagsnotes.Deleted = 0 AND zipad_diagsnotes.followUpDate >= '$after_date' AND zipad_diagsnotes.followUpDate <= '$before_date'
			    ORDER BY zipad_diagsnotes.NoteValue ASC
				";
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
			$UserData = str_replace("'", "`", $UserData);
			
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
			$UserData = str_replace("'", "`", $UserData);

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
			$RequestForModifAlterData = str_replace("'", "`", $RequestForModifAlterData);

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
			$ConsentSurgItem = str_replace("'", "`", $ConsentSurgItem);

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
			$ConsentSurgItem = str_replace("'", "`", $ConsentSurgItem);

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

	
		//============
		// Operating Room Disinfection
		//============


		private function apiGetOperatingRoomDisinfectionDetail()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$operatingDisinfectCheckRID = (int)$this->_request['operatingDisinfectCheckRID'];

			$query="SELECT zh_operatingRoomDisinfectionChecklistDetail.*
			    , CONCAT(px_dataInitial.FirstName,' ',SUBSTRING(px_dataInitial.MiddleName, 1, 1),'. ',px_dataInitial.LastName) as initialName
			    , px_dsigInitial.b64a AS initialSign
			    FROM zh_operatingRoomDisinfectionChecklistDetail 

			    LEFT JOIN px_data AS px_dataInitial ON px_dataInitial.PxRID = zh_operatingRoomDisinfectionChecklistDetail.initialPxRID
			    LEFT JOIN px_dsig AS px_dsigInitial ON px_dsigInitial.PxRID = zh_operatingRoomDisinfectionChecklistDetail.initialPxRID

				WHERE zh_operatingRoomDisinfectionChecklistDetail.operatingDisinfectCheckRID = '$operatingDisinfectCheckRID' AND zh_operatingRoomDisinfectionChecklistDetail.Deleted = 0 ";
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


		private function apiGetOperatingRoomDisinfection()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			// $HospRID = (int)$this->_request['HospRID'];

			$query="SELECT *
			    FROM zh_operatingRoomDisinfectionChecklist
				WHERE zh_operatingRoomDisinfectionChecklist.Deleted = 0";
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


		private function apiInsertOperatingRoomDisinfectionDetail(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$OperatingRoomDisinfectionData = json_decode(file_get_contents("php://input"),true);
			$OperatingRoomDisinfectionData = str_replace("'", "`", $OperatingRoomDisinfectionData);

			$operatingDisinfectCheckRID  = (int)$OperatingRoomDisinfectionData['operatingDisinfectCheckRID'];
			$operatingDisinfectCheckDetailRID  = (int)$OperatingRoomDisinfectionData['operatingDisinfectCheckDetailRID'];
			$dateTimeEntered  = (string)$OperatingRoomDisinfectionData['dateTimeEntered'];
			$wall  = (string)$OperatingRoomDisinfectionData['wall'];
			$anesthesiaMachine  = (string)$OperatingRoomDisinfectionData['anesthesiaMachine'];
			$orBed  = (string)$OperatingRoomDisinfectionData['orBed'];
			$suctionMachine  = (string)$OperatingRoomDisinfectionData['suctionMachine'];
			$electrocauteryMachine  = (string)$OperatingRoomDisinfectionData['electrocauteryMachine'];
			$orLight  = (string)$OperatingRoomDisinfectionData['orLight'];
			$suppliesCabinet  = (string)$OperatingRoomDisinfectionData['suppliesCabinet'];
			$equipmentCabinet  = (string)$OperatingRoomDisinfectionData['equipmentCabinet'];
			$floor  = (string)$OperatingRoomDisinfectionData['floor'];
			$others  = (string)$OperatingRoomDisinfectionData['others'];
			$remarks  = (string)$OperatingRoomDisinfectionData['remarks'];
	        

	        if ($operatingDisinfectCheckDetailRID > 0) {
	        	$query = "UPDATE zh_operatingRoomDisinfectionChecklistDetail SET
					dateTimeEntered = '$dateTimeEntered'
					, wall = '$wall'
					, anesthesiaMachine = '$anesthesiaMachine'
					, orBed = '$orBed'
					, suctionMachine = '$suctionMachine'
					, electrocauteryMachine = '$electrocauteryMachine'
					, orLight = '$orLight'
					, suppliesCabinet = '$suppliesCabinet'
					, equipmentCabinet = '$equipmentCabinet'
					, floor = '$floor'
					, others = '$others'
					, remarks = '$remarks'
					WHERE operatingDisinfectCheckDetailRID = '$operatingDisinfectCheckDetailRID' ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	        }else{
	        	$query = "INSERT INTO zh_operatingRoomDisinfectionChecklistDetail SET
					operatingDisinfectCheckRID = '$operatingDisinfectCheckRID'
					, dateTimeEntered = '$dateTimeEntered'
					, wall = '$wall'
					, anesthesiaMachine = '$anesthesiaMachine'
					, orBed = '$orBed'
					, suctionMachine = '$suctionMachine'
					, electrocauteryMachine = '$electrocauteryMachine'
					, orLight = '$orLight'
					, suppliesCabinet = '$suppliesCabinet'
					, equipmentCabinet = '$equipmentCabinet'
					, floor = '$floor'
					, others = '$others'
					, remarks = '$remarks'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

	        }
			
		}


		private function apiInsertOperatingRoomDisinfection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$OperatingRoomDisinfectionData = json_decode(file_get_contents("php://input"),true);

			$room  = (string)$OperatingRoomDisinfectionData['room'];
			$operatingDisinfectCheckRID  = (int)$OperatingRoomDisinfectionData['operatingDisinfectCheckRID'];

	        if ($operatingDisinfectCheckRID > 0) {
	        	$query = "UPDATE zh_operatingRoomDisinfectionChecklist SET
					room = '$room'
					
					WHERE operatingDisinfectCheckRID = '$operatingDisinfectCheckRID' ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	        }else{
	        	$query = "INSERT INTO zh_operatingRoomDisinfectionChecklist SET
					room = '$room' ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

	        }
			
		}



		private function apiSignOperatingRoomDisinfection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentForAdmissionData = json_decode(file_get_contents("php://input"),true);

			$operatingDisinfectCheckDetailRID  = (int)$ConsentForAdmissionData['operatingDisinfectCheckDetailRID'];
			$initialPxRID  = (int)$ConsentForAdmissionData['initialPxRID'];

	         
			$query = "UPDATE zh_operatingRoomDisinfectionChecklistDetail SET
				initialPxRID = '$initialPxRID'
				
				WHERE operatingDisinfectCheckDetailRID = '$operatingDisinfectCheckDetailRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiRemoveOperatingRoomDisinfectionDetail(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$OperatingRoomDisinfectionData = json_decode(file_get_contents("php://input"),true);

			$operatingDisinfectCheckDetailRID  = (int)$OperatingRoomDisinfectionData['operatingDisinfectCheckDetailRID'];

	         
			$query = "UPDATE zh_operatingRoomDisinfectionChecklistDetail SET
				Deleted = 1
				
				WHERE operatingDisinfectCheckDetailRID = '$operatingDisinfectCheckDetailRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiRemoveOperatingRoomDisinfection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$OperatingRoomDisinfectionData = json_decode(file_get_contents("php://input"),true);

			$operatingDisinfectCheckRID  = (int)$OperatingRoomDisinfectionData['operatingDisinfectCheckRID'];

	         
			$query = "UPDATE zh_operatingRoomDisinfectionChecklist SET
				Deleted = 1
				
				WHERE operatingDisinfectCheckRID = '$operatingDisinfectCheckRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiNewOperatingRoomDisinfection()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			// $HospRID = (int)$this->_request['HospRID'];

			$query = "INSERT INTO zh_operatingRoomDisinfectionChecklist SET
				room = ''
				";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			$query="SELECT *
			    FROM zh_operatingRoomDisinfectionChecklist
				WHERE zh_operatingRoomDisinfectionChecklist.Deleted = 0
				ORDER BY operatingDisinfectCheckRID DESC LIMIT 1";
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


		//============
		//End Operating Room Disinfection
		//============






		private function apiGetAllFollowUpSched()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			// $HospRID = (int)$this->_request['HospRID'];
			date_default_timezone_set('Asia/Manila');
			$today=date('Y-m-d');
			$before_date= date('Y-m-d', strtotime($today. ' + 90 days'));
			$after_date= date('Y-m-d', strtotime($today. ' - 90 days'));

			$query="SELECT zipad_diagsnotes.*
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
			    FROM zipad_diagsnotes
			    LEFT JOIN clinix ON clinix.ClinixRID = zipad_diagsnotes.ClinixRID
			    INNER JOIN px_data ON px_data.PxRID = clinix.PxRID
			    WHERE zipad_diagsnotes.Deleted = 0 AND zipad_diagsnotes.followUpDate >= '$after_date' AND zipad_diagsnotes.followUpDate <= '$before_date'
			    ORDER BY zipad_diagsnotes.followUpDate ASC
				";
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


		private function apiGetAllFollowUpSchedNotes()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			// $HospRID = (int)$this->_request['HospRID'];
			date_default_timezone_set('Asia/Manila');
			$today=date('Y-m-d');
			$before_date= date('Y-m-d', strtotime($today. ' + 90 days'));
			$after_date= date('Y-m-d', strtotime($today. ' - 90 days'));

			$query="SELECT zipad_diagsnotes.*
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
			    FROM zipad_diagsnotes
			    LEFT JOIN px_data ON px_data.PxRID = zipad_diagsnotes.PxRID
			    ORDER BY zipad_diagsnotes.NoteValue ASC
				";
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


		private function apiChangeStatFlag(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$OperatingRoomDisinfectionData = json_decode(file_get_contents("php://input"),true);
			$OperatingRoomDisinfectionData = str_replace("'", "`", $OperatingRoomDisinfectionData);

			$wrid  = (int)$OperatingRoomDisinfectionData['wrid'];
			$columnToChange  = (string)$OperatingRoomDisinfectionData['columnToChange'];
			$columnValue  = (string)$OperatingRoomDisinfectionData['columnValue'];

	         
			$query = "UPDATE zipad_diagsnotes SET
				$columnToChange = $columnValue
				
				WHERE wrid = '$wrid'
				";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}







		private function apiGetDrugDepartment (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
				$query="SELECT * 
				FROM mx_department 
				";
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

		private function apiGetDrugList (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
				$query="SELECT drugs.* 
				, mx_department.DeptDesc
				FROM drugs 
				LEFT JOIN mx_department ON mx_department.DeptCode = drugs.DeptCode";
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





		private function apiInsertMedicine(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MedicineData = json_decode(file_get_contents("php://input"),true);
			$MedicineData = str_replace("'", "`", $MedicineData);

            $DrugRID = (int)$MedicineData['DrugRID'];
            $GlobalRID = (int)$MedicineData['GlobalRID'];
            $MIMSid = (int)$MedicineData['MIMSid'];
            $GenericName = (string)$MedicineData['GenericName'];
            $BrandName = (string)$MedicineData['BrandName'];
            $qtyOnHand = (string)$MedicineData['qtyOnHand'];
            $OnOrder = (string)$MedicineData['OnOrder'];
            $ReOrderPoint = (string)$MedicineData['ReOrderPoint'];
            $Packaging = (string)$MedicineData['Packaging'];
            $PreparationQty = (string)$MedicineData['PreparationQty'];
            $PreparationUnit = (string)$MedicineData['PreparationUnit'];
            $AdvertiserTag = (string)$MedicineData['AdvertiserTag'];
            $DrugUnitRID = (string)$MedicineData['DrugUnitRID'];
            $DefDosage = (string)$MedicineData['DefDosage'];
            $DefDrugDesperseRID = (string)$MedicineData['DefDrugDesperseRID'];
            $DefMedBagnosRID = (string)$MedicineData['DefMedBagnosRID'];
            $DefMedBagnosis = (string)$MedicineData['DefMedBagnosis'];
            $DefIntervalRID = (string)$MedicineData['DefIntervalRID'];
            $DefXDays = (string)$MedicineData['DefXDays'];
            $EnteredBy = (string)$MedicineData['EnteredBy'];
            $ModifiedBy = (string)$MedicineData['ModifiedBy'];
            $DateModified = (string)$MedicineData['DateModified'];
            $Manufacturer = (string)$MedicineData['Manufacturer'];
            $Distributor = (string)$MedicineData['Distributor'];
            $Marketer = (string)$MedicineData['Marketer'];
            $Contents = (string)$MedicineData['Contents'];
            $Indications = (string)$MedicineData['Indications'];
            $Dosage = (string)$MedicineData['Dosage'];
            $Overdosage = (string)$MedicineData['Overdosage'];
            $Administration = (string)$MedicineData['Administration'];
            $Contraindications = (string)$MedicineData['Contraindications'];
            $SpecialPrecautions = (string)$MedicineData['SpecialPrecautions'];
            $AdverseDrugReactions = (string)$MedicineData['AdverseDrugReactions'];
            $PregnancyCategory = (string)$MedicineData['PregnancyCategory'];
            $Storage = (string)$MedicineData['Storage'];
            $Description = (string)$MedicineData['Description'];
            $MechanismofAction = (string)$MedicineData['MechanismofAction'];
            $ATCClassification = (string)$MedicineData['ATCClassification'];
            $PoisonSchedule = (string)$MedicineData['PoisonSchedule'];
            $Presentation = (string)$MedicineData['Presentation'];
            $DeptCode = (int)$MedicineData['DeptCode'];
            $InSynched = (int)$MedicineData['InSynched'];
            $InActive = (int)$MedicineData['InActive'];

	        
	        if ($DrugRID > 0) {
	        	$query = "UPDATE drugs SET
			            GlobalRID = '$GlobalRID'
			            , MIMSid = '$MIMSid'
			            , GenericName = '$GenericName'
			            , BrandName = '$BrandName'
			            , qtyOnHand = '$qtyOnHand'
			            , OnOrder = '$OnOrder'
			            , ReOrderPoint = '$ReOrderPoint'
			            , Packaging = '$Packaging'
			            , PreparationQty = '$PreparationQty'
			            , PreparationUnit = '$PreparationUnit'
			            , AdvertiserTag = '$AdvertiserTag'
			            , DrugUnitRID = '$DrugUnitRID'
			            , DefDosage = '$DefDosage'
			            , DefDrugDesperseRID = '$DefDrugDesperseRID'
			            , DefMedBagnosRID = '$DefMedBagnosRID'
			            , DefMedBagnosis = '$DefMedBagnosis'
			            , DefIntervalRID = '$DefIntervalRID'
			            , DefXDays = '$DefXDays'
			            , EnteredBy = '$EnteredBy'
			            , ModifiedBy = '$ModifiedBy'
			            , DateModified = '$DateModified'
			            , Manufacturer = '$Manufacturer'
			            , Distributor = '$Distributor'
			            , Marketer = '$Marketer'
			            , Contents = '$Contents'
			            , Indications = '$Indications'
			            , Dosage = '$Dosage'
			            , Overdosage = '$Overdosage'
			            , Administration = '$Administration'
			            , Contraindications = '$Contraindications'
			            , SpecialPrecautions = '$SpecialPrecautions'
			            , AdverseDrugReactions = '$AdverseDrugReactions'
			            , PregnancyCategory = '$PregnancyCategory'
			            , Storage = '$Storage'
			            , Description = '$Description'
			            , MechanismofAction = '$MechanismofAction'
			            , ATCClassification = '$ATCClassification'
			            , PoisonSchedule = '$PoisonSchedule'
			            , Presentation = '$Presentation'
			            , DeptCode = '$DeptCode'
			            , InSynched = '$InSynched'
			            , InActive = '$InActive'
		           
					WHERE DrugRID = '$DrugRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	        }else{
	        	$query = "INSERT INTO drugs SET
						GlobalRID = '$GlobalRID'
			            , MIMSid = '$MIMSid'
			            , GenericName = '$GenericName'
			            , BrandName = '$BrandName'
			            , qtyOnHand = '$qtyOnHand'
			            , OnOrder = '$OnOrder'
			            , ReOrderPoint = '$ReOrderPoint'
			            , Packaging = '$Packaging'
			            , PreparationQty = '$PreparationQty'
			            , PreparationUnit = '$PreparationUnit'
			            , AdvertiserTag = '$AdvertiserTag'
			            , DrugUnitRID = '$DrugUnitRID'
			            , DefDosage = '$DefDosage'
			            , DefDrugDesperseRID = '$DefDrugDesperseRID'
			            , DefMedBagnosRID = '$DefMedBagnosRID'
			            , DefMedBagnosis = '$DefMedBagnosis'
			            , DefIntervalRID = '$DefIntervalRID'
			            , DefXDays = '$DefXDays'
			            , EnteredBy = '$EnteredBy'
			            , ModifiedBy = '$ModifiedBy'
			            , DateModified = '$DateModified'
			            , Manufacturer = '$Manufacturer'
			            , Distributor = '$Distributor'
			            , Marketer = '$Marketer'
			            , Contents = '$Contents'
			            , Indications = '$Indications'
			            , Dosage = '$Dosage'
			            , Overdosage = '$Overdosage'
			            , Administration = '$Administration'
			            , Contraindications = '$Contraindications'
			            , SpecialPrecautions = '$SpecialPrecautions'
			            , AdverseDrugReactions = '$AdverseDrugReactions'
			            , PregnancyCategory = '$PregnancyCategory'
			            , Storage = '$Storage'
			            , Description = '$Description'
			            , MechanismofAction = '$MechanismofAction'
			            , ATCClassification = '$ATCClassification'
			            , PoisonSchedule = '$PoisonSchedule'
			            , Presentation = '$Presentation'
			            , DeptCode = '$DeptCode'
			            , InSynched = '$InSynched'
			            , InActive = '$InActive'
					";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	        }
			
		}






		// Messages

		private function apiGetNewMessages (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];


				$query="SELECT messageBox.*
				, CONCAT(px_dataSendBy.FirstName,' ',SUBSTRING(px_dataSendBy.MiddleName, 1, 1),'. ',px_dataSendBy.LastName) as pxNameSender
				, px_dataSendBy.foto AS fotoSender
				, messageRecipient.messageViewed
				, messageRecipient.messageAlert
				, messageRecipient.toRID
				FROM  messageRecipient
				LEFT JOIN messageBox ON messageBox.messageBoxRID = messageRecipient.messageBoxRID
				LEFT JOIN px_data AS px_dataSendBy ON px_dataSendBy.PxRID = messageBox.byRID
				WHERE messageRecipient.toRID = '$userPxRID' AND messageRecipient.messageViewed = 0 AND messageBox.byRID > 0 AND messageBox.Deleted = 0";

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

		private function apiSendMessage(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);
			$MessageData = str_replace("'", "`", $MessageData);

			$byRID  = (int)$MessageData['byRID'];
			$toRID  = (string)$MessageData['toRID'];
			$messageSubject  = (string)$MessageData['messageSubject'];
			$messageSubject  = str_replace("'", "`", $messageSubject);
			$messageContent  = (string)$MessageData['messageContent'];
			$messageContent  = str_replace("'", "`", $messageContent);

			$messageBoxRID  = (int)$MessageData['messageBoxRID'];


        	$query = "UPDATE messageBox SET
				byRID = '$byRID'
				, messageSubject = '$messageSubject'
				, messageContent = '$messageContent'
				WHERE messageBoxRID = '$messageBoxRID'
			";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// $query = "INSERT INTO messageRecipient SET
			// 	toRID = '$toRID'
			// 	, messageBoxRID = '$messageBoxRID'

			// ";
			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			
		}


		private function apiAutoSaveNewMessage(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);
			$MessageData = str_replace("'", "`", $MessageData);

			$messageSubject  = (string)$MessageData['messageSubject'];
			$messageSubjectTemp  = str_replace("'", "`", $messageSubject);
			$messageContent  = (string)$MessageData['messageContent'];
			$messageContentTemp  = str_replace("'", "`", $messageContent);

			$messageBoxRID  = (int)$MessageData['messageBoxRID'];


	        	$query = "UPDATE messageBox SET
					messageSubject = '$messageSubjectTemp'
					, messageContent = '$messageContentTemp'
					WHERE messageBoxRID = '$messageBoxRID'
				";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	        
			
		}



		private function apiGetMessages (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];

				$query="SELECT messageBox.*
				, CONCAT(px_dataSendBy.FirstName,' ',SUBSTRING(px_dataSendBy.MiddleName, 1, 1),'. ',px_dataSendBy.LastName) as pxNameSender
				, px_dataSendBy.foto AS fotoSender
				, messageRecipient.messageViewed
				, messageRecipient.messageAlert
				, messageRecipient.toRID
				, messageAttachFile.messageAttachFileRID
				FROM  messageRecipient
				LEFT JOIN messageBox ON messageBox.messageBoxRID = messageRecipient.messageBoxRID
				LEFT JOIN px_data AS px_dataSendBy ON px_dataSendBy.PxRID = messageBox.byRID
				LEFT JOIN messageAttachFile ON messageAttachFile.messageAttachFileRID = messageBox.messageBoxRID
				WHERE messageRecipient.toRID = '$userPxRID' AND messageBox.byRID > 0 AND messageBox.Deleted = 0";
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


		private function apiGetDraftMessages (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];

				$query="SELECT messageBox.*
				, CONCAT(px_dataSendBy.FirstName,' ',SUBSTRING(px_dataSendBy.MiddleName, 1, 1),'. ',px_dataSendBy.LastName) as pxNameSender
				, px_dataSendBy.foto AS fotoSender

				, messageAttachFile.messageAttachFileRID
				FROM  messageBox

				LEFT JOIN px_data AS px_dataSendBy ON px_dataSendBy.PxRID = messageBox.byRID
				LEFT JOIN messageAttachFile ON messageAttachFile.messageAttachFileRID = messageBox.messageBoxRID
				WHERE messageBox.EnteredBy = '$userPxRID' AND messageBox.byRID = 0 AND messageBox.Deleted = 0";
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



		private function apiViewMessages(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);

			$messageBoxRID  = (int)$MessageData['messageBoxRID'];
			$toRID  = (int)$MessageData['toRID'];
			
	         
			$query = "UPDATE messageRecipient SET
				messageViewed = 1
			WHERE messageBoxRID = '$messageBoxRID' AND toRID = '$toRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiAlertMessages(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);

			$messageBoxRID  = (int)$MessageData['messageBoxRID'];
			$toRID  = (int)$MessageData['toRID'];
			
	         
			$query = "UPDATE messageRecipient SET
				messageAlert = 1
			WHERE messageBoxRID = '$messageBoxRID' AND toRID = '$toRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiDeleteMessage(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);

			$messageBoxRID  = (int)$MessageData['messageBoxRID'];
			
	         
			$query = "UPDATE messageBox SET
				Deleted = 1
			WHERE messageBoxRID = '$messageBoxRID'
				";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}



		private function apiGetMessageWhereToAttachFile (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];
			$PxRID = (int)$this->_request['PxRID'];

				$query="SELECT *

				FROM  messageBox
				WHERE messageBox.byRID = '$userPxRID' AND messageBox.toRID = '$PxRID' ORDER BY messageBoxRID DESC LIMIT 1 ";
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




		private function apiSendAttachFileToMessage(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

            	$messageBoxRID=$_POST['messageBoxRID'];
				
				$path = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
				$origFileName = $_FILES['image']['name'];
				$fileName = $messageBoxRID."_".time().'.'.$path;

				move_uploaded_file($_FILES['image']['tmp_name'], "../../dump_hosp/" .$fileName);

		        $query = "INSERT INTO  messageAttachFile SET
					messageBoxRID = '$messageBoxRID'
					, fileName = '$fileName'
					, origFileName = '$origFileName'
				";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
           
		}





		private function apiCreateNewMessage (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userPxRID = (int)$this->_request['userPxRID'];

			$query = "INSERT INTO  messageBox SET
				EnteredBy = '$userPxRID'
			";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			$query1="SELECT *
			FROM  messageBox
			WHERE EnteredBy = '$userPxRID' AND messageBox.Deleted = 0
			ORDER BY messageBoxRID DESC LIMIT 1";
			$r1 = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);
			if($r1->num_rows > 0) {
				$result1 = array();
				while($row1 = $r1->fetch_assoc()){
					$result1 = $row1;
				}
				$this->response($this->json($result1, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetNewMessageAttachFile (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$messageBoxRID = (int)$this->_request['messageBoxRID'];
			
				$query="SELECT *

				FROM  messageAttachFile
				WHERE messageAttachFile.messageBoxRID = '$messageBoxRID' AND Deleted = 0 ";
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


		private function apiRemoveNewMessageAttachedFile(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);

			$messageAttachFileRID  = (int)$MessageData['messageAttachFileRID'];
			
	         
			$query = "UPDATE messageAttachFile SET
				Deleted = 1
			WHERE messageAttachFileRID = '$messageAttachFileRID'
				";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiGetNewMessageRecipient (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$messageBoxRID = (int)$this->_request['messageBoxRID'];
			
				$query="SELECT *
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
				FROM  messageRecipient
				LEFT JOIN px_data ON px_data.PxRID = messageRecipient.toRID
				WHERE messageRecipient.messageBoxRID = '$messageBoxRID' AND messageRecipient.Deleted = 0 ";
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

		private function apiInsertNewMessageRecipient(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);

			$messageBoxRID  = (int)$MessageData['messageBoxRID'];
			$toRID  = (int)$MessageData['toRID'];
			
	         
			$query = "INSERT INTO messageRecipient SET
				messageBoxRID = '$messageBoxRID'
				, toRID = '$toRID'
				";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiRemoveNewMessageRecipient(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$MessageData = json_decode(file_get_contents("php://input"),true);

			$messageRecipientRID  = (int)$MessageData['messageRecipientRID'];
			
	         
			$query = "UPDATE messageRecipient SET
				Deleted = 1
			WHERE messageRecipientRID = '$messageRecipientRID'
				";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		// end messages



		
		// Medical Record request
		private function apigetMedRequestList (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
				$query="SELECT rep_requestmedRecord.*
				, clinix.AppDateSet
				, CONCAT(px_data.LastName,', ',px_data.FirstName, ', ',SUBSTRING(px_data.MiddleName, 1,1)) as PxName
				, px_data.foto
				FROM rep_requestmedRecord
				LEFT JOIN px_data ON px_data.PxRID = rep_requestmedRecord.PxRID
				LEFT JOIN clinix ON clinix.ClinixRID = rep_requestmedRecord.ClinixRID
				WHERE rep_requestmedRecord.Deleted = 0
				ORDER BY rep_requestmedRecord.releaseStatus;
				";
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


		private function apiReleaseSignRequest(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ptBillingData = json_decode(file_get_contents("php://input"),true);

			$releaseStatus= (string)$ptBillingData['releaseStatus'];
			$releaseDate= (string)$ptBillingData['releaseDate'];
			$releasePxRID= (int)$ptBillingData['releasePxRID'];
			$requestmedRecordRID  = (int)$ptBillingData['requestmedRecordRID'];


			$query = "UPDATE rep_requestmedRecord SET
				releaseStatus= '$releaseStatus'
				, releaseDate= '$releaseDate'
				, releasePxRID= '$releasePxRID'

			WHERE requestmedRecordRID = '$requestmedRecordRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}



		// Surgical forms fixer
		private function apiGetOperatingRoomScheduleReportAllList()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$fromDate = (string)$this->_request['fromDate'];
			$toDate = (string)$this->_request['toDate'];

			if ($fromDate == '') {
				$doThis = "";
			}else{
				$doThis = "AND (zipad_diags_schedsurgery.SurgeryDate >= '2019-12-22' AND zipad_diags_schedsurgery.SurgeryDate <= '$toDate')";
			}

			// $PxRID = (int)$this->_request['PxRID'];
			date_default_timezone_set('Asia/Manila');
			$dateNow= date("Y-m-d");

			$query="SELECT zipad_diags_schedsurgery.*
			, CONCAT(px_data.LastName,', ',px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'.') as pxName

			, CONCAT(px_dataSigned.FirstName,' ',SUBSTRING(px_dataSigned.MiddleName, 1, 1),'. ',px_dataSigned.LastName) AS singedByName
			, px_dsigSigned.b64a AS singedBySign

			, CONCAT(px_dataorNurse.FirstName,' ',SUBSTRING(px_dataorNurse.MiddleName, 1, 1),'. ',px_dataorNurse.LastName) AS orNurseName
			, px_dsigorNurse.b64a AS orNurseSign

			, px_data.foto

			FROM zipad_diags_schedsurgery
			LEFT JOIN px_data ON px_data.PxRID = zipad_diags_schedsurgery.PxRID

			LEFT JOIN px_data AS px_dataSigned ON px_dataSigned.PxRID = zipad_diags_schedsurgery.signedPxRID
			LEFT JOIN px_dsig AS px_dsigSigned ON px_dsigSigned.PxRID = zipad_diags_schedsurgery.signedPxRID

			LEFT JOIN px_data AS px_dataorNurse ON px_dataorNurse.PxRID = zipad_diags_schedsurgery.orNursePxRID
			LEFT JOIN px_dsig AS px_dsigorNurse ON px_dsigorNurse.PxRID = zipad_diags_schedsurgery.orNursePxRID

			WHERE zipad_diags_schedsurgery.PxRID > 0 AND zipad_diags_schedsurgery.Deleted = 0 $doThis
			ORDER BY zipad_diags_schedsurgery.SurgeryDate
			";
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


		private function apiInsertSurgerySchedule(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$SurgeryScheduleData = json_decode(file_get_contents("php://input"),true);
			$SurgeryScheduleData = str_replace("'", "`", $SurgeryScheduleData);

			$wrid  = (int)$SurgeryScheduleData['wrid'];
			$orCaseRID  = (int)$SurgeryScheduleData['orCaseRID'];
			$ClinixRID  = (int)$SurgeryScheduleData['ClinixRID'];
			$diagnosis  = (string)$SurgeryScheduleData['diagnosis'];
			$SurgeryType  = (string)$SurgeryScheduleData['SurgeryType'];
			$SurgeryType = str_replace("'", "`", $SurgeryType);
			$SurgeryDate  = (string)$SurgeryScheduleData['SurgeryDate'];
			$SurgeryTime  = (string)$SurgeryScheduleData['SurgeryTime'];
			$SurgeryTimeEnd  = (string)$SurgeryScheduleData['SurgeryTimeEnd'];
			if ($SurgeryDate == null) {
				$SurgeryDate = '0000-00-00';
			}else{
				$SurgeryDate = date('Y-m-d', strtotime($SurgeryDate));
			}
			if ($SurgeryTime == null) {
				$SurgeryTime = '00:00:00';
			}else{
				$SurgeryTime = date('H:i:s', strtotime($SurgeryTime));
			}
			if ($SurgeryTimeEnd == null) {
				$SurgeryTimeEnd = '00:00:00';
			}else{
				$SurgeryTimeEnd = date('H:i:s', strtotime($SurgeryTimeEnd));
			}
			$Surgeon  = (string)$SurgeryScheduleData['Surgeon'];
			$Cardio  = (string)$SurgeryScheduleData['Cardio'];
			$Assistant  = (string)$SurgeryScheduleData['Assistant'];
			$Anesthesio  = (string)$SurgeryScheduleData['Anesthesio'];
			$AnesthesiaType  = (string)$SurgeryScheduleData['AnesthesiaType'];
			$Hospital  = (string)$SurgeryScheduleData['Hospital'];
			// $OrNurse  = (string)$SurgeryScheduleData['OrNurse'];
			$scrubNurse  = (string)$SurgeryScheduleData['scrubNurse'];
			$circulatingNurse  = (string)$SurgeryScheduleData['circulatingNurse'];
			$Others  = (string)$SurgeryScheduleData['Others'];
			$operatingRoom  = (string)$SurgeryScheduleData['operatingRoom'];
	         
			$query = "UPDATE zipad_diags_schedsurgery SET
				orCaseRID = '$orCaseRID'
				, diagnosis = '$diagnosis'
				, SurgeryType = '$SurgeryType'
				, ClinixRID = '$ClinixRID'
				, SurgeryDate = '$SurgeryDate'
				, SurgeryTime = '$SurgeryTime'
				, Surgeon = '$Surgeon'
				, SurgeryTimeEnd = '$SurgeryTimeEnd'
				, Cardio = '$Cardio'
				, Assistant = '$Assistant'
				, Anesthesio = '$Anesthesio'
				, AnesthesiaType = '$AnesthesiaType'
				, Hospital = '$Hospital'
				-- , OrNurse = '$OrNurse'
				, scrubNurse = '$scrubNurse'
				, circulatingNurse = '$circulatingNurse'
				, Others = '$Others'
				, operatingRoom = '$operatingRoom'
			WHERE wrid = '$wrid'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apigetPxPreopDiagnosis()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];
			$ClinixRID = (int)$this->_request['ClinixRID'];

			$query="SELECT zh_hospitalchart.diagnosis 
			    ,zipad_diags_schedsurgery.HospRID
			    FROM zipad_diags_schedsurgery
			    LEFT JOIN zh_hospitalchart ON zh_hospitalchart.HospRID = zipad_diags_schedsurgery.HospRID
				WHERE zipad_diags_schedsurgery.HospRID = '$HospRID' AND zipad_diags_schedsurgery.Deleted = 0";
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

		private function apigetLastORCaseNumber()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query="SELECT orCaseRID FROM zipad_diags_schedsurgery WHERE Deleted = 0 ORDER BY orCaseRID DESC LIMIT 1";
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


		private function apiSignSurgerySchedule(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$SurgeryScheduleData = json_decode(file_get_contents("php://input"),true);

			$wrid  = (int)$SurgeryScheduleData['wrid'];
			$signedPxRID  = (int)$SurgeryScheduleData['signedPxRID'];
	         
			$query = "UPDATE zipad_diags_schedsurgery SET
				signedPxRID = '$signedPxRID'
				, surgeryStatus = 3
			WHERE wrid = '$wrid'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignOrNurseSurgerySchedule(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$SurgeryScheduleData = json_decode(file_get_contents("php://input"),true);

			$wrid  = (int)$SurgeryScheduleData['wrid'];
			$signedPxRID  = (int)$SurgeryScheduleData['signedPxRID'];
	         
			$query = "UPDATE zipad_diags_schedsurgery SET
				orNursePxRID = '$signedPxRID'
				, surgeryStatus = 2
			WHERE wrid = '$wrid'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apigetFinalORcaseList()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$toDate = (string)$this->_request['toDate'];
			$doThis = "AND (zipad_diags_schedsurgery.SurgeryDate >= '2019-12-22' AND zipad_diags_schedsurgery.SurgeryDate <= '$toDate')";

			$query="SELECT zipad_diags_schedsurgery.*
			, CONCAT(px_data.LastName,', ',px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'.') as pxName
			, CONCAT(px_dataSigned.FirstName,' ',SUBSTRING(px_dataSigned.MiddleName, 1, 1),'. ',px_dataSigned.LastName) AS singedByName
			, px_dsigSigned.b64a AS singedBySign
			, CONCAT(px_dataorNurse.FirstName,' ',SUBSTRING(px_dataorNurse.MiddleName, 1, 1),'. ',px_dataorNurse.LastName) AS orNurseName
			, px_dsigorNurse.b64a AS orNurseSign

			, px_data.foto

			FROM zipad_diags_schedsurgery
			LEFT JOIN px_data ON px_data.PxRID = zipad_diags_schedsurgery.PxRID
			LEFT JOIN px_data AS px_dataSigned ON px_dataSigned.PxRID = zipad_diags_schedsurgery.signedPxRID
			LEFT JOIN px_dsig AS px_dsigSigned ON px_dsigSigned.PxRID = zipad_diags_schedsurgery.signedPxRID
			LEFT JOIN px_data AS px_dataorNurse ON px_dataorNurse.PxRID = zipad_diags_schedsurgery.orNursePxRID
			LEFT JOIN px_dsig AS px_dsigorNurse ON px_dsigorNurse.PxRID = zipad_diags_schedsurgery.orNursePxRID
			WHERE zipad_diags_schedsurgery.orCaseRID > 0 AND zipad_diags_schedsurgery.PxRID > 0 AND zipad_diags_schedsurgery.Deleted = 0 $doThis
			ORDER BY zipad_diags_schedsurgery.orCaseRID
			";
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







		private function apigetQues(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query = "SELECT que_regs.*
				, StatusDesc
				FROM que_regs
				INNER JOIN lkup_que_status ON que_regs.questatus = lkup_que_status.questatus
				WHERE que_regs.questatus < 99
				ORDER BY que_regs.questatus, que_regs.qregsRID ASC
			;";
			// , CONCAT(que_regs.LastName,', ',que_regs.FirstName,' ',SUBSTRING(que_regs.MiddleName, 1, 1),'.') as pxName
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

		private function apiqueAction(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$rid = (int)$this->_request['rid'];
			$stts = (int)$this->_request['stts'];

			$query = "UPDATE que_regs
				SET questatus = $stts
				WHERE qregsRID = $rid 
			;";
			$this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiqueRESET(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$query = "INSERT INTO que_master (
				qforrid
				, DateEntered
				, LastName
				, FirstName
				, MiddleName
				, questatus
				)

				SELECT qregsRID
					, DateEntered
					, LastName
					, FirstName
					, MiddleName
					, questatus
				FROM que_regs
				;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			$queryVS = "INSERT INTO que_mastervs (
				qregsRID
				, VitalsRID
				, DateEntered
				, VitalsStamp
				, Value
				)
				SELECT qregsRID
					, VitalsRID
					, DateEntered
					, VitalsStamp
					, Value
				FROM que_vitals
				;";

			// $wfp = fopen("zzz.VSM.txt", "w");
			// fwrite($wfp, $queryVS);
			// fclose($wfp);
			
			$r = $this->mysqli->query($queryVS) or die($this->mysqli->error.__LINE__);

			$queryDEC = "INSERT INTO que_masterdeclaration (
				qregsRID
				, DateEntered
				, VitalsStamp
				, ubo
				, sipon
				, hilanat
				, lupot
				, sorethroat
				, headache
				, bodyache
				, shortbreath
				, notaste
				, nosmell
				, nakabyahe
				, nakabyahe_placeexit
				, nakabyahe_datedeparture
				, nakabyahe_datearrival
				, nakatiner
				, nakatiner_placeexit
				, nakatiner_datedeparture
				, nakatiner_datearrival
				, nakaatubang
				, may_pending_rt_pcr
				)
				SELECT qregsRID
					, DateEntered
					, VitalsStamp
					, ubo
					, sipon
					, hilanat
					, lupot
					, sorethroat
					, headache
					, bodyache
					, shortbreath
					, notaste
					, nosmell
					, nakabyahe
					, nakabyahe_placeexit
					, nakabyahe_datedeparture
					, nakabyahe_datearrival
					, nakatiner
					, nakatiner_placeexit
					, nakatiner_datedeparture
					, nakatiner_datearrival
					, nakaatubang
					, may_pending_rt_pcr
				FROM que_declaration
				;";

			// $wfp = fopen("zzz.DECM.txt", "w");
			// fwrite($wfp, $queryDEC);
			// fclose($wfp);

			$r = $this->mysqli->query($queryDEC) or die($this->mysqli->error.__LINE__);

			$query = "TRUNCATE TABLE que_regs;";
			// $query = "UPDATE que_regs
			// 	SET questatus = 99
			// ;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			$query = "TRUNCATE TABLE que_vitals;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			
			$query = "TRUNCATE TABLE que_declaration;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			$queryRESET = "INSERT INTO que_regs SET qregsRID=99, questatus=99;";
			$r = $this->mysqli->query($queryRESET) or die($this->mysqli->error.__LINE__);
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
	
	$api = new API();
	$api->processApi();
?>