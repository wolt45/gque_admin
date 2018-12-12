<?php
 	require_once("Rest.inc.php");
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
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
			            $result = $r->fetch_assoc();

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