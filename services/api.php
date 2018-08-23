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
		
		// echo"f";
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

		private function apiAuthenticateUser (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$Username = (string)$this->_request['Username'];
			$Password = (string)$this->_request['Password'];
			
				$query="SELECT *
				FROM  users
				WHERE UserName = '$Username' AND PassWD = '".md5($Password)."' LIMIT 1";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {

					while($row = $r->fetch_assoc()){
						$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}
		

		# PX APIs
		private function apiPxList(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query = "SELECT PxRID, LPAD(PxRID, 8, '0') as PxRIDDisp
				,LastName, FirstName, Sex, MaritalStatus
				,CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
				,foto
				,TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge

				FROM px_data 
				ORDER BY PxRIDDisp DESC, LastName, FirstName";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}




		# NonPX APIs
		private function apiNonPxList(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query = "SELECT PxRID, LPAD(PxRID, 8, '0') as PxRIDDisp
				,LastName, FirstName, Sex, MaritalStatus
				,CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
				,foto
				,TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge

				FROM px_data 

				WHERE (PersonDataType='PERSONNEL'
					OR PersonDataType='DOCTOR'
					OR PersonDataType='MEDTECH'
					OR PersonDataType='RADTECH'
					OR PersonDataType='ADMIN'
					OR PersonDataType='CLERK'
					)

				ORDER BY PxRIDDisp DESC, LastName, FirstName";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		


		# HMO List
		private function apiHMOList(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query = "SELECT * FROM lkup_hmo ORDER BY HMO";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}



		private function apiSetAppointment() {	
			if($this->get_request_method() != "POST") {
				$this->response('',406);
			}

			$appointmentDataItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID = (int)$appointmentDataItem['ClinixRID'];
            $DOB = (string)$appointmentDataItem['DOB'];
            $PxRID = (int)$appointmentDataItem['PxRID'];
            $AppDateSet = (string)$appointmentDataItem['AppDateSet'];
            $DateVisit = (string)$appointmentDataItem['AppDateSet'];
            $AppTimeSet = (string)$appointmentDataItem['AppTimeSet'];
            $AppArivalTimeSet = (string)$appointmentDataItem['AppArivalTimeSet'];
            $PurposeOfVisit = (string)$appointmentDataItem['PurposeOfVisit'];
            $DokPxRID = (int)$appointmentDataItem['DokPxRID'];
            $assistingphysic = (int)$appointmentDataItem['assistingphysic'];
            $HospitalRID = (string)$appointmentDataItem['HospitalRID'];
            $Hospital = (string)$appointmentDataItem['Hospital'];
            $TranStatus = (int)$appointmentDataItem['TranStatus'];
			

			if($ClinixRID)
			{
				
				//apiUpdateTranStatusDisp();

				$query1 = "SELECT LastName, FirstName FROM px_data WHERE PxRID = '$DokPxRID'";
				$sQuery = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);

				while($tblPX = mysqli_fetch_object($sQuery))
				{
					$pxName = $tblPX->LastName.", ".$tblPX->FirstName;
				}

				$query2 = "SELECT LastName, FirstName FROM px_data WHERE PxRID = '$assistingphysic'";
				$sQuery2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				while($tblPX2 = mysqli_fetch_object($sQuery2))
				{
					$pxName2 = $tblPX2->LastName.", ".$tblPX2->FirstName;
					
				}

			
				$query="UPDATE clinix SET 
					 PxRID = '$PxRID'
					, AppDateSet='$AppDateSet'
					, DateVisit='$DateVisit'
				 	, AppDateAge = TIMESTAMPDIFF( YEAR, '$DOB', '$AppDateSet')
				 	, AppTimeSet = '$AppTimeSet'
				 	, AppArivalTimeSet = '$AppArivalTimeSet'
					, PurposeOfVisit='$PurposeOfVisit'
					, DokPxRID='$DokPxRID'
					, Dok='$pxName'
					, assistingphysic='$pxName2'
					
					, HospitalRID='$HospitalRID'
					, Hospital='$Hospital'
					WHERE ClinixRID = '$ClinixRID'";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				
			}
			else
			{

				$query1 = "SELECT LastName, FirstName FROM px_data WHERE PxRID = '$DokPxRID'";
				$sQuery = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);

				while($tblPX = mysqli_fetch_object($sQuery))
				{
					$pxName = $tblPX->LastName.", ".$tblPX->FirstName;
				}

				$query2 = "SELECT LastName, FirstName FROM px_data WHERE PxRID = '$assistingphysic'";
				$sQuery2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				while($tblPX2 = mysqli_fetch_object($sQuery2))
				{
					$pxName2 = $tblPX2->LastName.", ".$tblPX2->FirstName;
					
				}

				$query3 = "SELECT TrnStts, preBackColor, preForeColor  FROM lkup_transtatus WHERE TrnSttsRID = '$TranStatus'";
				$sQuery3 = $this->mysqli->query($query3) or die($this->mysqli->error.__LINE__);

				while($tblSt = mysqli_fetch_object($sQuery3))
				{
					$TranStatusDisp = $tblSt->TrnStts;
					$preForeColor = $tblSt->preForeColor;
					$preBackColor = $tblSt->preBackColor;
				}

			
				$query="INSERT INTO clinix SET 
					 PxRID = '$PxRID'
					, AppDateSet='$AppDateSet'
					, DateVisit='$DateVisit'
				 	, AppDateAge = TIMESTAMPDIFF( YEAR, '$DOB', '$AppDateSet')
				 	, AppTimeSet = '$AppTimeSet'
				 	, AppArivalTimeSet = '$AppArivalTimeSet'
					, PurposeOfVisit='$PurposeOfVisit'
					, DokPxRID='$DokPxRID'
					, Dok='$pxName'
					, assistingphysic='$pxName2'
					, TranStatus='$TranStatus'
					, TranStatusDisp='$TranStatusDisp'
					, preForeColor='$preForeColor'
					, preBackColor='$preBackColor'
					
					, HospitalRID='$HospitalRID'
					, Hospital='$Hospital'
					; ";


				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				if ($TranStatus == 1) {
					$query3 = "SELECT ClinixRID FROM clinix WHERE PxRID = '$PxRID' ORDER BY ClinixRID DESC LIMIT 1";
					$sQuery3 = $this->mysqli->query($query3) or die($this->mysqli->error.__LINE__);

					while($tblPX = mysqli_fetch_object($sQuery3))
					{
						$ClinixRID = $tblPX->ClinixRID;

						$query4 = "INSERT INTO zh_hospitalchart SET
							PxRID = '$PxRID'
							, ClinixRID = '$ClinixRID'
							, DokPxRID = '$DokPxRID'
							, TranStatus = '$TranStatus'
							, TranStatusDisp = '$TranStatusDisp'
							";
						$r4 = $this->mysqli->query($query4) or die($this->mysqli->error.__LINE__);
					}
				
				}
				
			}
		}



		private function apiCancelAppointment() {	
			if($this->get_request_method() != "POST") {
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID 	= (int)$pxDataItem['ClinixRID'];

			if($ClinixRID > 0){
				$query="UPDATE clinix SET TranStatus='98'
					WHERE ClinixRID = $ClinixRID
					; ";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				//apiUpdateTranStatusDisp();
				if ($r)
					$this->response($query, 200);
				else
					$this->response('Appointment NOT Cancelled',204);
			}
		}



		private function apiUpdateTranStatusDisp() {
			// $query="INSERT INTO clinix SET PxRID = '$id', AppDateSet='$dt'";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiClinixItem(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT clinix.ClinixRID
					, clinix.PxRID
					, clinix.AppDateSet
					, clinix.AppTimeSet
					, clinix.TranStatus
					, clinix.Dok
					, clinix.DokPxRID
					, clinix.dateTimeRecieved
					, clinix.dateTimeEnded
					, clinix.PurposeOfVisit

					, CONCAT (px_data.LastName, ', ', px_data.FirstName, ', ', px_data.MiddleName) as pxName
					, CONCAT (px_data.Sex,' / ', TIMESTAMPDIFF( YEAR, px_data.DOB, CURDATE( ) ), ' / ', px_data.MaritalStatus) as pxstatus
					, CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
					, TIMESTAMPDIFF( YEAR, px_data.DOB, CURDATE( ) ) as pxAge
					, px_data.foto
					, px_data.RegDate
					, px_data.Balance
					, px_data.Occupation
					, px_data.DOB
					, px_data.Sex
					
					, px_data.ReferralType
					, px_data.ReferredBy

					, px_data.SSS
					, px_data.GSIS
					, px_data.PagIBIG
					, px_data.PhilHealth

					, lkup_TranStatus.TrnStts
					, lkup_TranStatus.preForeColor
					, lkup_TranStatus.preBackColor

					, px_dsigDok.b64a
					, CONCAT(px_dsigNurse.b64a) as pxDsigNurse
					, CONCAT (NurseData.LastName, ', ', NurseData.FirstName, ', ', NurseData.MiddleName) as NurseName
					, px_dok.PRC

					, risita_format.foot1
					, risita_format.foot2

				FROM clinix 
					INNER JOIN px_data ON px_data.PxRID = clinix.PxRID
					INNER JOIN lkup_TranStatus ON clinix.TranStatus = lkup_TranStatus.TrnSttsRID
					LEFT JOIN px_dsig as px_dsigDok ON clinix.px_PEdsig = px_dsigDok.PxRID
					LEFT JOIN px_dsig as px_dsigNurse ON clinix.px_initialdsig = px_dsigNurse.PxRID
					LEFT JOIN px_data as NurseData ON NurseData.PxRID = px_dsigNurse.PxRID
					LEFT JOIN px_dok ON px_dok.PxRID = px_dsigNurse.PxRID
					LEFT JOIN risita_format ON clinix.DokPxRID = risita_format.PxRID
					WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();	
					$this->response($this->json($result), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiDeletePECharges(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id']; //NOTE, ChargeRowRID, not ClinixRID

			//if($id > 0){				
				$query="DELETE FROM zipad_pecharges WHERE PEChargesRID = '$id';";
				
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				$success = array('status' => "Success", "msg" => "Successfully deleted one record.");
				$this->response($this->json($success),200);
			//}else
			//	$this->response('',204);	// If no records "No Content" status
		}

		private function apiClinixCharges(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			//if($id > 0){	
				$query="SELECT 
					zipad_pecharges.PEChargesRID
					, zipad_pecharges.ClinixRID
					, zipad_pecharges.PxRID
					, zipad_pecharges.FeeRID
					, zipad_pecharges.ChargeItem
					, zipad_pecharges.Tariff
					, zipad_pecharges.ChargeAmount
					, zipad_pecharges.Discount
					, zipad_pecharges.NetAmount
					, zipad_pecharges.LinePayment
					, zipad_pecharges.LineBalance

					, zipad_pecharges.SynchStatus
					FROM zipad_pecharges 
					WHERE ClinixRID = '$id'
					ORDER BY zipad_pecharges.PEChargesRID
					";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			//}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetUserData(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * FROM users WHERE PxRID = '$PxRID'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);

		}

		private function apiCheckUser()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);
			$txtUser = (string)$pxDataItem['txtUser'];
			$txtPWD = (string)$pxDataItem['txtPWD'];

			$txtPWDmd5 = md5($txtPWD);

			$query="SELECT * FROM users 
				WHERE UserName = '$txtUser' AND PassWD = '$txtPWDmd5'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);
		}

		private function apiCheckUserExist()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);
			$pxrid = (string)$pxDataItem['pxrid'];
			$txtUser = (string)$pxDataItem['txtUser'];
			$txtPWD = (string)$pxDataItem['txtPWD'];

			$txtPWDmd5 = md5($txtPWD);

			$query="SELECT * FROM users 
				WHERE PxRID = '$pxrid' AND UserName = '$txtUser' AND PassWD = '$txtPWDmd5'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			// $wfp = fopen("zzz.ttt", "w");
			// 	fwrite($wfp, $query);
			// 	fclose($wfp);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);
		}

		private function apiInsUserItem(){	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);

			$username 	= (string)$pxDataItem['username'];

			$query="SELECT * FROM users 
					WHERE UserName='$username'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);
		}

		private function apiInsUserItemSave(){	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);

			$pxrid 	= (int)$pxDataItem['pxrid'];
			$username 	= (string)$pxDataItem['username'];
			$password 	= (string)$pxDataItem['password'];

			$passmd5 = md5($password);

			$query="SELECT *
						FROM px_data 
						WHERE PxRID = '$pxrid'";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				if ($r->num_rows > 0) {

					$result = array();

					while($row = $r->fetch_assoc()){
						$result[] = $row;

						$PersonDataType = $result[0]['PersonDataType'];

							$query="INSERT INTO users SET PxRID = '$pxrid', UserName = '$username', PassWD = '$passmd5', 
							ActivationKey = '$passmd5', UserStatus = 9, UserType = '$PersonDataType', ActivationDate = NOW() ";
							// $wfp = fopen("zzz.ttt", "w");
							// fwrite($wfp, $query);
							// fclose($wfp);

							$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					}
				}
			}

			private function apiInspxdoc(){	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);

			$pxrid 	= (int)$pxDataItem['pxrid'];
          	$Specialty = (string)$pxDataItem['Specialty'];
          	$PRC = (string)$pxDataItem['PRC'];


			$query="SELECT * FROM px_dok WHERE PxRID = '$pxrid'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if ($r->num_rows == 0) 
					{

							$query="INSERT INTO px_dok SET PxRID = '$pxrid', Specialty = '$Specialty', PRC = '$PRC' ";
							// $wfp = fopen("zzz.ttt", "w");
							// fwrite($wfp, $query);
							// fclose($wfp);
					}else
					{
						$query="UPDATE px_dok SET Specialty = '$Specialty', PRC = '$PRC' WHERE PxRID = '$pxrid'";
						// $wfp = fopen("zzz.ttt", "w");
						// fwrite($wfp, $query);
						// fclose($wfp);
					}
				
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}


		private function apiLoadpxdoc(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			//if($id > 0){	
				$query="SELECT * FROM px_dok WHERE PxRID = '$id'";
					// $wfp = fopen("zzz_GMMR.zzz", "w");
					// fwrite($wfp, $query);
					// fclose($wfp);
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}else
				{
					$query="INSERT INTO px_dok 
					SET PxRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					
					$query="SELECT * FROM px_dok WHERE PxRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			//}
			$this->response('',204);	// If no records "No Content" status
		}	








		private function apiUpUserItem(){	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);

			$pxrid 	= (int)$pxDataItem['pxrid'];
            $newusername= (string)$pxDataItem['newusername'];
            $newpassword= (string)$pxDataItem['newpassword'];

			$oldpassmd5 = md5($oldpassword);
			$passmd5 = md5($newpassword);

			$query="UPDATE users SET UserName = '$newusername', PassWD = '$passmd5' WHERE PxRID = '$pxrid'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPxItem(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT p.* 
				, CONCAT (p.LastName, ', ', p.FirstName, ' ', p.MiddleName) as pxName
				, CONCAT (p.Sex,' / ', TIMESTAMPDIFF( YEAR, p.DOB, CURDATE() ), ' / ', p.MaritalStatus) as pxstatus
				, CONCAT (p.Street, ', ', p.City, ', ', p.Province) as pxAddress
				, TIMESTAMPDIFF( YEAR, p.DOB, CURDATE() ) AS pxAge
				, TIMESTAMPDIFF( YEAR, p.DOB, p.RegDate ) AS RegDateAge
				, b64a
				, b64b
				, b64c
				FROM px_data p
					LEFT JOIN px_dsig AS d ON p.PxRID=d.PxRID
				WHERE p.PxRID =$id";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();	
					$this->response($this->json($result), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiLASTPxItem(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
	
			$query="SELECT * FROM px_data ORDER BY PxRID DESC LIMIT 1";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = $r->fetch_assoc();

				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertPxItem(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);
				
				$PxRID = (string)$pxDataItem['PxRID'];
			  	$RegDate = (string)$pxDataItem['RegDate'];
		        $LastName = (string)$pxDataItem['LastName'];
		        $LastName2 = str_replace("'", "`", $LastName);
		        $MiddleName = (string)$pxDataItem['MiddleName'];
		        $MiddleName2 = str_replace("'", "`", $MiddleName);
		        $FirstName = (string)$pxDataItem['FirstName'];
		        $FirstName2 = str_replace("'", "`", $FirstName);
		        $Street = (string)$pxDataItem['Street'];
		        $City = (string)$pxDataItem['City'];
		        $Province = (string)$pxDataItem['Province'];
		        $DOB = (string)$pxDataItem['DOB'];
		        $Sex = (string)$pxDataItem['Sex'];
		        $pxClassification = (string)$pxDataItem['pxClassification'];
		        $TIN = (string)$pxDataItem['TIN'];
		        $SSS = (string)$pxDataItem['SSS'];
		        $GSIS = (string)$pxDataItem['GSIS'];
		        $PagIBIG = (string)$pxDataItem['PagIBIG'];
		        $PhilHealth = (string)$pxDataItem['PhilHealth'];
		        $MobileCon = (string)$pxDataItem['MobileCon'];
		        $OfficeCon = (string)$pxDataItem['OfficeCon'];
		        $HomeCon = (string)$pxDataItem['HomeCon'];
		        $Email = (string)$pxDataItem['Email'];
		        $Employer = (string)$pxDataItem['Employer'];
		        $Occupation = (string)$pxDataItem['Occupation'];
		        $MaritalStatus = (string)$pxDataItem['MaritalStatus'];
		        $FamilyDoctor = (string)$pxDataItem['FamilyDoctor'];
          		$FamilyDoctorSpecialty = (string)$pxDataItem['FamilyDoctorSpecialty'];
          		$FamilyDoctorPhone = (string)$pxDataItem['FamilyDoctorPhone'];
          		$NearestRelative = (string)$pxDataItem['NearestRelative'];
          		$RelativeCon = (string)$pxDataItem['RelativeCon'];
		        $SpouseName = (string)$pxDataItem['SpouseName'];
		        $PersonDataType = (string)$pxDataItem['PersonDataType'];
		        $ReferredBy = (string)$pxDataItem['ReferredBy'];
		        $ReferralType = (string)$pxDataItem['ReferralType'];
		        $RegBy = (string)$pxDataItem['RegBy'];
		        $RegDateAge = (string)$pxDataItem['RegDateAge'];
			
			$query = "INSERT INTO px_data SET 
					FirstName = '$FirstName2', 
					MiddleName = '$MiddleName2',
					LastName = '$LastName2',
					RegDate = '$RegDate',
					Street = '$Street',
					City = '$City',
					Province = '$Province',
					DOB = '$DOB',
					Sex = '$Sex',
					pxClassification = '$pxClassification',
					TIN = '$TIN',
					SSS = '$SSS',
					GSIS = '$GSIS',
					PagIBIG = '$PagIBIG',
					PhilHealth = '$PhilHealth',
					MobileCon = '$MobileCon',
					OfficeCon = '$OfficeCon',
					HomeCon = '$HomeCon',
					Email = '$Email',
					Employer = '$Employer',
					Occupation = '$Occupation',
					FamilyDoctor = '$FamilyDoctor',
	          		FamilyDoctorSpecialty = '$FamilyDoctorSpecialty',
	          		FamilyDoctorPhone = '$FamilyDoctorPhone',
	          		NearestRelative = '$NearestRelative',
	          		RelativeCon = '$RelativeCon',
					MaritalStatus = '$MaritalStatus',
					SpouseName = '$SpouseName',
					PersonDataType = '$PersonDataType',
					ReferralType = '$ReferralType',
					ReferredBy = '$ReferredBy' ,
					RegBy = '$RegBy' ,
					RegDateAge = '$RegDateAge'
					";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}


		private function apiInsertPersonelItem(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);
			  
			  $PxRID = (string)$pxDataItem['PxRID'];
			  $RegDate = (string)$pxDataItem['RegDate'];
		        $LastName = (string)$pxDataItem['LastName'];
		        $LastName2 = str_replace("'", "`", $LastName);
		        $MiddleName = (string)$pxDataItem['MiddleName'];
		        $MiddleName2 = str_replace("'", "`", $MiddleName);
		        $FirstName = (string)$pxDataItem['FirstName'];
		        $FirstName2 = str_replace("'", "`", $FirstName);
		        $Street = (string)$pxDataItem['Street'];
		        $City = (string)$pxDataItem['City'];
		        $Province = (string)$pxDataItem['Province'];
		        $DOB = (string)$pxDataItem['DOB'];
		        $Sex = (string)$pxDataItem['Sex'];
		        $Race = (string)$pxDataItem['Race'];
          		$pxClassification = (string)$pxDataItem['pxClassification'];
		        $TIN = (string)$pxDataItem['TIN'];
		        $SSS = (string)$pxDataItem['SSS'];
		        $GSIS = (string)$pxDataItem['GSIS'];
		        $PagIBIG = (string)$pxDataItem['PagIBIG'];
		        $PhilHealth = (string)$pxDataItem['PhilHealth'];
		        $MobileCon = (string)$pxDataItem['MobileCon'];
		        $OfficeCon = (string)$pxDataItem['OfficeCon'];
		        $HomeCon = (string)$pxDataItem['HomeCon'];
		        $Email = (string)$pxDataItem['Email'];
		        $Occupation = (string)$pxDataItem['Occupation'];
		        $MaritalStatus = (string)$pxDataItem['MaritalStatus'];
		        $SpouseName = (string)$pxDataItem['SpouseName'];
		        $PersonDataType = (string)$pxDataItem['PersonDataType'];
		        $ReferralType = (string)$pxDataItem['ReferralType'];
		        $ReferredBy = (string)$pxDataItem['ReferredBy'];
			
			if(empty($PxRID)){

				$query = "INSERT INTO px_data SET 
					FirstName = '$FirstName', 
					MiddleName = '$MiddleName',
					LastName = '$LastName',
					RegDate = '$RegDate',
					Street = '$Street',
					City = '$City',
					Province = '$Province',
					DOB = '$DOB',
					Sex = '$Sex',
					Race = '$Race',
          			pxClassification = '$pxClassification',
					TIN = '$TIN',
					SSS = '$SSS',
					GSIS = '$GSIS',
					PagIBIG = '$PagIBIG',
					PhilHealth = '$PhilHealth',
					MobileCon = '$MobileCon',
					OfficeCon = '$OfficeCon',
					HomeCon = '$HomeCon',
					Email = '$Email',
					Occupation = '$Occupation',
					MaritalStatus = '$MaritalStatus',
					SpouseName = '$SpouseName',
					PersonDataType = '$PersonDataType',
					ReferralType = '$ReferralType',
					ReferredBy = '$ReferredBy'
					";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				// $wfp = fopen("zzz.ttt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			}else
			{

				$query = "UPDATE px_data SET 
					FirstName = '$FirstName', 
					MiddleName = '$MiddleName',
					LastName = '$LastName',
					RegDate = '$RegDate',
					Street = '$Street',
					City = '$City',
					Province = '$Province',
					DOB = '$DOB',
					Sex = '$Sex',
					pxClassification = '$pxClassification',
					TIN = '$TIN',
					SSS = '$SSS',
					GSIS = '$GSIS',
					PagIBIG = '$PagIBIG',
					PhilHealth = '$PhilHealth',
					MobileCon = '$MobileCon',
					OfficeCon = '$OfficeCon',
					HomeCon = '$HomeCon',
					Email = '$Email',
					Occupation = '$Occupation',
					MaritalStatus = '$MaritalStatus',
					SpouseName = '$SpouseName',
					PersonDataType = '$PersonDataType',
					ReferredBy = '$ReferredBy' WHERE PxRID = '$PxRID'	
					";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			// 	$success = array('status' => "Success", "msg" => "Personel Record Created Successfully.", "data" => $pxDataItem);
			// 	$this->response($this->json($success),200);
			// }else
			// 	$this->response('',204);	//"No Content" status
		}


		//Statistic Classification
		private function apigetPxClass(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$query="SELECT FullName, ClassA, ClassB, ClassC, ClassD FROM rep_StatClassRep ";
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

		private function apigetPxTotalClass(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$query="SELECT SUM(ClassA) AS ClassTotalA, SUM(ClassB) AS ClassTotalB, SUM(ClassC) AS ClassTotalC, SUM(ClassD) AS ClassTotalD FROM rep_StatClassRep ";
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



		private function apiclassgetDate(){	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$DateFrom = (string)$IntraItem['DateFrom'];
			$DateTo = (string)$IntraItem['DateTo'];
             
            $queryDel = "TRUNCATE TABLE rep_StatClassRep";
			$QueryDel = $this->mysqli->query($queryDel) or die($this->mysqli->error.__LINE__);

			$queryDel = "TRUNCATE TABLE rep_QtrMonth";
			$QueryDel = $this->mysqli->query($queryDel) or die($this->mysqli->error.__LINE__);

				$queryMonth = "INSERT INTO rep_QtrMonth SET 
							DateFrom = '".$DateFrom."', 
							DateTo = '".$DateTo."'";
				$queryMonth2 = $this->mysqli->query($queryMonth) or die($this->mysqli->error.__LINE__);

		    	$query = "SELECT DISTINCT DokPxRID FROM clinix WHERE DokPxRID != 0 AND AppDateSet BETWEEN '".$DateFrom."' AND '".$DateTo."'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				while($tblPX = mysqli_fetch_array($r))
				{
					$DokPxRID = $tblPX['DokPxRID'];
					
					$query2 = "SELECT clinix.DokPxRID, clinix.Dok, 
					COUNT(CASE WHEN pxClassification = 'A' THEN 1 ELSE NULL END) AS ClassA, 
					COUNT(CASE WHEN pxClassification = 'B' THEN 1 ELSE NULL END) AS ClassB,
					COUNT(CASE WHEN pxClassification = 'C' THEN 1 ELSE NULL END) AS ClassC,
					COUNT(CASE WHEN pxClassification = 'D' THEN 1 ELSE NULL END) AS ClassD
					FROM clinix INNER JOIN px_data ON clinix.pxrid = px_data.pxrid WHERE (AppDateSet BETWEEN '".$DateFrom."' AND '".$DateTo."') 
					AND clinix.DokPxRID = '".$DokPxRID."' AND clinix.TranStatus != '98' AND clinix.TranStatus != '91' ";
					// $wfp = fopen("zzz.ttt", "w");
					// fwrite($wfp, $query2);
					// fclose($wfp);
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

					while($tblPX2 = mysqli_fetch_array($r2))
					{
						$ClassA = $tblPX2['ClassA'];
						$ClassB = $tblPX2['ClassB'];
						$ClassC = $tblPX2['ClassC'];
						$ClassD = $tblPX2['ClassD'];
						$DokPxRID = $tblPX2['DokPxRID'];
						$Dok = $tblPX2['Dok'];
					

							$query7 = "INSERT INTO rep_StatClassRep SET 
							DokPxRID = '".$DokPxRID."', 
							FullName = '".$Dok."', 
							ClassA = '".$ClassA."',
							ClassB = '".$ClassB."',
							ClassC = '".$ClassC."',
							ClassD = '".$ClassD."'";
							$r7 = $this->mysqli->query($query7) or die($this->mysqli->error.__LINE__);

					}
				
				}
		}

		private function apiclassquartergetDate()
		{	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			// $IntraItem = json_decode(file_get_contents("php://input"),true);

			// $DateFrom  = (string)$IntraItem['DateFrom'];
			// $DateTo = (string)$IntraItem['DateTo'];
		}
		//Statistic Classification - End
		


		private function apiUpdatePxItem(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$pxDataItem = json_decode(file_get_contents("php://input"),true);
			$p_PxRID = (int)$pxDataItem['PxRID'];

			$column_names = array('LastName', 'FirstName', 'MiddleName','Street', 'City', 'Province', 'DOB', 'Sex', 'Race',
				'MobileCon', 'OfficeCon', 'HomeCon', 'Email', 'Occupation', 'Employer',
				'SpouseName', 'FamilyDoctor', 'FamilyDoctorSpecialty', 'MaritalStatus'
				, 'isInsured', 'Insurance', 'InsureCardNumber'
				, 'ReferralType', 'FamilyDoctorPhone', 'NearestRelative', 'RelativeCon', 'ReferredBy'
				, 'Notes' , 'RegDate'
				, 'pxClassification'
				, 'SSS'
				, 'GSIS'
				, 'TIN'
				, 'PagIBIG'
				, 'PhilHealth'
				);
			$keys = array_keys($pxDataItem);

			$columns = '';
			$values = '';

			foreach($column_names as $desired_key){ // Check the PX received. If key does not exist, insert blank into the array.
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $pxDataItem[$desired_key];
				}
				$columns = $columns.$desired_key."='".$$desired_key."',";
			}

			$query = "UPDATE px_data SET ".trim($columns,',')." WHERE PxRID='$p_PxRID'";

			if(!empty($pxDataItem)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				$success = array('status' => "Success", "msg" => "PxData ".$p_PxRID." Updated Successfully.", "data" => $pxDataItem);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// "No Content" status
		}

		

		
		private function apiDeletePxItem() {
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];

			if($id > 0){				
				$query="DELETE FROM px_data WHERE PxRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Successfully deleted one record.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}






		private function apiInsertHMOItem(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);
			//$id = (int)$this->_request['pid'];

			$id  = (int)$pxDataItem['PxRID'];
			$HMORID = (int)$pxDataItem['HMORID'];
			$HMO 	= (string)$pxDataItem['HMO'];


			$query = "INSERT INTO px_hmos (PxRID, HMORID, HMO) 
				VALUES (". $id . ", " . $HMORID . ", '" . $HMO . "' )";

			if(!empty($pxDataItem)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				$queryPX = "UPDATE px_data SET Insurance = CONCAT(`Insurance`, '\n', '$HMO') 
					WHERE PxRID = $id;";
				$rPX = $this->mysqli->query($queryPX) or die($queryPX . "<br>" . $this->mysqli->error.__LINE__);

				$success = array('status' => "Success", "msg" => "HMO Data added Successfully.", "data" => $pxDataItem);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	//"No Content" status
		}




		private function apiTDYCollection(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			//LPAD(zip, 5, '0') as zipcode

			$query="SELECT clinix.ClinixRID, clinix.AppDateSet,
				LPAD(clinix.ClinixRID, 4, '0') as ClinixRIDDisp
				, clinix.TranStatus
				, clinix.AppDateAge
				, clinix.Dok
				, px_data.PxRID, px_data.LastName, px_data.FirstName, 
				CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress, 
				TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge,
				px_data.Sex, px_data.MaritalStatus, px_data.foto,
				lkup_TranStatus.preForeColor, lkup_TranStatus.preBackColor,
				lkup_TranStatus.TrnStts
				FROM clinix
				INNER JOIN px_data ON clinix.PxRID = px_data.PxRID 
				INNER JOIN lkup_TranStatus ON clinix.TranStatus = lkup_TranStatus.TrnSttsRID 
				WHERE clinix.ClinixRID > 0
					
					ORDER BY 
					clinix.TranStatus,
					clinix.AppDateSet,
					clinix.ClinixRID;";

					// AND
					// (clinix.TranStatus=0 
					// OR clinix.TranStatus=1
					// OR clinix.TranStatus=2
					// OR clinix.TranStatus=10
					// OR clinix.TranStatus=20
					// OR clinix.TranStatus=30
					// OR clinix.TranStatus=99
					// OR clinix.TranStatus=200
					// OR clinix.TranStatus=300
					// OR clinix.TranStatus=400
					// OR clinix.TranStatus=500
					// OR clinix.TranStatus=1000
					// )

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}

				// forEach(People.details, function (detail) {
				//   detail.age = parseFloat(detail.age);
				// });
				
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}

			$this->response('',204);	// If no records "No Content" status
		}

		private function apiTranSttsCollection(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query="SELECT * FROM lkup_TranStatus WHERE Deleted = 0;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPEChargesLkUpCollection(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query="SELECT * FROM lkup_clinixcharges 
				WHERE Deleted = 0 
				ORDER BY SortOrder;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetTranStatusZZZ(){	
			if($this->get_request_method() != "GET"){
				$this->response('Use GET Method!',406);
			}

			$id = (int)$this->_request['id'];

			if($id > 0){
				$query="SELECT TrnSttsRID, TrnStts FROM lkup_TranStatus WHERE TrnSttsRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();	
					$this->response($this->json($result), 200); // send user details
				}
			}	
			$this->response('TranStatus not found!', 204);	// If no records "No Content" status
		}


		private function apiInsertPECharge(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$newChargeItem = json_decode(file_get_contents("php://input"),true);

			$query = "INSERT INTO zipad_pecharges (
				  ClinixRID, PxRID
				, FeeRID, ChargeItem, Tariff
				, ChargeAmount
				, Discount , NetAmount
				, SynchStatus, EnteredBy)
				VALUES ("
					. $newChargeItem['ClinixRID'] 
					. ",   ". $newChargeItem['PxRID'] 
					. " , '" . $newChargeItem['FeeRID']
					. "', '" . $newChargeItem['Description']
					. "', '" . $newChargeItem['Tariff']
					. "', '" . $newChargeItem['ChargeAmount']
					. "', '" . $newChargeItem['Discount']
					. "', '" . $newChargeItem['NetAmount']
					. "', '" . 333
					. "', '" . 909
					. "')";

			if(!empty($newChargeItem)){
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "PE Charge Record Created Successfully.", "data" => $newChargeItem);
				$this->response($query,200);
			}else
				$this->response('',204);	//"No Content" status
		}
		
		private function apiUpdatePEChargesWithPayments() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$payments = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($payments); $i++)
			{
				$xPEChargesRID = $payments[$i]['PEChargesRID'];
				$xlinePayment  = $payments[$i]['LinePayment'] * 1;
				$xlineBalance  = $payments[$i]['NetAmount'] - $payments[$i]['LinePayment'];
				
				$query = "UPDATE zipad_pecharges SET
					LinePayment = '$xlinePayment',
					LineBalance = '$xlineBalance'
					WHERE PEChargesRID = '$xPEChargesRID';";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "PE Charge Record Created Successfully.", "data" => $payments);
			$this->response($x , 200);
		}



		private function apiCloseTrans() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$clinixObj = json_decode(file_get_contents("php://input"),true);

			$p_ClinixRID = (int)$clinixObj['ClinixRID'];

			# 1, UPDATE FINANCES & BALANCES
			$query = "UPDATE clinix aliasCLINIX, 
				(SELECT 
					  SUM(ChargeAmount) AS Ttl_ChargeAmount
					, SUM(Discount) 	AS Ttl_Discount	
					, SUM(NetAmount) 	AS Ttl_NetAmount	
					, SUM(LinePayment)  AS Ttl_LinePayment	
				FROM zipad_pecharges WHERE ClinixRID = '$p_ClinixRID') aliasCHARGES
  					  SET aliasCLINIX.Charges   = aliasCHARGES.Ttl_ChargeAmount 
  					, aliasCLINIX.Discount  = aliasCHARGES.Ttl_Discount 
  					, aliasCLINIX.AmountDue = aliasCHARGES.Ttl_NetAmount 
  					, aliasCLINIX.PaidAmount= aliasCHARGES.Ttl_LinePayment 

  				WHERE aliasCLINIX.ClinixRID = '$p_ClinixRID'" ;
			$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
			
			# 2, UPDATE CHIEF COMPLAINT
			# 3, UPDATE PHYSICAL EXAMINATION
			# 



			$query = "UPDATE clinix SET
					TranStatus = 99
					WHERE ClinixRID = '$p_ClinixRID';";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
			$success = array('status' => "Success", "msg" => "Transaction Closed Successfully.", "data" => $clinixObj);
			$this->response($query , 200);
		}



		private function apiGetOrgData() {
			if($this->get_request_method() != "GET"){
				$this->response('Use GET Method!',406);
			}
			$query="SELECT * FROM orgparms;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0) {
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			else
				$this->response('OrgParms not found!', 204);	// If no records "No Content" status
		}


		private function apiInsertBodyParts() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$bpartsObj = json_decode(file_get_contents("php://input"),true);
			
			if (count($bpartsObj) > 0)
			{
				$clinixRID  	= $bpartsObj[0]['ClinixRID'];
				$hip  			= $bpartsObj[0]['HIP'];
				$knee  			= $bpartsObj[0]['KNEE'];

				//blank out table
				$query="DELETE FROM zipad_zclinix WHERE ClinixRID ='$clinixRID';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				$query = "INSERT INTO zipad_zclinix SET
					ClinixRID = '$clinixRID'
					, HIP  = '$hip'
					, KNEE = '$knee'
					;";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Transaction NOW Successfully.", "data" => $bpartsObj);
			}
			$this->response($bpartsObj , 200);
		}


		# PxRID hack
		# PxRID hack
		# PxRID hack

		private function apiInsertZPxRIDNOW() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$zpxRID = (int)$this->_request['zpxRID'];

			// blank out table
			$query="DELETE FROM zipad_zpxnow;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			$query = "INSERT INTO zipad_zpxnow SET
					PxRID = '$zpxRID';";
			$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
			$success = array('status' => "Success", "msg" => "Transaction NOW Successfully.", "data" => $zpxRID);
			$this->response($query , 200);
		}



		private function apiInsertZClinixNOW() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$zclinixRID = (int)$this->_request['zclinixRID'];

			// blank out table
			$query="DELETE FROM zipad_zclinixnow;";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			$query = "INSERT INTO zipad_zclinixnow SET
					ClinixRID = '$zclinixRID';";
			$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
			$success = array('status' => "Success", "msg" => "Transaction NOW Successfully.", "data" => $zclinixRID);
			$this->response($query , 200);
		}


		private function apiGetZClinix() {
			if($this->get_request_method() != "GET"){
				$this->response('Use GET Method!',406);
			}
			$zclinixRID = (int)$this->_request['zclinixRID'];

			if($zclinixRID > 0){	
				$query="SELECT * FROM zipad_zclinix
					WHERE ClinixRID = '$zclinixRID';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		// PX Visit History routines
		// PX Visit History routines
		// PX Visit History routines

		private function apiPxHistory() {
			if($this->get_request_method() != "GET"){
				$this->response('Use GET Method!',406);
			}
			$PxRID = (int)$this->_request['id'];

			if($PxRID > 0){	
				$query="SELECT 
					clinix.ClinixRID
					, clinix.PxRID
					, clinix.DateVisit
					, clinix.AppDateSet
					, clinix.AppTimeSet
					, clinix.AppArivalTimeSet
					, clinix.ChiefComplaint
					, clinix.PurposeOfVisit
					, clinix.Dok
					, clinix.DokPxRID
					, clinix.assistingphysic
					, clinix.HospitalRID
					, clinix.Hospital
					, clinix.TranStatus

					, lkup_TranStatus.TrnStts
					, lkup_TranStatus.preForeColor
					, lkup_TranStatus.preBackColor

				FROM clinix 
					INNER JOIN lkup_TranStatus ON clinix.TranStatus = lkup_TranStatus.TrnSttsRID
					WHERE clinix.PxRID = '$PxRID' ORDER BY clinix.ClinixRID DESC";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		// UPDATES the Chief Complaint in clinix from iPad synch zipad_ioh_chiefcomp table
		private function apiUpdatePxHistory() {
			if($this->get_request_method() != "GET"){
				$this->response('Use GET Method!',406);
			}
			$zPxRID = (int)$this->_request['id'];
			$mSql = "NOT PROCESSED! " . $zPxRID;

			if ($zPxRID > 0) {	

				$query="SELECT ClinixRID, PxRID
						FROM clinix 
						WHERE clinix.PxRID = '$zPxRID' 
						ORDER BY ClinixRID;";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				if ($r->num_rows > 0) {

					$result = array();

					while($row = $r->fetch_assoc()){
						$result[] = $row;

						$z_ClinixRID = $result[0]['ClinixRID'];

						// Gather Chief Complaints
						$mSql = "SELECT * FROM zipad_ioh_chiefcomp WHERE ClinixRID = '$z_ClinixRID';"; 
						$mQry = $this->mysqli->query($mSql) or die($this->mysqli->error.__LINE__);
						
						$mComplaint = "";
						while ($complaint_row = $mQry->fetch_assoc()){
							$resultComplaint[] = $complaint_row;

							$mComplaint .= $resultComplaint[0]['MyBoneComplaint'] . "&nbsp;";
							$mComplaint .= $resultComplaint[0]['MyBoneLRB'] . "&nbsp;";
							$mComplaint .= $resultComplaint[0]['MyBone'] . "&nbsp;";
							$mComplaint .= $resultComplaint[0]['Remarks'] . "&nbsp;";
						}

						if ($mComplaint) {
							$mSql = "UPDATE clinix SET ChiefComplaint = '$mComplaint' WHERE ClinixRID = '$z_ClinixRID';"; 
							$r = $this->mysqli->query($query) or die($mSql."<br>".$this->mysqli->error.__LINE__);
						}
					}
				}
			}
			$this->response($mSql,200);	// If no records "No Content" status
		}
		// floor


		# CHIEF COMPLAINT

		private function apiGetChiefComplaint(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_chiefcomp 
					WHERE ClinixRID = '$id'
					ORDER BY ChiefRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetEthiology(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_ethiology WHERE ClinixRID = '$id'
					ORDER BY EtiologyRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPastTreatment(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_pasttreatment WHERE ClinixRID = '$id'
					ORDER BY TreatmentRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetPrevSurgeries(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_prevsurgeries WHERE ClinixRID = '$id'
					ORDER BY PrevSurgRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetLabs(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_Labs WHERE ClinixRID = '$id'
					ORDER BY LabsRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiGetLabs_WhatLabs(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$whatLabs = (string)$this->_request['whatLabs'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_Labs WHERE ClinixRID = '$id' AND labCategory = '$whatLabs'
					ORDER BY LabsRID;"; // in this case don't use LIMIT 1 so as to show all labs
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		// all MediHistory
		private function apiGetMedHist(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ioh_MedHist WHERE ClinixRID = '$id' 
					ORDER BY MedHistlRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		// Specific MediHistory
		private function apiGetMedHist_WhatMedHist(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$whatMedHist = (string)$this->_request['whatMedHist'];

			if ($id > 0) {	
				$query="SELECT * FROM zipad_ioh_MedHist WHERE ClinixRID = '$id' AND MedHist = '$whatMedHist'
					ORDER BY MedHistlRID DESC LIMIT 1;";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		# AMBULATORY STATUS
		# AMBULATORY STATUS
		# AMBULATORY STATUS
		private function apiGetAmbulatoryStatus(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_AmbuStatus WHERE ClinixRID = '$id'
					ORDER BY AmbuStatusRID DESC LIMIT 1;"; # just pick up the last, to be safe
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		#       H I P
		#         H I P
		#           H I P
		private function apiGetHipMeasures(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_HIPmeasures WHERE ClinixRID = '$id'
					ORDER BY HipMeasuresRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		// Specific Hip Measures
		private function apiGetHipMeasures_WhatHipMeasures(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$whatHipMeasures = (string)$this->_request['whatHipMeasures'];

			if ($id > 0) {	
				$query="SELECT * FROM zipad_pe_HIPmeasures WHERE ClinixRID = '$id' AND Supine = '$whatHipMeasures'
					ORDER BY HipMeasuresRID DESC LIMIT 1;";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		private function apiGetHipStanding(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_HIPstanding WHERE ClinixRID = '$id'
					ORDER BY HipStandingRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiGetHipStanding_WhatHipStanding(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$whatHipStanding = (string)$this->_request['whatHipStanding'];

			if($id > 0){	
				$query="SELECT * FROM zipad_pe_HIPstanding WHERE ClinixRID = '$id' AND Standing = '$whatHipStanding'
					ORDER BY HipStandingRID DESC LIMIT 1;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		private function apiGetHipRangeOfMotion(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_HIPmotionRange WHERE ClinixRID = '$id'
					ORDER BY HipMeasuresRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiGetHipRangeOfMotion_WhatHipRangeOfMotion(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$whatHipRangeOfMotion = (string)$this->_request['whatHipRangeOfMotion'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_HIPmotionRange WHERE ClinixRID = '$id' AND MotionArea = '$whatHipRangeOfMotion'
					ORDER BY HipMeasuresRID DESC LIMIT 1;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetHipXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_HipXRays WHERE ClinixRID = '$id'
					ORDER BY HipXRaysRID DESC LIMIT 1;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		// private function apiGetHipXRays_WhatHipXRays(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
		// 	$WhatHipXRays = (string)$this->_request['whatHipXRays'];

		// 	if($id > 0){	
		// 		$query="SELECT * FROM zipad_pe_HipXRays WHERE ClinixRID = '$id' AND XRayArea = '$WhatHipXRays'
		// 			ORDER BY HipXRaysRID DESC LIMIT 1;";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }


		# K N E E
		# K N E E
		# K N E E
		private function apiGetKNEEMeasures(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_KneeMeasures WHERE ClinixRID = '$id'
					ORDER BY KneeMeasuresRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetgetKNEEappearance(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_KneeAppearance WHERE ClinixRID = '$id'
					ORDER BY KneeAppearanceRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetgetKNEEalignment(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_KneeAlignment WHERE ClinixRID = '$id'
					ORDER BY KneeAlignmentRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetgetKNEEmotionrange(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_KneeMotionRange WHERE ClinixRID = '$id'
					ORDER BY KneeMotionRangeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetgetKNEExrays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_KneeXRays WHERE ClinixRID = '$id'
					ORDER BY KneeXRaysRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetTraumaLongBone(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_longbone_1 WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetTraumaLongBone2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_pe_longbone_2 WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		# DIAGNOSIS
		# DIAGNOSIS
		# DIAGNOSIS
		private function apiGetDiagnosis(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_Diagnosis WHERE ClinixRID = '$id'
					ORDER BY DiagnosisRID DESC LIMIT 1;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetDiagsMgmt(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_DiagsManagement WHERE ClinixRID = '$id'
					ORDER BY DiagsMgmtRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiGetDiagsMgmt_WhatDiagsManagement(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$whatDiagsManagement = (string)$this->_request['whatDiagsManagement'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_DiagsManagement WHERE ClinixRID = '$id' AND Management = '$whatDiagsManagement'
					ORDER BY DiagsMgmtRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}





		private function apiGetDiagsMedication(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_DiagsMedication WHERE ClinixRID = '$id'
					ORDER BY DiagsMedsRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetDiagsSchedForSurgery(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT zipad_diags_schedsurgery.* 
					, zipad_ophip_6.Diagnosis as PostOpHipDiagnosis
					, zipad_opknee_5.Diagnosis as PostOpKneeDiagnosis
					, zipad_trauma_op_surgicaltech.Diagnosis as PostOpTraumaDiagnosis
					, zh_recordOfOperation.postOpDiagnosis as PostOpRecordOfOperationDiagnosis
				FROM zipad_diags_schedsurgery 
				LEFT JOIN zipad_opknee_5 ON zipad_opknee_5.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				LEFT JOIN zipad_ophip_6 ON zipad_ophip_6.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				LEFT JOIN zipad_trauma_op_surgicaltech ON zipad_trauma_op_surgicaltech.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				LEFT JOIN zh_recordOfOperation ON zh_recordOfOperation.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				WHERE zipad_diags_schedsurgery.ClinixRID = '$id'
				ORDER BY DiagsSchedSurgRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		// private function apiGetDiagsSchedForSurgeryAll(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
			
		// 		$query="SELECT 
		// 					zipad_diags_schedsurgery.ClinixRID
		// 					, zipad_diags_schedsurgery.SurgeryDate
		// 					, zipad_diags_schedsurgery.SurgeryTime
		// 					, zipad_diags_schedsurgery.SurgeryTimeEnd
		// 					, zipad_diags_schedsurgery.Surgeon
		// 					, zipad_diags_schedsurgery.Assistant
		// 					, zipad_diags_schedsurgery.Cardio
		// 					, zipad_diags_schedsurgery.Anesthesio
		// 					, zipad_diags_schedsurgery.AnesthesiaType
		// 					, zipad_diags_schedsurgery.Hospital
		// 					, zipad_diags_schedsurgery.OrNurse
		// 					, zipad_diags_schedsurgery.SurgeryType
		// 					, px_data.PxRID
		// 					, px_data.FirstName
		// 					, px_data.MiddleName
		// 					, px_data.LastName
		// 					, px_data.Sex
		// 					, TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge
		// 					, px_data.PhilHealth
		// 					, px_data.DOB

		// 					, zipad_diagnosis.Diagnosis
		// 					, zipad_ophip_6.Diagnosis as PostOpHipDiagnosis
		// 					, zipad_opknee_5.Diagnosis as PostOpKneeDiagnosis
		// 					, zipad_trauma_op_surgicaltech.Diagnosis as PostOpTraumaDiagnosis
							
		// 				FROM zipad_diags_schedsurgery 
		// 				INNER JOIN clinix ON zipad_diags_schedsurgery.ClinixRID = clinix.ClinixRID
		// 				INNER JOIN px_data ON clinix.PxRID = px_data.pxRID
		// 				LEFT JOIN zipad_diagnosis ON zipad_diagnosis.ClinixRID = zipad_diags_schedsurgery.ClinixRID
		// 				LEFT JOIN zipad_opknee_5 ON zipad_opknee_5.ClinixRID = zipad_diags_schedsurgery.ClinixRID
		// 				LEFT JOIN zipad_ophip_6 ON zipad_ophip_6.ClinixRID = zipad_diags_schedsurgery.ClinixRID
		// 				LEFT JOIN zipad_trauma_op_surgicaltech ON zipad_trauma_op_surgicaltech.ClinixRID = zipad_diags_schedsurgery.ClinixRID
		// 				ORDER BY zipad_diags_schedsurgery.SurgeryDate DESC";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 	$this->response('',204);	// If no records "No Content" status
		// }


		private function apiGetDiagsSchedForSurgeryAll(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$fromDate = (string)$this->_request['fromDate'];
			$toDate = (string)$this->_request['toDate'];

			$query="SELECT 
					zipad_diags_schedsurgery.ClinixRID
					, zipad_diags_schedsurgery.SurgeryDate
					, zipad_diags_schedsurgery.SurgeryTime
					, zipad_diags_schedsurgery.SurgeryTimeEnd
					, zipad_diags_schedsurgery.Surgeon
					, zipad_diags_schedsurgery.Assistant
					, zipad_diags_schedsurgery.Cardio
					, zipad_diags_schedsurgery.Anesthesio
					, zipad_diags_schedsurgery.AnesthesiaType
					, zipad_diags_schedsurgery.Hospital
					, zipad_diags_schedsurgery.OrNurse
					, zipad_diags_schedsurgery.SurgeryType
					, px_data.PxRID
					, px_data.FirstName
					, px_data.MiddleName
					, px_data.LastName
					, px_data.Sex
					, TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge
					, px_data.PhilHealth
					, px_data.DOB

					, zipad_diagnosis.Diagnosis
					, zipad_ophip_6.Diagnosis as PostOpHipDiagnosis
					, zipad_opknee_5.Diagnosis as PostOpKneeDiagnosis
					, zipad_trauma_op_surgicaltech.Diagnosis as PostOpTraumaDiagnosis
					
				FROM zipad_diags_schedsurgery 
				INNER JOIN clinix ON zipad_diags_schedsurgery.ClinixRID = clinix.ClinixRID
				INNER JOIN px_data ON clinix.PxRID = px_data.pxRID
				LEFT JOIN zipad_diagnosis ON zipad_diagnosis.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				LEFT JOIN zipad_opknee_5 ON zipad_opknee_5.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				LEFT JOIN zipad_ophip_6 ON zipad_ophip_6.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				LEFT JOIN zipad_trauma_op_surgicaltech ON zipad_trauma_op_surgicaltech.ClinixRID = zipad_diags_schedsurgery.ClinixRID
				WHERE (zipad_diags_schedsurgery.SurgeryDate >= '$fromDate' AND zipad_diags_schedsurgery.SurgeryDate <= '$toDate') 
				ORDER BY zipad_diags_schedsurgery.SurgeryDate DESC";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status			
		}


		private function apiGetDiagsSchedForSurgeryPatient(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$id = (int)$this->_request['id'];
			
				$query="SELECT 
						zipad_diags_schedsurgery.ClinixRID
						, zipad_diags_schedsurgery.SurgeryDate
						, zipad_diags_schedsurgery.SurgeryTime
						, zipad_diags_schedsurgery.SurgeryTimeEnd
						, zipad_diags_schedsurgery.Surgeon
						, zipad_diags_schedsurgery.Assistant
						, zipad_diags_schedsurgery.Cardio
						, zipad_diags_schedsurgery.Anesthesio
						, zipad_diags_schedsurgery.AnesthesiaType
						, zipad_diags_schedsurgery.Hospital
						, zipad_diags_schedsurgery.OrNurse
						, zipad_diags_schedsurgery.SurgeryType
						, px_data.PxRID
						, px_data.FirstName
						, px_data.MiddleName
						, px_data.LastName
						, px_data.Sex
						, TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge
						, px_data.PhilHealth
						, px_data.DOB

						, zipad_diagnosis.Diagnosis
						, zipad_ophip_6.Diagnosis as PostOpHipDiagnosis
						, zipad_opknee_5.Diagnosis as PostOpKneeDiagnosis
						, zipad_trauma_op_surgicaltech.Diagnosis as PostOpTraumaDiagnosis
						
					FROM zipad_diags_schedsurgery 
					INNER JOIN clinix ON zipad_diags_schedsurgery.ClinixRID = clinix.ClinixRID
					INNER JOIN px_data ON clinix.PxRID = px_data.pxRID
					LEFT JOIN zipad_diagnosis ON zipad_diagnosis.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					LEFT JOIN zipad_opknee_5 ON zipad_opknee_5.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					LEFT JOIN zipad_ophip_6 ON zipad_ophip_6.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					LEFT JOIN zipad_trauma_op_surgicaltech ON zipad_trauma_op_surgicaltech.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					WHERE zipad_diags_schedsurgery.ClinixRID = '".$id."'
					ORDER BY zipad_diags_schedsurgery.SurgeryDate DESC";
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

		private function apiGetDiagsDisposition(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_DiagsDisposition WHERE ClinixRID = '$id'
					ORDER BY DiagsDispoRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetDiagsNotes(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_DiagsNotes WHERE ClinixRID = '$id'";

				// $wfp = fopen("zzz.ttt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		# CHIEF COMPLAINT

		private function apiUpdateNarr_ChiefCompALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$queryZ = "";
			for ($i = 0; $i < count ( $uniqClnx ) ; $i ++) {
				$uniqIn = $uniqClnx[$i];
				$queryZ = "UPDATE zipad_ioh_chiefcomp SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$uniqIn');";
				$r = $this->mysqli->query($queryZ) or die($queryZ."<br>".$this->mysqli->error.__LINE__);
			}

			// $success = array('status' => "Success", "msg" => "NARRATIVE LINES added Successfully.", "data" => $checkedLines);
			$this->response($queryZ , 200);
		}
		private function apiUpdateNarr_ChiefComp() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xChiefRID = $checkedLines[$i]['ChiefRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_chiefcomp SET
					ShowInNarrative = 1
					WHERE (ChiefRID = '$xChiefRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES added Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# ETHIOLOGY

		private function apiUpdateNarr_EthiologyALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_ethiology SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($success , 200);
		}
		private function apiUpdateNarr_Ethiology() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xEtiologyRID = $checkedLines[$i]['EtiologyRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_ethiology SET
					ShowInNarrative = 1
					WHERE (EtiologyRID = '$xEtiologyRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}

		# PAST TREATMENTS

		private function apiUpdateNarr_PastTreatsALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			// $ekek = file_get_contents("php://input");
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}
			
			$x = "";
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];  ???
				
				$query = "UPDATE zipad_ioh_pasttreatment SET
					ShowInNarrative = 0
					WHERE (ClinixRID = $xClinixRID);";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);
				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_PastTreats() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xTreatmentRID = $checkedLines[$i]['TreatmentRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_pasttreatment SET
					ShowInNarrative = 1
					WHERE (TreatmentRID = '$xTreatmentRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# PREVIOUS SURGERIES

		private function apiUpdateNarr_PrevSurgALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_prevsurgeries SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_PrevSurg() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xPrevSurgRID = $checkedLines[$i]['PrevSurgRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_prevsurgeries SET
					ShowInNarrative = 1
					WHERE (PrevSurgRID = '$xPrevSurgRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# PREVIOUS LABS

		private function apiUpdateNarr_LABSALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_Labs SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_LABS() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xLabsRID = $checkedLines[$i]['LabsRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_Labs SET
					ShowInNarrative = 1
					WHERE (LabsRID = '$xLabsRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# IOHPE Medical History

		private function apiUpdateNarr_MedHistALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_MedHist SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_MedHist() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xMedHistlRID = $checkedLines[$i]['MedHistlRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_ioh_MedHist SET
					ShowInNarrative = 1
					WHERE (MedHistlRID = '$xMedHistlRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# AMBULATORY STATUS - Narratives

		private function apiUpdateNarr_AMBUSttsALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_AmbuStatus SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_AMBUStts() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xAmbuStatusRID = $checkedLines[$i]['AmbuStatusRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_AmbuStatus SET
					ShowInNarrative = 1
					WHERE (AmbuStatusRID = '$xAmbuStatusRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# PE HIP Measurements - Narratives

		private function apiUpdateNarr_HIPMeasuresALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HIPmeasures SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_HIPMeasures() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{                                         
				$xHipMeasuresRID = $checkedLines[$i]['HipMeasuresRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HIPmeasures SET
					ShowInNarrative = 1
					WHERE (HipMeasuresRID = '$xHipMeasuresRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# PE HIP Standing - Narratives

		private function apiUpdateNarr_HIPstandALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HIPstanding SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_HIPstand() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xHipStandingRID = $checkedLines[$i]['HipStandingRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HIPstanding SET
					ShowInNarrative = 1
					WHERE (HipStandingRID = '$xHipStandingRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# PE HIP Range of Motion

		private function apiUpdateNarr_HIPromALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HIPmotionRange SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_HIProm() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xHipMeasuresRID = $checkedLines[$i]['HipMeasuresRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HIPmotionRange SET
					ShowInNarrative = 1
					WHERE (HipMeasuresRID = '$xHipMeasuresRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# PE HIP X-Ray

		private function apiUpdateNarr_HIPxrayALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HipXRays SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_HIPxray() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xHipXRaysRID = $checkedLines[$i]['HipXRaysRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_HipXRays SET
					ShowInNarrative = 1
					WHERE (HipXRaysRID = '$xHipXRaysRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# KNEE Measurements - Narratives

		private function apiUpdateNarr_KNEEMeasuresALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeMeasures SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_KNEEMeasures() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{                                         
				$xKneeMeasuresRID = $checkedLines[$i]['KneeMeasuresRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeMeasures SET
					ShowInNarrative = 1
					WHERE (KneeMeasuresRID = '$xKneeMeasuresRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# KNEE APPEARANCE - Narratives

		private function apiUpdateNarr_KNEEappearanceALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeAppearance SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_KNEEappearance() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{                                         
				$xKneeAppearanceRID = $checkedLines[$i]['KneeAppearanceRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeAppearance SET
					ShowInNarrative = 1
					WHERE (KneeAppearanceRID = '$xKneeAppearanceRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# KNEE ALIGNMENT - Narratives

		private function apiUpdateNarr_KNEEalignmentALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeAlignment SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_KNEEalignment() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{                                         
				$xKneeAlignmentRID = $checkedLines[$i]['KneeAlignmentRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeAlignment SET
					ShowInNarrative = 1
					WHERE (KneeAlignmentRID = '$xKneeAlignmentRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# KNEE RANGE of MOTION - Narratives

		private function apiUpdateNarr_KNEEmotionRangeALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeMotionRange SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_KNEEmotionRange() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{                                         
				$xKneeMotionRangeRID = $checkedLines[$i]['KneeMotionRangeRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeMotionRange SET
					ShowInNarrative = 1
					WHERE (KneeMotionRangeRID = '$xKneeMotionRangeRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# KNEE X R A Y s - Narratives

		private function apiUpdateNarr_KNEExrayALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeXRays SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_KNEExray() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{                                         
				$xKneeXRaysRID = $checkedLines[$i]['KneeXRaysRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_pe_KneeXRays SET
					ShowInNarrative = 1
					WHERE (KneeXRaysRID = '$xKneeXRaysRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}









		# DIAGNOSIS Narratives
		# DIAGNOSIS Narratives

		private function apiUpdateNarr_DiagnosisALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_Diagnosis SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_Diagnosis() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xDiagnosisRID = $checkedLines[$i]['DiagnosisRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_Diagnosis SET
					ShowInNarrative = 1
					WHERE (DiagnosisRID = '$xDiagnosisRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# DIAGNOSIS MANAGEMENT

		private function apiUpdateNarr_DiagsMGMTALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsManagement SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_DiagsMGMT() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xDiagsMgmtRID = $checkedLines[$i]['DiagsMgmtRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsManagement SET
					ShowInNarrative = 1
					WHERE (DiagsMgmtRID = '$xDiagsMgmtRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# DIAGNOSIS MEDICATION
		
		private function apiUpdateNarr_DiagsMEDSALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsMedication SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_DiagsMEDS() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xDiagsMedsRID = $checkedLines[$i]['DiagsMedsRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsMedication SET
					ShowInNarrative = 1
					WHERE (DiagsMedsRID = '$xDiagsMedsRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# DIAGNOSIS SCHEDULE for SURGERY
		
		private function apiUpdateNarr_DiagsSchedSurgALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_diags_schedsurgery SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_DiagsSchedSurg() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xDiagsSchedSurgRID = $checkedLines[$i]['DiagsSchedSurgRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_diags_schedsurgery SET
					ShowInNarrative = 1
					WHERE (DiagsSchedSurgRID = '$xDiagsSchedSurgRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# DIAGNOSIS DISPOSITION
		
		private function apiUpdateNarr_DiagsDISPOALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsDisposition SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_DiagsDISPO() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xDiagsDispoRID = $checkedLines[$i]['DiagsDispoRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsDisposition SET
					ShowInNarrative = 1
					WHERE (DiagsDispoRID = '$xDiagsDispoRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}


		# DIAGNOSIS n o t e s
		
		private function apiUpdateNarr_DiagsNOTESALLOFF() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);

			# Get Each Unique ClinixRID
	        $uniqClnx = array ();
			for ($i = 0; $i < count ( $checkedLines ) ; $i ++) {
				$isIn = $checkedLines[$i]['ClinixRID'];
				if (!in_array($isIn, $uniqClnx)) $uniqClnx[] = $isIn ;
			}

			$x = "";	
			for ($i=0; $i<count($uniqClnx); $i++)
			{
				$xClinixRID  = $uniqClnx[$i]; //['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsNotes SET
					ShowInNarrative = 0
					WHERE (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}
		private function apiUpdateNarr_DiagsNOTES() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$checkedLines = json_decode(file_get_contents("php://input"),true);


			$x = "";	
			for ($i=0; $i<count($checkedLines); $i++)
			{
				$xDiagsNotesRID  = $checkedLines[$i]['DiagsNotesRID'];
				$xClinixRID  = $checkedLines[$i]['ClinixRID'];
				
				$query = "UPDATE zipad_DiagsNotes SET
					ShowInNarrative = 1
					WHERE (DiagsNotesRID = '$xDiagsNotesRID') AND (ClinixRID = '$xClinixRID');";
				$r = $this->mysqli->query($query) or die($query."<br>".$this->mysqli->error.__LINE__);

				$x .= $query ;
			}
			$success = array('status' => "Success", "msg" => "NARRATIVE LINES RESET Successfully.", "data" => $checkedLines);
			$this->response($x , 200);
		}





		//General Ortho - 1
		private function apiGENORTHOappearance(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_genortho_1 WHERE ClinixRID = '$id'
					ORDER BY GenOrthoRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function apigetGENORTHOXrayOrder(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_genortho_2 WHERE ClinixRID = '$id'
					ORDER BY GenOrthoRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apigetGENORTHOXrayFinding(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_genortho_3 WHERE ClinixRID = '$id'
					ORDER BY GenOrthoRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiGENORTHODiagnosis(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_genortho_4 WHERE ClinixRID = '$id'
					ORDER BY GenOrthoRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGENORTHOLaboratoryExamination(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_genortho_5 WHERE ClinixRID = '$id'
					ORDER BY GenOrthoRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiGENORTHOPreviousSurgery(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_genortho_6 WHERE ClinixRID = '$id'
					ORDER BY GenOrthoRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		//General Ortho - 1 - last line


		//FOOT ANKLE - 1
		private function apiFootAnkleHistoryOfPresentIllness(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_1 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnklePastMedicalHistory(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_2 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnklextremities(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_3 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnklextremities2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_4 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnkleRangeofMotion(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_5 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnkleVascular(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_6 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnkleXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_7 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnkleMRI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_8 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID	;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiFootAnkleAsManDis(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsfootankle_9 WHERE ClinixRID = '$id'
					ORDER BY FootAnkleRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		//FOOT ANKLE - 1 - Last line


		//Skeletal Trauma - 1
		private function apiSKELTRAUMAmbulatoryStatus(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_skeltrauma_1 WHERE ClinixRID = '$id'
					ORDER BY SkelTraumaRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSKELTRAUMAXrayOrdered(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_skeltrauma_2 WHERE ClinixRID = '$id'
					ORDER BY SkelTraumaRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

	//Skeletal Trauma - 1 - Last line



		private function apiSPORTSKNEESystemic(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_1 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSKNEExtremities(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_2 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSKNEExtremities2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_3 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSKNEErangemotion(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_4 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSKNEEvascular(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_5 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSKNEESpecialTest(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_6 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSKNEEgrosspic(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_7 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSKNEEassmandispo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sportsknee_8 WHERE ClinixRID = '$id'
					ORDER BY KneeRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		
		private function apiSPORTSHOULDERHPI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_1 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		private function apiSPORTSHOULDERPMH(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_2 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSHOULDERExtrimities(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_3 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function apiSPORTSHOULDERExtrimities2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_4 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSHOULDERRangeofMotion(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_5 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSHOULDERVascular(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_6 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSHOULDERspecialtest(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_7 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSHOULDERGrossPictures(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_8 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPORTSHOULDERAssDisMan(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_sholder_9 WHERE ClinixRID = '$id'
					ORDER BY SholderRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiSPINEEvalForm(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_spine_1 WHERE ClinixRID = '$id'
					ORDER BY SpineRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPINEOnsetofspine(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_spine_2 WHERE ClinixRID = '$id'
					ORDER BY SpineRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPINEactivities(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_spine_3 WHERE ClinixRID = '$id'
					ORDER BY SpineRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSPINECheckAll(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_spine_4 WHERE ClinixRID = '$id'
					ORDER BY SpineRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiSaveDsigPIN(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}


			$PxRID = (int)$this->_request['PxRID'];
			$pin = (string)$this->_request['PIN'];

			// SEARCH PIN
			$queryf="SELECT PIN FROM px_dsig WHERE PIN = '$pin' ;";


			$r = $this->mysqli->query($queryf) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				
				$error = array('status' => "Failed", "msg" => "CANNOT SAVE PIN, ALREADY TAKEN!");
				$this->response($this->json($error, JSON_NUMERIC_CHECK), 200); // send user details
				
			}else{


				$query = "UPDATE px_dsig SET 
					PIN = '$pin'
				WHERE PxRID = $PxRID";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			}

		}
		



		//Narrative Report Signature

		private function apiGetNarMedRep(){	

			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$clinixrid = (int)$this->_request['clinixrid'];
			

			if($clinixrid > 0){	
				$query="SELECT DoctorPIN FROM zipad_ClosePE_dsig 
					WHERE zipad_ClosePE_dsig.ClinixRID = '$clinixrid' LIMIT 1";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$zipaddsig = array();
					while($row = $r->fetch_assoc()) {
						$zipaddsig[] = $row;
						$DoctorPIN = $zipaddsig[0]['DoctorPIN'];
					}

					$qryDsig="SELECT 
						a.b64a
						, CONCAT(b.LastName,', ',b.FirstName) AS NameDoctor
						, a.PIN
					FROM px_dsig AS a
					INNER JOIN px_data AS b ON a.PxRID = b.PxRID
					WHERE a.PIN = '$DoctorPIN' LIMIT 1";

					$rDsig = $this->mysqli->query($qryDsig) or die($this->mysqli->error.__LINE__);
					if($rDsig->num_rows > 0) {

						$result = array();
						while($row = $rDsig->fetch_assoc()) {
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}else
						$this->response('',204);	// If no records "No Content" status
				}
				else
					$this->response('',204);	// If no records "No Content" status
			}
			$this->response('',204);	// If no records "No Content" status
		}
		

		private function apiGetNarMedRepNurse(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$clinixrid = (int)$this->_request['clinixrid'];
			
				$wfp = fopen("zzz.txt", "w");
				fwrite($wfp, $clinixrid);
				fclose($wfp);

			if($clinixrid > 0){	
				$query="SELECT NursePIN FROM zipad_ClosePE_dsig 
					WHERE zipad_ClosePE_dsig.ClinixRID = '$clinixrid' LIMIT 1";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$zipaddsig = array();
					while($row = $r->fetch_assoc()) {
						$zipaddsig[] = $row;
						$NursePIN = $zipaddsig[0]['NursePIN'];
					}

					$qryDsig="SELECT 
						a.b64a
						, CONCAT(b.LastName,', ',b.FirstName) AS NameNurse
						, a.PIN
					FROM px_dsig AS a
					INNER JOIN px_data AS b ON a.PxRID = b.PxRID
					WHERE a.PIN = '$NursePIN' LIMIT 1";

					$rDsig = $this->mysqli->query($qryDsig) or die($this->mysqli->error.__LINE__);
					if($rDsig->num_rows > 0) {

						$result = array();
						while($row = $rDsig->fetch_assoc()) {
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}else
						$this->response('',204);	// If no records "No Content" status
				}
				else
					$this->response('',204);	// If no records "No Content" status
			}
			$this->response('',204);	// If no records "No Content" status
		}
		//Narrative Report - End

	//ANESHTESIA

		private function apiSafeSurg(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$surgDataItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$surgDataItem['ClinixRID'];
			$PxRID  = (int)$surgDataItem['PxRID'];
			$PatientIdentity = (string)$surgDataItem['PatientIdentity'];
			$Antimicrobial = (string)$surgDataItem['Antimicrobial'];
			$ProcedureConcent = (string)$surgDataItem['ProcedureConcent'];
			$Oximeter = (string)$surgDataItem['Oximeter'];
			$Marketing = (string)$surgDataItem['Marketing'];
			$Allergies = (string)$surgDataItem['Allergies'];
			$Intervention = (string)$surgDataItem['Intervention'];
			$BloodAvail = (string)$surgDataItem['BloodAvail'];
			$PatientReconfirm = (string)$surgDataItem['PatientReconfirm'];
			$ImageFilms = (string)$surgDataItem['ImageFilms'];
			$MemberIntroduction = (string)$surgDataItem['MemberIntroduction'];
			$AnticipatedEvents = (string)$surgDataItem['AnticipatedEvents'];
			$ProcedureRecord = (string)$surgDataItem['ProcedureRecord'];
			$InstrumentUsed = (string)$surgDataItem['InstrumentUsed'];
			$SpecimenLabel = (string)$surgDataItem['SpecimenLabel'];
			$ConcernAddress = (string)$surgDataItem['ConcernAddress'];
			$AnestNotes = (string)$surgDataItem['AnestNotes'];
			
			$querycheck = "SELECT * FROM zipad_anesthesiasurge 
				WHERE ClinixRID = $ClinixRID";

			$r2 = $this->mysqli->query($querycheck) or die($this->mysqli->error.__LINE__);

			if(mysqli_num_rows($r2) == 1)
			{
				$query = "UPDATE zipad_anesthesiasurge SET 
				PatientIdentity = '$PatientIdentity', Antimicrobial = '$Antimicrobial',
				ProcedureConcent = '$ProcedureConcent', Oximeter = '$Oximeter',
				Marketing = '$Marketing', Allergies = '$Allergies',
				Intervention = '$Intervention', BloodAvail = '$BloodAvail',
				PatientReconfirm = '$PatientReconfirm', ImageFilms = '$ImageFilms',
				MemberIntroduction = '$MemberIntroduction', AnticipatedEvents = '$AnticipatedEvents',
				AnticipatedEvents = '$AnticipatedEvents', ProcedureRecord = '$ProcedureRecord',
				InstrumentUsed = '$InstrumentUsed', SpecimenLabel = '$SpecimenLabel',
				ConcernAddress = '$ConcernAddress', AnestNotes = '$AnestNotes' 
				WHERE ClinixRID = $ClinixRID";
			}else
			{
				$query = "INSERT INTO zipad_anesthesiasurge (ClinixRID
				, PxRID, PatientIdentity , Antimicrobial, ProcedureConcent
			 	, Oximeter, Marketing, Allergies, Intervention
			 	, BloodAvail, PatientReconfirm, ImageFilms
			 	, MemberIntroduction, AnticipatedEvents
			 	, ProcedureRecord, InstrumentUsed, SpecimenLabel
			 	, ConcernAddress, AnestNotes) 
				VALUES (".$ClinixRID.",".$PxRID.",'".$PatientIdentity."',
						'".$Antimicrobial."','".$ProcedureConcent."', 
						'".$Oximeter."','".$Marketing."','".$Allergies."',
						'".$Intervention."','".$BloodAvail."','".$PatientReconfirm."',
						'".$ImageFilms."','".$MemberIntroduction."','".$AnticipatedEvents."',
			 			'".$ProcedureRecord."','".$InstrumentUsed."',
			 			'".$SpecimenLabel."','".$ConcernAddress."','".$AnestNotes."')";
			}
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiIntraAnest(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$PxRID  = (int)$IntraItem['PxRID'];
			$timeIntra = (string)$IntraItem['timeIntra'];
             $BP = (string)$IntraItem['BP'];
             $PR = (string)$IntraItem['PR'];
             $RR = (string)$IntraItem['RR'];
             $TEMP = (string)$IntraItem['TEMP'];
             $SaO2 = (string)$IntraItem['SaO2'];
             $REMARKS = (string)$IntraItem['REMARKS'];
			

				$query = "INSERT INTO zipad_anestheIntra (ClinixRID
				, PxRID, TimeIntra, BP,PR,RR,TEMP,SaO2,REMARKS) 
				VALUES (".$ClinixRID.",".$PxRID.",'$timeIntra','$BP','$PR',
					'$RR','$TEMP','$SaO2','$REMARKS')";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiIntraNotes(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid  = (int)$IntraItem['pxrid'];
			$IntraAnestDate  = (int)$IntraItem['IntraAnestDate'];
			$IntraAnestNotes  = (string)$IntraItem['IntraAnestNotes'];

				$query = "UPDATE zipad_anestheIntraNotes SET
				PxRID = $pxrid, 
				IntraAnestNotes = '$IntraAnestNotes', 
				IntraAnestDate = '$IntraAnestDate' 
				WHERE ClinixRID = $clinix";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiIntraNotesShow(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			$query="SELECT * FROM zipad_anestheIntraNotes WHERE ClinixRID = '$ClinixRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}else{
		
				$query1="INSERT INTO zipad_anestheIntraNotes SET ClinixRID = '$ClinixRID'";
				$r1 = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);

				$query="SELECT * FROM zipad_anestheIntraNotes WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apisafetySurgShow(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anesthesiasurge WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details

					
				}else{
		
					$query1="INSERT INTO zipad_anesthesiasurge SET ClinixRID = '$ClinixRID'";
					$r1 = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);
	
					$query="SELECT * FROM zipad_anesthesiasurge WHERE ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiIntraMonitor(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anestheIntra WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiDeleteIntraAnest(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$IntraAnestID = (int)$this->_request['ID'];

		
			if($IntraAnestID > 0){	
				$query="DELETE FROM zipad_anestheIntra WHERE AnesthesiaRID = '$IntraAnestID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCareUnitAnest1(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anesthesiacareunitA WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
				}else
				{

					$query="INSERT INTO zipad_anesthesiacareunitA SET ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_anesthesiacareunitA WHERE ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
					}
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpAnestCareUnit1(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$PxRID = (int)$IntraItem['PxRID'];
			$Operation= (string)$IntraItem['Operation'];
	        $OperationDate= (string)$IntraItem['OperationDate'];
	        $TimeArrivalIN= (string)$IntraItem['TimeArrivalIN'];
	        $TimeArrivalOUT= (string)$IntraItem['TimeArrivalOUT'];
	        $Anesthesia= (string)$IntraItem['Anesthesia'];
	        $AnesthesiaOthers= (string)$IntraItem['AnesthesiaOthers'];
	        $AnesthesiaMSo4= (string)$IntraItem['AnesthesiaMSo4'];
	        $TimeIN= (string)$IntraItem['TimeIN'];
	        $TimeOut= (string)$IntraItem['TimeOut'];

				$query = "UPDATE zipad_anesthesiacareunitA SET
				PxRID = ".$PxRID.",
				Operation = '".$Operation."',
	            OperationDate = '".$OperationDate."',
	            TimeArrivalIN = '".$TimeArrivalIN."',
	            TimeArrivalOUT = '".$TimeArrivalOUT."',
	            Anesthesia = '".$Anesthesia."',
	            AnesthesiaOthers = '".$AnesthesiaOthers."',
	            AnesthesiaMSo4 = '".$AnesthesiaMSo4."',
	            TimeIN = '".$TimeIN."',
	            TimeOut = '".$TimeOut."'
				WHERE ClinixRID = ".$ClinixRID." ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		
		private function apiDelAnestCareUnit1(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$wrid = (int)$this->_request['wrid'];

		
			if($wrid > 0){	
				$query="DELETE FROM zipad_anesthesiacareunitA WHERE wrid = '$wrid'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCareUnitAnest2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anesthesiacareunitB WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpAnestCareUnit2(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$PxRID = (int)$IntraItem['PxRID'];
			$TimeCareUnit = (string)$IntraItem['TimeCareUnit'];
	        $BP = (string)$IntraItem['BP'];
	        $PR = (string)$IntraItem['PR'];
	        $Temp = (string)$IntraItem['Temp'];
	        $Sa02 = (string)$IntraItem['Sa02'];

				$query = "INSERT INTO zipad_anesthesiacareunitB SET
				PxRID = ".$PxRID.",
				TimeCareUnit = '".$TimeCareUnit."',
		        BP = '".$BP."',
		        PR = '".$PR."',
		        Temp = '".$Temp."',
		        Sa02  = '".$Sa02."',
		        ClinixRID = ".$ClinixRID."";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiDelAnestCareUnit2(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$wrid = (int)$this->_request['wrid'];

			if($wrid > 0){	
				$query="DELETE FROM zipad_anesthesiacareunitB WHERE wrid = '$wrid'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCareUnitAnest3(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anesthesiacareunitC WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpAnestCareUnit3(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$PxRID = (int)$IntraItem['PxRID'];
			$Medication = (string)$IntraItem['Medication'];
	        $TimeInitial = (string)$IntraItem['TimeInitial'];

				$query = "INSERT INTO zipad_anesthesiacareunitC SET
				PxRID = ".$PxRID.",
				Medication = '".$Medication."',
				TimeInitial = '".$TimeInitial."',
		        ClinixRID = ".$ClinixRID."";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiDelAnestCareUnit3(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$wrid = (int)$this->_request['wrid'];

		
			if($wrid > 0){	
				$query="DELETE FROM zipad_anesthesiacareunitC WHERE wrid = '$wrid'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCareUnitAnest4(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anesthesiacareunitD WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
				}
				else
				{
					$query="INSERT INTO zipad_anesthesiacareunitD SET ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_anesthesiacareunitD WHERE ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
					}
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpAnestCareUnit4(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$PxRID = (int)$IntraItem['PxRID'];
			$Oxygen = (string)$IntraItem['Oxygen'];
	        $Minutes = (string)$IntraItem['Minutes'];
	        $NasalMask = (string)$IntraItem['NasalMask'];
	        $NasalMaskOther = (string)$IntraItem['NasalMaskOther'];
	        $Position = (string)$IntraItem['Position'];
	        $PositionOther = (string)$IntraItem['PositionOther'];
	        $SafetyDevice = (string)$IntraItem['SafetyDevice'];
	        $SafetyDeviceOthers = (string)$IntraItem['SafetyDeviceOthers'];
	        $RestrainNeeds = (string)$IntraItem['RestrainNeeds'];
	        $AreaValue = (string)$IntraItem['AreaValue'];

				$query = "UPDATE zipad_anesthesiacareunitD SET
				PxRID = ".$PxRID.",
				Oxygen = '".$Oxygen."',
		        Minutes = '".$Minutes."',
		        NasalMask = '".$NasalMask."',
		        NasalMaskOther = '".$NasalMaskOther."',
		        Position = '".$Position."',
		        PositionOther = '".$PositionOther."',
		        SafetyDevice = '".$SafetyDevice."',
		        SafetyDeviceOthers = '".$SafetyDeviceOthers."',
		        RestrainNeeds = '".$RestrainNeeds."',
		        AreaValue = '".$AreaValue."'
		        WHERE ClinixRID = ".$ClinixRID."";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiDelAnestCareUnit4(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$wrid = (int)$this->_request['wrid'];

		
			if($wrid > 0){	
				$query="DELETE FROM zipad_anesthesiacareunitD WHERE wrid = '$wrid'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiCareUnitAnest5(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$ClinixRID = (int)$this->_request['ClinixRID'];
		

			if($ClinixRID > 0){	
				$query="SELECT * FROM zipad_anesthesiacareunitE WHERE ClinixRID = '$ClinixRID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
				}
				else
				{
					$query="INSERT INTO zipad_anesthesiacareunitE SET ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_anesthesiacareunitE WHERE ClinixRID = '$ClinixRID'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
					}
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpAnestCareUnit5(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$PxRID = (int)$IntraItem['PxRID'];
			$ActIn = (string)$IntraItem['ActIn'];
             $Act1hr = (string)$IntraItem['Act1hr'];
             $ActOut = (string)$IntraItem['ActOut'];
             $LarmSolu = (string)$IntraItem['LarmSolu'];
             $LarmVol = (string)$IntraItem['LarmVol'];
             $LarmInfu = (string)$IntraItem['LarmInfu'];
             $LarmLeft = (string)$IntraItem['LarmLeft'];
             $RarmSolu = (string)$IntraItem['RarmSolu'];
             $RarmVol = (string)$IntraItem['RarmVol'];
             $RarmInfu = (string)$IntraItem['RarmInfu'];
             $RarmLeft = (string)$IntraItem['RarmLeft'];
             $StartSolu = (string)$IntraItem['StartSolu'];
             $StartVol = (string)$IntraItem['StartVol'];
             $StartInfu = (string)$IntraItem['StartInfu'];
             $StartLeft = (string)$IntraItem['StartLeft'];
             $InRES = (string)$IntraItem['InRES'];
             $HrRES = (string)$IntraItem['HrRES'];
             $OutRES = (string)$IntraItem['OutRES'];
             $BLOODSolu = (string)$IntraItem['BLOODSolu'];
             $BLOODVol = (string)$IntraItem['BLOODVol'];
             $BLOODInfu = (string)$IntraItem['BLOODInfu'];
             $BLOODLeft = (string)$IntraItem['BLOODLeft'];
             $BLOODStartSolu = (string)$IntraItem['BLOODStartSolu'];
             $BLOODStartVol = (string)$IntraItem['BLOODStartVol'];
             $BLOODStartInfu = (string)$IntraItem['BLOODStartInfu'];
             $BLOODStartLeft = (string)$IntraItem['BLOODStartLeft'];
             $InCIR = (string)$IntraItem['InCIR'];
             $HrCIR = (string)$IntraItem['HrCIR'];
             $OutCIR = (string)$IntraItem['OutCIR'];
             $FrORSolu = (string)$IntraItem['FrORSolu'];
             $FrORVol = (string)$IntraItem['FrORVol'];
             $FrORInfu = (string)$IntraItem['FrORInfu'];
             $FrORLeft = (string)$IntraItem['FrORLeft'];
             $FrORStartedSolu = (string)$IntraItem['FrORStartedSolu'];
             $FrORStartedVol = (string)$IntraItem['FrORStartedVol'];
             $FrORStartedInfu = (string)$IntraItem['FrORStartedInfu'];
             $FrORStartedLeft = (string)$IntraItem['FrORStartedLeft'];
             $LarmSolu = (string)$IntraItem['LarmSolu'];
             $LarmVol = (string)$IntraItem['LarmVol'];
             $LarmInfu = (string)$IntraItem['LarmInfu'];
             $LarmLeft = (string)$IntraItem['LarmLeft'];
             $InCON = (string)$IntraItem['InCON'];
             $HrCON = (string)$IntraItem['HrCON'];
             $OutCON = (string)$IntraItem['OutCON'];
             $Urine = (string)$IntraItem['Urine'];
             $NGT = (string)$IntraItem['NGT'];
             $TTUDE = (string)$IntraItem['TTUDE'];
             $Hemovac = (string)$IntraItem['Hemovac'];
             $Others = (string)$IntraItem['Others'];
             $OxyIn = (string)$IntraItem['OxyIn'];
             $OxyHr = (string)$IntraItem['OxyHr'];
             $OxyOut = (string)$IntraItem['OxyOut'];
             $Dressing = (string)$IntraItem['Dressing'];
             $AnestNotes = (string)$IntraItem['AnestNotes'];

				$query = "UPDATE zipad_anesthesiacareunitE SET
				PxRID = '".$PXRID."',
				ActIn = '".$ActIn."',
	            Act1hr = '".$Act1hr."',
	            ActOut = '".$ActOut."',
	            LarmSolu = '".$LarmSolu."',
	            LarmVol = '".$LarmVol."',
	            LarmInfu = '".$LarmInfu."',
	            LarmLeft = '".$LarmLeft."',
	            RarmSolu = '".$RarmSolu."',
	            RarmVol = '".$RarmVol."',
	            RarmInfu = '".$RarmInfu."',
	            RarmLeft = '".$RarmLeft."',
	            StartSolu = '".$StartSolu."',
	            StartVol = '".$StartVol."',
	            StartInfu = '".$StartInfu."',
	            StartLeft = '".$StartLeft."',
	            InRES = '".$InRES."',
	            HrRES = '".$HrRES."',
	            OutRES = '".$OutRES."',
	            BLOODSolu = '".$BLOODSolu."',
	            BLOODVol = '".$BLOODVol."',
	            BLOODInfu = '".$BLOODInfu."',
	            BLOODLeft = '".$BLOODLeft."',
	            BLOODStartSolu = '".$BLOODStartSolu."',
	            BLOODStartVol = '".$BLOODStartVol."',
	            BLOODStartInfu = '".$BLOODStartInfu."',
	            BLOODStartLeft = '".$BLOODStartLeft."',
	            InCIR = '".$InCIR."',
	            HrCIR = '".$HrCIR."',
	            OutCIR = '".$OutCIR."',
	            FrORSolu = '".$FrORSolu."',
	            FrORVol = '".$FrORVol."',
	            FrORInfu = '".$FrORInfu."',
	            FrORLeft = '".$FrORLeft."',
	            FrORStartedSolu = '".$FrORStartedSolu."',
	            FrORStartedVol = '".$FrORStartedVol."',
	            FrORStartedInfu = '".$FrORStartedInfu."',
	            FrORStartedLeft = '".$FrORStartedLeft."',
	            LarmSolu = '".$LarmSolu."',
	            LarmVol = '".$LarmVol."',
	            LarmInfu = '".$LarmInfu."',
	            LarmLeft = '".$LarmLeft."',
	            InCON = '".$InCON."',
	            HrCON = '".$HrCON."',
	            OutCON = '".$OutCON."',
	            Urine = '".$Urine."',
	            NGT = '".$NGT."',
	            TTUDE = '".$TTUDE."',
	            Hemovac = '".$Hemovac."',
	            Others = '".$Others."',
	            OxyIn = '".$OxyIn."',
	            OxyHr = '".$OxyHr."',
	            OxyOut = '".$OxyOut."',
	            Dressing = '".$Dressing."',
	            AnestNotes = '".$AnestNotes."'
				WHERE ClinixRID = ".$ClinixRID." ";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiDelAnestCareUnit5(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$wrid = (int)$this->_request['wrid'];

		
			if($wrid > 0){	
				$query="DELETE FROM zipad_anesthesiacareunitE WHERE wrid = '$wrid'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}


	//ANESHTESIA - END


	//Consent 
	// by delo 180215

	private function apiGetConsentSurgery(){
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}

		$ClinixRID = (int)$this->_request['ClinixRID'];


		$query = "SELECT zipad_consentforsurgery.*
		, ResponsiblePerson.b64a as ResponsiblePersonSign
		, witness1.b64a as witness1Sign
		, witness2.b64a as witness2Sign

		, CONCAT(px_data_ResponsiblePerson.FirstName , ' ' , px_data_ResponsiblePerson.LastName) as pxResponsiblePerson
		, CONCAT(px_data_witness1.FirstName , ' ' , px_data_witness1.LastName) as pxwitness1
		, CONCAT(px_data_witness2.FirstName , ' ' , px_data_witness2.LastName) as pxwitness2


		FROM zipad_consentforsurgery 
		LEFT JOIN px_dsig as ResponsiblePerson ON ResponsiblePerson.PxRID = zipad_consentforsurgery.ResponsiblePersonPxRID
		LEFT JOIN px_data as px_data_ResponsiblePerson ON px_data_ResponsiblePerson.PxRID = zipad_consentforsurgery.ResponsiblePersonPxRID

		LEFT JOIN px_dsig as witness1 ON witness1.PxRID = zipad_consentforsurgery.witness1PxRID
		LEFT JOIN px_data as px_data_witness1 ON px_data_witness1.PxRID = zipad_consentforsurgery.witness1PxRID

		LEFT JOIN px_dsig as witness2 ON witness2.PxRID = zipad_consentforsurgery.witness2PxRID
		LEFT JOIN px_data as px_data_witness2 ON px_data_witness2.PxRID = zipad_consentforsurgery.witness2PxRID

		WHERE ClinixRID = '$ClinixRID' ";
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			$result = array();
			while($row = $r->fetch_assoc()){
				$result = $row;
			}
			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		}else{
			$query = "INSERT INTO zipad_consentforsurgery SET
				ClinixRID = '$ClinixRID'
			";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}
		$this->response('',204);	// If no records "No Content" status
	}


	private function apiInsertConsentSurgery(){
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}

		$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

		$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
		$PxRID  = (int)$ConsentSurgItem['PxRID'];
		$nameOfSurgery = (string)$ConsentSurgItem['nameOfSurgery'];
		$relationToPatient = (string)$ConsentSurgItem['relationToPatient'];
		$dayOfSurgery= (string)$ConsentSurgItem['dayOfSurgery'];
		$dateOfSurgery= (string)$ConsentSurgItem['dateOfSurgery'];
		$locationOfSurgery= (string)$ConsentSurgItem['locationOfSurgery'];


		$query = "UPDATE zipad_consentforsurgery SET
			PxRID = '$PxRID'
			, nameOfSurgery = '$nameOfSurgery'
			, relationToPatient = '$relationToPatient'
			, dayOfSurgery = '$dayOfSurgery'
			, dateOfSurgery= '$dateOfSurgery'
			, locationOfSurgery= '$locationOfSurgery'

		WHERE ClinixRID = '$ClinixRID' ";

		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	}


	private function apiSignWitness1ConsentSurgery(){
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}

		$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

		$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
		$witness1PxRID= (string)$ConsentSurgItem['witness1PxRID'];


		$query = "UPDATE zipad_consentforsurgery SET
			witness1PxRID= '$witness1PxRID'

		WHERE ClinixRID = '$ClinixRID' ";

		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	}


	private function apiSignWitness2ConsentSurgery(){
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}

		$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

		$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
		$witness2PxRID= (string)$ConsentSurgItem['witness2PxRID'];


		$query = "UPDATE zipad_consentforsurgery SET
			witness2PxRID= '$witness2PxRID'

		WHERE ClinixRID = '$ClinixRID' ";

		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
	}

	private function apiSignresponsiblePersonConsentSurgery(){
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}

		$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

		$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
		$responsiblePersonPxRID= (string)$ConsentSurgItem['responsiblePersonPxRID'];


		$query = "UPDATE zipad_consentforsurgery SET
			ResponsiblePersonPxRID= '$responsiblePersonPxRID'

		WHERE ClinixRID = '$ClinixRID' ";

		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);	
	}




		private function apiGetConsentAdminAnesthesia(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];


			$query = "SELECT zh_consentforadminanesthesia.* 
			, ResponsiblePerson.b64a as ResponsiblePersonSign
			, witness1.b64a as witness1Sign
			, witness2.b64a as witness2Sign

			, CONCAT(px_data_ResponsiblePerson.FirstName , ' ' , px_data_ResponsiblePerson.LastName) as pxResponsiblePerson
			, CONCAT(px_data_witness1.FirstName , ' ' , px_data_witness1.LastName) as pxwitness1
			, CONCAT(px_data_witness2.FirstName , ' ' , px_data_witness2.LastName) as pxwitness2

			FROM zh_consentforadminanesthesia
			LEFT JOIN px_dsig as ResponsiblePerson ON ResponsiblePerson.PxRID = zh_consentforadminanesthesia.ResponsiblePersonPxRID
			LEFT JOIN px_data as px_data_ResponsiblePerson ON px_data_ResponsiblePerson.PxRID = zh_consentforadminanesthesia.ResponsiblePersonPxRID

			LEFT JOIN px_dsig as witness1 ON witness1.PxRID = zh_consentforadminanesthesia.witness1PxRID
			LEFT JOIN px_data as px_data_witness1 ON px_data_witness1.PxRID = zh_consentforadminanesthesia.witness1PxRID

			LEFT JOIN px_dsig as witness2 ON witness2.PxRID = zh_consentforadminanesthesia.witness2PxRID
			LEFT JOIN px_data as px_data_witness2 ON px_data_witness2.PxRID = zh_consentforadminanesthesia.witness2PxRID

			WHERE ClinixRID = '$ClinixRID' ";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}else{
				$query1 = "INSERT INTO zh_consentforadminanesthesia SET
					ClinixRID = '$ClinixRID'
				";
				$r1 = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);

				$query = "SELECT zh_consentforadminanesthesia.* 
				, ResponsiblePerson.b64a as ResponsiblePersonSign
				, witness1.b64a as witness1Sign
				, witness2.b64a as witness2Sign

				, CONCAT(px_data_ResponsiblePerson.FirstName , ' ' , px_data_ResponsiblePerson.LastName) as pxResponsiblePerson
				, CONCAT(px_data_witness1.FirstName , ' ' , px_data_witness1.LastName) as pxwitness1
				, CONCAT(px_data_witness2.FirstName , ' ' , px_data_witness2.LastName) as pxwitness2

				FROM zh_consentforadminanesthesia
				LEFT JOIN px_dsig as ResponsiblePerson ON ResponsiblePerson.PxRID = zh_consentforadminanesthesia.ResponsiblePersonPxRID
				LEFT JOIN px_data as px_data_ResponsiblePerson ON px_data_ResponsiblePerson.PxRID = zh_consentforadminanesthesia.ResponsiblePersonPxRID

				LEFT JOIN px_dsig as witness1 ON witness1.PxRID = zh_consentforadminanesthesia.witness1PxRID
				LEFT JOIN px_data as px_data_witness1 ON px_data_witness1.PxRID = zh_consentforadminanesthesia.witness1PxRID

				LEFT JOIN px_dsig as witness2 ON witness2.PxRID = zh_consentforadminanesthesia.witness2PxRID
				LEFT JOIN px_data as px_data_witness2 ON px_data_witness2.PxRID = zh_consentforadminanesthesia.witness2PxRID

				WHERE ClinixRID = '$ClinixRID' ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertConsentAdminAnesthesia(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentTreatItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentTreatItem['ClinixRID'];
			$PxRID  = (int)$ConsentTreatItem['PxRID'];
			$typeOfAnesthesia = (string)$ConsentTreatItem['typeOfAnesthesia'];
			$anesthesiologist = (string)$ConsentTreatItem['anesthesiologist'];
			$nameOfTreatment= (string)$ConsentTreatItem['nameOfTreatment'];
			$dayOfTreatment= (string)$ConsentTreatItem['dayOfTreatment'];
			$dateOfTreatment= (string)$ConsentTreatItem['dateOfTreatment'];


			$query = "UPDATE zh_consentforadminanesthesia SET
				PxRID = '$PxRID'
				, typeOfAnesthesia = '$typeOfAnesthesia'
				, anesthesiologist = '$anesthesiologist'
				, nameOfTreatment = '$nameOfTreatment'
				, dayOfTreatment= '$dayOfTreatment'
			    , dateOfTreatment= '$dateOfTreatment'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignWitness1ConsentAdminAnesthesia(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
			$witness1PxRID= (string)$ConsentSurgItem['witness1PxRID'];


			$query = "UPDATE zh_consentforadminanesthesia SET
				witness1PxRID= '$witness1PxRID'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignWitness2ConsentAdminAnesthesia(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
			$witness2PxRID= (string)$ConsentSurgItem['witness2PxRID'];


			$query = "UPDATE zh_consentforadminanesthesia SET
				witness2PxRID= '$witness2PxRID'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignresponsiblePersonConsentAdminAnesthesia(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
			$responsiblePersonPxRID= (string)$ConsentSurgItem['responsiblePersonPxRID'];


			$query = "UPDATE zh_consentforadminanesthesia SET
				responsiblePersonPxRID= '$responsiblePersonPxRID'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiGetConsentTreatment(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];


			$query = "SELECT zipad_consentfortreatment.* 
			, ResponsiblePerson.b64a as ResponsiblePersonSign
			, witness1.b64a as witness1Sign
			, witness2.b64a as witness2Sign

			, CONCAT(px_data_ResponsiblePerson.FirstName , ' ' , px_data_ResponsiblePerson.LastName) as pxResponsiblePerson
			, CONCAT(px_data_witness1.FirstName , ' ' , px_data_witness1.LastName) as pxwitness1
			, CONCAT(px_data_witness2.FirstName , ' ' , px_data_witness2.LastName) as pxwitness2

			FROM zipad_consentfortreatment
			LEFT JOIN px_dsig as ResponsiblePerson ON ResponsiblePerson.PxRID = zipad_consentfortreatment.ResponsiblePersonPxRID
			LEFT JOIN px_data as px_data_ResponsiblePerson ON px_data_ResponsiblePerson.PxRID = zipad_consentfortreatment.ResponsiblePersonPxRID

			LEFT JOIN px_dsig as witness1 ON witness1.PxRID = zipad_consentfortreatment.witness1PxRID
			LEFT JOIN px_data as px_data_witness1 ON px_data_witness1.PxRID = zipad_consentfortreatment.witness1PxRID

			LEFT JOIN px_dsig as witness2 ON witness2.PxRID = zipad_consentfortreatment.witness2PxRID
			LEFT JOIN px_data as px_data_witness2 ON px_data_witness2.PxRID = zipad_consentfortreatment.witness2PxRID

			WHERE ClinixRID = '$ClinixRID' ";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}else{
				$query = "INSERT INTO zipad_consentfortreatment SET
					ClinixRID = '$ClinixRID'
				";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertConsentTreatment(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentTreatItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentTreatItem['ClinixRID'];
			$PxRID  = (int)$ConsentTreatItem['PxRID'];
			$nameOfTreatment = (string)$ConsentTreatItem['nameOfTreatment'];
			$relationToPatient = (string)$ConsentTreatItem['relationToPatient'];
			$dayOfTreatment= (string)$ConsentTreatItem['dayOfTreatment'];
			$dateOfTreatment= (string)$ConsentTreatItem['dateOfTreatment'];
			$locationOfTreatment= (string)$ConsentTreatItem['locationOfTreatment'];


			$query = "UPDATE zipad_consentfortreatment SET
				PxRID = '$PxRID'
				, nameOfTreatment = '$nameOfTreatment'
				, relationToPatient = '$relationToPatient'
				, dayOfTreatment = '$dayOfTreatment'
				, dateOfTreatment= '$dateOfTreatment'
			    , locationOfTreatment= '$locationOfTreatment'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignWitness1ConsentTreatment(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
			$witness1PxRID= (string)$ConsentSurgItem['witness1PxRID'];


			$query = "UPDATE zipad_consentfortreatment SET
				witness1PxRID= '$witness1PxRID'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiSignWitness2ConsentTreatment(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
			$witness2PxRID= (string)$ConsentSurgItem['witness2PxRID'];


			$query = "UPDATE zipad_consentfortreatment SET
				witness2PxRID= '$witness2PxRID'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiSignresponsiblePersonConsentTreatment(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ConsentSurgItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$ConsentSurgItem['ClinixRID'];
			$responsiblePersonPxRID= (string)$ConsentSurgItem['responsiblePersonPxRID'];


			$query = "UPDATE zipad_consentfortreatment SET
				responsiblePersonPxRID= '$responsiblePersonPxRID'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

	//Consent End


	//Home Instruction
	// by delo 180215

		private function apiGetHomeInstruction(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			$PxRID = (int)$this->_request['PxRID'];
			

			$query = "SELECT zipad_homeinstruction.* 
			, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
			FROM zipad_homeinstruction 
			LEFT JOIN px_data ON  zipad_homeinstruction.PxRID = px_data.PxRID
			WHERE ClinixRID = '$ClinixRID' ";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}else{
				$query2 = "INSERT INTO zipad_homeinstruction SET
					ClinixRID = '$ClinixRID'
					,PxRID = '$PxRID'
				";
				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				$query = "SELECT zipad_homeinstruction.* 
				, CONCAT(px_data.FirstName,' ',SUBSTRING(px_data.MiddleName, 1, 1),'. ',px_data.LastName) as pxName
				FROM zipad_homeinstruction 
				LEFT JOIN px_data ON  zipad_homeinstruction.PxRID = px_data.PxRID
				WHERE ClinixRID = '$ClinixRID' ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetHomeInstructionMedication(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			

			$query = "SELECT * FROM zipad_homeinstruction_medication WHERE ClinixRID = '$ClinixRID' ";
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


		private function apiInsertHomeInstruction(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$HospInsItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$HospInsItem['ClinixRID'];
			$PxRID  = (int)$HospInsItem['PxRID'];
            $roomNo = (string)$HospInsItem['roomNo'];
            $dateOfDischarge = (string)$HospInsItem['dateOfDischarge'];
            $nursingUnit = (string)$HospInsItem['nursingUnit'];
            $followUpPhysician = (string)$HospInsItem['followUpPhysician'];
            $followUpLocation = (string)$HospInsItem['followUpLocation'];
            $followUpDateTime = (string)$HospInsItem['followUpDateTime'];
            $toOtherCenters = (string)$HospInsItem['toOtherCenters'];
            $followingDischargeInstruction = (string)$HospInsItem['followingDischargeInstruction'];
            $generalOrFullDiet = (string)$HospInsItem['generalOrFullDiet'];
            $softDiet = (string)$HospInsItem['softDiet'];
            $lowSaltDiet = (string)$HospInsItem['lowSaltDiet'];
            $lowFatDiet = (string)$HospInsItem['lowFatDiet'];
            $othersDiet = (string)$HospInsItem['othersDiet'];
            $detailsDiet = (string)$HospInsItem['detailsDiet'];
            $noRestrictions = (string)$HospInsItem['noRestrictions'];
            $withRestrictions = (string)$HospInsItem['withRestrictions'];
            $detailsExerciseOrActivity = (string)$HospInsItem['detailsExerciseOrActivity'];
            $specialHomeInstructions = (string)$HospInsItem['specialHomeInstructions'];
            $instructionGivenBy = (string)$HospInsItem['instructionGivenBy'];
            $instructionGivenByDate = (string)$HospInsItem['instructionGivenByDate'];
            $instructionReceivedBy = (string)$HospInsItem['instructionReceivedBy'];
            $instructionReceivedByDate = (string)$HospInsItem['instructionReceivedByDate'];
    
            $query = "UPDATE zipad_homeinstruction SET
            	PxRID = '$PxRID'
	            , roomNo = '$roomNo'
	            , dateOfDischarge = '$dateOfDischarge'
	            , nursingUnit = '$nursingUnit'
	            , followUpPhysician = '$followUpPhysician'
	            , followUpLocation = '$followUpLocation'
	            , followUpDate = '$followUpDateTime'
	            , toOtherCenters = '$toOtherCenters'
	            , followingDischargeInstruction = '$followingDischargeInstruction'
	            , generalOrFullDiet = '$generalOrFullDiet'
	            , softDiet = '$softDiet'
	            , lowSaltDiet = '$lowSaltDiet'
	            , lowFatDiet = '$lowFatDiet'
	            , othersDiet = '$othersDiet'
	            , detailsDiet = '$detailsDiet'
	            , noRestrictions = '$noRestrictions'
	            , withRestrictions = '$withRestrictions'
	            , detailsExerciseOrActivity = '$detailsExerciseOrActivity'
	            , specialHomeInstructions = '$specialHomeInstructions'
	            , instructionGivenBy = '$instructionGivenBy'
	            , instructionGivenByDate = '$instructionGivenByDate'
	            , instructionReceivedBy = '$instructionReceivedBy'
	            , instructionReceivedByDate = '$instructionReceivedByDate'

			WHERE ClinixRID = '$ClinixRID' ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			$this->response($this->json($r, JSON_NUMERIC_CHECK), 200); // send user 	
		}

		private function apiInserthomeInstructionMedication(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$HospInsMedItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$HospInsMedItem['ClinixRID'];
			$PxRID  = (int)$HospInsMedItem['PxRID'];
            $homeInstructionID = (int)$HospInsMedItem['homeInstructionID'];

            $medicineName = (string)$HospInsMedItem['medicineName'];
            $sixam = (string)$HospInsMedItem['sixam'];
            $eightam = (string)$HospInsMedItem['eightam'];
            $twelvenn = (string)$HospInsMedItem['twelvenn'];
            $fourpm = (string)$HospInsMedItem['fourpm'];
            $sixpm = (string)$HospInsMedItem['sixpm'];
            $eightpm = (string)$HospInsMedItem['eightpm'];
            $tenpm = (string)$HospInsMedItem['tenpm'];
    
            $query = "INSERT INTO zipad_homeinstruction_medication SET
	            ClinixRID = '$ClinixRID'
            	, PxRID = '$PxRID'
	            , homeInstructionID = '$homeInstructionID'
	            , medicineName = '$medicineName'
	            , sixam = '$sixam'
	            , eightam = '$eightam'
	            , twelvenn = '$twelvenn'
	            , fourpm = '$fourpm'
	            , sixpm = '$sixpm'
	            , eightpm = '$eightpm'
	            , tenpm = '$tenpm'  
			";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			$this->response($this->json($r, JSON_NUMERIC_CHECK), 200); // send user 	
		}


		private function apiDeletehomeInstructionMedication(){	
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$homeInstructionMedID = (int)$this->_request['homeInstructionMedID'];

		
			if($homeInstructionMedID > 0){	
				$query="DELETE FROM zipad_homeinstruction_medication WHERE homeInstructionMedID = '$homeInstructionMedID'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}
			$this->response('',204);	// If no records "No Content" status
		}

	//Home Instruction end

	//follow upnotes

		private function apiGetFollowUpNotes(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			

			$query = "SELECT * FROM zipad_diagsnotes WHERE ClinixRID = '$ClinixRID' ";
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

	//end follow upnotes


	//admission

		private function apiInsertAdmissionOrders(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$AdmissionData = json_decode(file_get_contents("php://input"),true);

			$PxRID  = (int)$AdmissionData['PxRID'];
			$ClinixRID  = (int)$AdmissionData['ClinixRID'];
			$AdmissionOrder  = (string)$AdmissionData['AdmissionOrder'];
			$HospRID  = "";
			

			$query1="SELECT HospRID FROM zh_hospitalchart WHERE PxRID = '$PxRID' ORDER BY HospRID DESC LIMIT 1;";
			
			$r1 = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);
			if($r1->num_rows > 0) {
				
				while($row = $r1->fetch_assoc()){
					$HospRID= $row['HospRID'];

					$query = "UPDATE zh_hospitalchart SET
						ClinixRID = '$ClinixRID'
						, AdmissionOrder = '$AdmissionOrder'
						WHERE HospRID = '$HospRID'";

					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			
			}
			
		}


		private function apiGetAdmissionOrders(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			
			$query = "SELECT * FROM zh_hospitalchart WHERE ClinixRID = '$ClinixRID' ";
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

		private function apiSignAdmissionOrders(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$AdmissionData = json_decode(file_get_contents("php://input"),true);

			$UserPxRID  = (int)$AdmissionData['UserPxRID'];
			$HospRID  = (int)$AdmissionData['HospRID'];
			$SignedDate= date("Y-m-d H:i:s");

			$query = "UPDATE zh_hospitalchart SET
				SignedPxRID = '$UserPxRID'
				, SignedDate = '$SignedDate'

				WHERE HospRID = '$HospRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			
		}

	// end-admission

	// supplemental

		private function apiInsertSupplemental(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$Supplemental = json_decode(file_get_contents("php://input"),true);

			$SupplementalRID  = (int)$Supplemental['SupplementalRID'];
			$HospRID  = (int)$Supplemental['HospRID'];
			$PxRID  = (int)$Supplemental['PxRID'];
			$ClinixRID  = (int)$Supplemental['ClinixRID'];

			$DateTimeEntered= date("Y-m-d H:i:s");

			// if ($SupplementalRID > 0) {

			// 	$query = "UPDATE zh_supplementalform SET
			// 		PxRID = '$PxRID'
			// 		, ClinixRID = '$ClinixRID' 
			// 		WHERE HospRID = $HospRID";
			// 	$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				
			// }else{

				$query = "INSERT INTO zh_supplementalform SET
					PxRID = '$PxRID'
					, HospRID = '$HospRID'
					, ClinixRID = '$ClinixRID'
					, DateTimeEntered = '$DateTimeEntered'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			// }
			
		}

		private function apiGetSupplemental(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];
			
			$query = "SELECT 
				zh_supplementalform.SupplementalRID
				, zh_supplementalform.HospRID
				, zh_supplementalform.PxRID
				, zh_supplementalform.EnteredBy
				, zh_supplementalform.DateTimeEntered
				, zh_supplementalform.ClinixRID
				, zh_supplementalform.DokPxRID
				, zh_supplementalform.SignedPxRID
				, zh_supplementalform.SignedDate
				, CONCAT (px_data.FirstName, ' ', px_data.LastName) as SignedBy
			FROM zh_supplementalform
			LEFT JOIN px_data ON px_data.PxRID =  zh_supplementalform.SignedPxRID
			WHERE HospRID = '$HospRID' ORDER BY zh_supplementalform.DateTimeEntered DESC";
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

		private function insertSupplementalDetails(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$Supplemental = json_decode(file_get_contents("php://input"),true);

			$SupplementalDetailRID  = (int)$Supplemental['SupplementalDetailRID'];
			$SupplementalRID  = (int)$Supplemental['SupplementalRID'];
			$ItemDescription  = (string)$Supplemental['ItemDescription'];
			$Qty  = (string)$Supplemental['Qty'];
			
			if ($SupplementalDetailRID > 0) {
				$query = "UPDATE zh_supplementalformdetails SET
					SupplementalRID = '$SupplementalRID'
					, ItemDescription = '$ItemDescription'
					, Qty = '$Qty' 
					WHERE SupplementalDetailRID = '$SupplementalDetailRID' ";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}else{
				$query = "INSERT INTO zh_supplementalformdetails SET
					SupplementalRID = '$SupplementalRID'
					, ItemDescription = '$ItemDescription'
					, Qty = '$Qty'";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);	
			}
		}


		private function apiGetSupplementalDetails(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$SupplementalRID = (int)$this->_request['SupplementalRID'];

			$query = "SELECT * FROM zh_supplementalformdetails WHERE SupplementalRID = '$SupplementalRID' ";
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


		private function apiSignSupplemental(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$AdmissionData = json_decode(file_get_contents("php://input"),true);

			$UserPxRID  = (int)$AdmissionData['UserPxRID'];
			$SupplementalRID  = (int)$AdmissionData['SupplementalRID'];
			$SignedDate= date("Y-m-d H:i:s");

			$query = "UPDATE zh_supplementalform SET
				SignedPxRID = '$UserPxRID'
				, SignedDate = '$SignedDate'

				WHERE SupplementalRID = '$SupplementalRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			
		}

		private function apiDelSupplementalDetails (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

	      	$SupplementalDetailRID  = (int)$IntraItem['SupplementalDetailRID'];

            	$query = "DELETE FROM zh_supplementalformdetails 
	            WHERE SupplementalDetailRID = '".$SupplementalDetailRID."'";

			 $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}



	// end supplemental

	//referral

		private function apiInsertReferral(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$ReferralData = json_decode(file_get_contents("php://input"),true);

			$PxRID  = (int)$ReferralData['PxRID'];
			$ClinixRID  = (int)$ReferralData['ClinixRID'];
			$HospRID  = (int)$ReferralData['HospRID'];
			$roomNo  = (int)$ReferralData['roomNo'];
			$impression  = (string)$ReferralData['impression'];
			$ReferTo  = (string)$ReferralData['ReferTo'];
			$ReferFor  = (string)$ReferralData['ReferFor'];
			$ReferralNotesTemp  = (string)$ReferralData['ReferralNotes'];
			$ReferralNotes = str_replace("'", "`", $ReferralNotesTemp);
			// $HospRID  = "";
			

			$query = "INSERT INTO zipad_diagsreferto SET
				HospRID = '$HospRID'
				, ClinixRID = '$ClinixRID'
				, roomNo = '$roomNo'
				, impression = '$impression'
				, ReferTo = '$ReferTo'
				, ReferFor = '$ReferFor'
				, ReferralNotes = '$ReferralNotes'
				";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			// $query1="SELECT HospRID FROM zh_hospitalchart WHERE PxRID = '$PxRID' ORDER BY HospRID DESC LIMIT 1;";
			
			// $r1 = $this->mysqli->query($query1) or die($this->mysqli->error.__LINE__);
			// if($r1->num_rows > 0) {
				
			// 	while($row = $r1->fetch_assoc()){
			// 		$HospRID= $row['HospRID'];

			// 		$query = "UPDATE zh_hospitalchart SET
			// 			ClinixRID = '$ClinixRID'
			// 			, RoomNumber = '$RoomNumber'
			// 			, Impression = '$Impression'
			// 			, RefferedTo = '$RefferedTo'
			// 			, RefferedFor = '$RefferedFor'
			// 			, RefferalNotes = '$RefferalNotes'
			// 			WHERE HospRID = '$HospRID'";

			// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			// 	}
			// 	$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			// }
		}

		private function apiGetReferal(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			

			$query = "SELECT *
			FROM zipad_diagsreferto 
			WHERE ClinixRID = '$ClinixRID' ";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status

			// $query = "SELECT 
			// 	zh_hospitalchart.RoomNumber
			// 	, zh_hospitalchart.Impression
			// 	, zh_hospitalchart.RefferedTo
			// 	, zh_hospitalchart.RefferedFor
			// 	, zh_hospitalchart.RefferalNotes

			// 	, CONCAT(px_data.FirstName, ' ', px_data.LastName) as PxDok
			// FROM zh_hospitalchart 
			// INNER JOIN px_data ON px_data.PxRID = zh_hospitalchart.DokPxRID
			// WHERE ClinixRID = '$ClinixRID' ";
			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			// if($r->num_rows > 0) {
			// 	$result = array();
			// 	while($row = $r->fetch_assoc()){
			// 		$result = $row;
			// 	}
			// 	$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			// }
			// $this->response('',204);	// If no records "No Content" status
		}

	//end referral


	//for dsig 

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


		//orig using pxrid anfd pin don't delete
		private function apiCheckPxDsigZZZ()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$pxDataItem = json_decode(file_get_contents("php://input"),true);
			$UserPxRID = (string)$pxDataItem['UserPxRID'];
			$PIN = (string)$pxDataItem['PIN'];

			$query="SELECT * FROM px_dsig 
				WHERE PxRID = '$UserPxRID' AND PIN = '$PIN'";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = $r->fetch_assoc();	
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);
		}
	// end dsig


		//===============
		// Hip Pre-op Order
		//===============

		private function apiGetHipOtherPreOpOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * 
				, clinix.AppDateSet
				FROM zipad_preop_hip_preform
				INNER JOIN clinix ON clinix.ClinixRID = zipad_preop_hip_preform.ClinixRID
				WHERE zipad_preop_hip_preform.HospRID = '$HospRID' AND zipad_preop_hip_preform.ToAdmitting = 5";
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

		private function apiUpdatePreOpOrders()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$PreOP = json_decode(file_get_contents("php://input"),true);

			$wrid = (string)$PreOP['wrid'];
			$HospRID = (string)$PreOP['HospRID'];

			$query = "UPDATE zipad_preop_hip_preform SET
				ToAdmitting = 5
				, HospRID = '$HospRID'
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiDelOtherPreOpOrders (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

	      	$wrid  = (int)$IntraItem['wrid'];

            $query = "UPDATE zipad_preop_hip_preform SET
				ToAdmitting = 1
				, HospRID = 0
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetHipPreOpOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * 
			, clinix.AppDateSet
			FROM zipad_preop_hip_preform
			INNER JOIN clinix ON clinix.ClinixRID = zipad_preop_hip_preform.ClinixRID
			WHERE zipad_preop_hip_preform.PxRID = '$PxRID' AND zipad_preop_hip_preform.ToAdmitting = 1";
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

		//===============
		// End Hip Pre-op Order
		//===============

		//===============
		// Knee Pre-op Order
		//===============

		private function apiGetKneePreOpOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * 
			, clinix.AppDateSet
			FROM zipad_preop_knee_preform
			INNER JOIN clinix ON clinix.ClinixRID = zipad_preop_knee_preform.ClinixRID
			WHERE zipad_preop_knee_preform.PxRID = '$PxRID' AND zipad_preop_knee_preform.ToAdmitting = 1";
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

		private function apiUpdateKneePreOpOrders()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$PreOP = json_decode(file_get_contents("php://input"),true);

			$wrid = (string)$PreOP['wrid'];
			$HospRID = (string)$PreOP['HospRID'];

			$query = "UPDATE zipad_preop_knee_preform SET
				ToAdmitting = 5
				, HospRID = '$HospRID'
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetKneeOtherPreOpOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * 
				, clinix.AppDateSet
				FROM zipad_preop_knee_preform
				INNER JOIN clinix ON clinix.ClinixRID = zipad_preop_knee_preform.ClinixRID
				WHERE zipad_preop_knee_preform.HospRID = '$HospRID' AND zipad_preop_knee_preform.ToAdmitting = 5";
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

		private function apiDelKneeOtherPreOpOrders (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

	      	$wrid  = (int)$IntraItem['wrid'];

            $query = "UPDATE zipad_preop_knee_preform SET
				ToAdmitting = 1
				, HospRID = 0
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		//===============
		// End Knee Pre-op Order
		//===============

		//===============
		// Admitting Order
		//===============

		private function apiGetOtherAdmittingOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * FROM forms_admitting
				WHERE HospRID = '$HospRID' AND ToAdmitting = 5";
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

		private function apiUpdateAdmittingOrders()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$PreOP = json_decode(file_get_contents("php://input"),true);

			$AdmitRID = (string)$PreOP['AdmitRID'];
			$HospRID = (string)$PreOP['HospRID'];

			$query = "UPDATE forms_admitting SET
				ToAdmitting = 5
				, HospRID = '$HospRID'
				WHERE AdmitRID = '$AdmitRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiDelOtherAdmittingOrders (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

	      	$AdmitRID  = (int)$IntraItem['AdmitRID'];

            $query = "UPDATE forms_admitting SET
				ToAdmitting = 1
				, HospRID = 0
				WHERE AdmitRID = '$AdmitRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetAdmittingOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * FROM forms_admitting
				WHERE PxRID = '$PxRID' AND ToAdmitting = 1";
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

		//===============
		// End Admitting Order
		//===============

		//===============
		// referral Notes
		//===============

		private function apiGetOtherReferralOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * 
			, clinix.AppDateSet
			FROM zipad_diagsreferto
			INNER JOIN clinix ON clinix.ClinixRID = zipad_diagsreferto.ClinixRID
			WHERE zipad_diagsreferto.HospRID = '$HospRID' AND zipad_diagsreferto.ToAdmitting = 5";
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

		private function apiUpdateReferralOrders()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$Referral = json_decode(file_get_contents("php://input"),true);

			$wrid = (string)$Referral['wrid'];
			$HospRID = (string)$Referral['HospRID'];

			$query = "UPDATE zipad_diagsreferto SET
				ToAdmitting = 5
				, HospRID = '$HospRID'
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiDelOtherReferralOrders (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$Referral = json_decode(file_get_contents("php://input"),true);

	      	$wrid  = (int)$Referral['wrid'];

            $query = "UPDATE zipad_diagsreferto SET
				ToAdmitting = 1
				, HospRID = 0
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetReferralOrders()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * 
			, clinix.AppDateSet
			FROM zipad_diagsreferto
			INNER JOIN clinix ON clinix.ClinixRID = zipad_diagsreferto.ClinixRID
			WHERE zipad_diagsreferto.PxRID = '$PxRID' AND zipad_diagsreferto.ToAdmitting = 1";
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

		//===============
		// End referral Notes
		//===============

		//============
		// Schedule Surgery
		//============


		private function apiGetOtherSurgerySchedule()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * FROM zipad_diags_schedsurgery
				WHERE HospRID = '$HospRID' AND ToAdmitting = 5";
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

		private function apiUpdateSurgerySchedule()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$SchedSurgery = json_decode(file_get_contents("php://input"),true);

			$wrid = (string)$SchedSurgery['wrid'];
			$HospRID = (string)$SchedSurgery['HospRID'];

			$query = "UPDATE zipad_diags_schedsurgery SET
				ToAdmitting = 5
				, HospRID = '$HospRID'
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiDelOtherSurgerySchedule (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

	      	$wrid  = (int)$IntraItem['wrid'];

            $query = "UPDATE zipad_diags_schedsurgery SET
				ToAdmitting = 1
				, HospRID = 0
				WHERE wrid = '$wrid'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetSurgerySchedule()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * FROM zipad_diags_schedsurgery
				WHERE PxRID = '$PxRID' AND ToAdmitting = 1";
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

		//============
		// END Schedule Surgery
		//============


		//============
		// Narrative
		//============


		private function apiGetOtherNarrative()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * FROM clinix
				WHERE HospRID = '$HospRID' AND ToAdmitting = 5";
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

		private function apiUpdateNarrative()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$SchedSurgery = json_decode(file_get_contents("php://input"),true);

			$ClinixRID = (string)$SchedSurgery['ClinixRID'];
			$HospRID = (string)$SchedSurgery['HospRID'];

			$query = "UPDATE clinix SET
				ToAdmitting = 5
				, HospRID = '$HospRID'
				WHERE ClinixRID = '$ClinixRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiDelOtherNarrative (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

	      	$ClinixRID  = (int)$IntraItem['ClinixRID'];

            $query = "UPDATE clinix SET
				ToAdmitting = 1
				, HospRID = 0
				WHERE ClinixRID = '$ClinixRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetNarrative()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT * FROM clinix
				WHERE PxRID = '$PxRID' AND ToAdmitting = 1";
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

		//============
		// END Narrative
		//============

		//attachments

		private function apiGetAttachments()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT * 
			FROM zh_attachments
			WHERE HospRID = '$HospRID' AND Deleted = 0";
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


		private function apiDelAttachments (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$Referral = json_decode(file_get_contents("php://input"),true);

	      	$aRID  = (int)$Referral['aRID'];

            $query = "UPDATE zh_attachments SET
				Deleted = 1
				WHERE aRID = '$aRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		//vitals

		private function apiGetLkupVitals()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query="SELECT * 
			FROM lkup_vitals
			WHERE Deleted = 0";
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


		private function apiInsertVitals(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$VitalsData = json_decode(file_get_contents("php://input"),true);

			$HospRID  = (int)$VitalsData['HospRID'];
			$PxRID  = (int)$VitalsData['PxRID'];
			$ClinixRID  = (int)$VitalsData['ClinixRID'];
			
			$VitalsRID  = (int)$VitalsData['VitalsRID'];
			$Value  = (string)$VitalsData['Value'];

			$DateEntered = date("Y-m-d H:i:s");
			
			$query = "INSERT INTO clinix_vitals SET
				HospRID = '$HospRID'
				, PxRID = '$PxRID'
				, ClinixRID = '$ClinixRID'
				, VitalsRID = '$VitalsRID'
				, Value = '$Value'
				, DateEntered = '$DateEntered'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);	
		}


		private function apiGetVitals()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$HospRID = (int)$this->_request['HospRID'];

			$query="SELECT 
				clinix_vitals.CVitRID
				, clinix_vitals.Value
				, clinix_vitals.DateEntered

				, lkup_vitals.Description
				, lkup_vitals.UOM

			FROM clinix_vitals
			INNER JOIN lkup_vitals ON lkup_vitals.VitalsRID = clinix_vitals.VitalsRID
			WHERE clinix_vitals.HospRID = $HospRID AND  clinix_vitals.Deleted = 0";
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


		private function apiDelVitals (){ 
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$VitalsData = json_decode(file_get_contents("php://input"),true);

	      	$CVitRID  = (int)$VitalsData['CVitRID'];

            $query = "UPDATE clinix_vitals SET
				Deleted = 1
				WHERE CVitRID = '$CVitRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		//charges

		private function apiClinixChargesTariff(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$query="SELECT * FROM lkup_clinixcharges ORDER BY SortOrder";

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

		private function apiGetHospitalcharges(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$HospRID = (int)$this->_request['HospRID'];
			
			//if($id > 0){	
				$query="SELECT 
					zipad_pecharges.PEChargesRID
					, zipad_pecharges.ClinixRID
					, zipad_pecharges.PxRID
					, zipad_pecharges.FeeRID
					, zipad_pecharges.ChargeItem
					, zipad_pecharges.Tariff
					, zipad_pecharges.ChargeAmount
					, zipad_pecharges.Discount
					, zipad_pecharges.NetAmount
					, zipad_pecharges.LinePayment
					, zipad_pecharges.LineBalance

					, zipad_pecharges.SynchStatus
					FROM zipad_pecharges 
					WHERE HospRID = '$HospRID' AND Deleted = 0
					ORDER BY PEChargesRID
					";
					// $wfp = fopen("zzz_GMMR.zzz", "w");
					// fwrite($wfp, $query);
					// fclose($wfp);
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			//}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertHospitalcharges(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$HospRID  = (int)$IntraItem['HospRID'];
			$PxRID  = (int)$IntraItem['PxRID'];
			$FeeRID  = (string)$IntraItem['FeeRID'];
			$Description  = (string)$IntraItem['Description'];
			$Tariff  = (string)$IntraItem['Tariff'];
			$ChargeAmount  = (string)$IntraItem['ChargeAmount'];
			$Discount  = (string)$IntraItem['Discount'];
			$NetAmount  = (string)$IntraItem['NetAmount'];
			

			$query = "INSERT INTO zipad_pecharges SET
				HospRID = '".$HospRID."'
				, PxRID = '".$PxRID."'
				, FeeRID = '".$FeeRID."'
				, ChargeItem = '".$Description."'
				, Tariff = '".$Tariff."'
				, ChargeAmount = '".$ChargeAmount."'
				, Discount = '".$Discount."'
				, NetAmount = '".$NetAmount."'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiDelHospitalcharges(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$PEChargesRID  = (int)$IntraItem['PEChargesRID'];

			$query = "UPDATE zipad_pecharges SET Deleted = 1
			WHERE PEChargesRID = '".$PEChargesRID."'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}



		private function apiGetReferTo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT zipad_DiagsReferTo.* 
				, px_dsig.b64a
				, CONCAT(px_data.FirstName,' ',px_data.LastName) as physicianName
				, risita_format.foot1
				FROM zipad_DiagsReferTo 
				LEFT JOIN px_dsig ON px_dsig.PxRID = zipad_DiagsReferTo.physicianPxRID
				LEFT JOIN px_data ON px_data.PxRID = zipad_DiagsReferTo.physicianPxRID
				LEFT JOIN risita_format ON risita_format.PxRID = zipad_DiagsReferTo.physicianPxRID
				WHERE ClinixRID = ".$id." ";
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
		}

		// PHYSICAL EXAMINATIONS - Pelvis

		// private function apiPelvicHipTrumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_traumapelvicacetabulum_inspection WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_traumapelvicacetabulum_inspection SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_traumapelvicacetabulum_inspection WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }

		// En dPHYSICAL EXAMINATIONS - Pelvis

		// PHYSICAL EXAMINATIONS - Pelvis
		// private function apiHipJointTrumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_trauma_hipjoint_appearance WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_trauma_hipjoint_appearance SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_trauma_hipjoint_appearance WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result[] = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }

		// End PHYSICAL EXAMINATIONS - Pelvis

		// PHYSICAL EXAMINATIONS - WristhandTruma

		// private function apiWristhandTrumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_traumawristhand_inspection WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_traumawristhand_inspection SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_traumawristhand_inspection WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result[] = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }

		// End PHYSICAL EXAMINATIONS - WristhandTruma

		// PHYSICAL EXAMINATIONS - TibiaShaft

		// private function apiaTibiaShaftTraumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_trauma_tibialshaft_appearance WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_trauma_tibialshaft_appearance SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_trauma_tibialshaft_appearance WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result[] = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }

		// End PHYSICAL EXAMINATIONS - TibiaShaft



		//PHYSICAL EXAMINATIONS - FemorShaftTruma
		// private function apiFemorShaftTrumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_trauma_femoralshaft_appearance WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_trauma_femoralshaft_appearance SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_trauma_femoralshaft_appearance WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result[] = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }


		//End PHYSICAL EXAMINATIONS - FemorShaftTruma

		//PHYSICAL EXAMINATIONS - ForearmTruma

		// private function apiForearmTrumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_traumaforearm_inspection WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_traumaforearm_inspection SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_traumaforearm_inspection WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result[] = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }

		//end PHYSICAL EXAMINATIONS - ForearmTruma

		//PHYSICAL EXAMINATIONS - FootAnkleTrauma
		
		// private function apiaFootAnkleTraumaApp(){	
		// 	if($this->get_request_method() != "GET"){
		// 		$this->response('',406);
		// 	}
		// 	$id = (int)$this->_request['id'];
			
		// 	if($id > 0)
		// 	{	
		// 		$query="SELECT * FROM zipad_trauma_footankle_appearance WHERE ClinixRID = '$id';";
		// 		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 		if($r->num_rows > 0) {
		// 			$result = array();
		// 			while($row = $r->fetch_assoc()){
		// 				$result[] = $row;
		// 			}
		// 			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 		}
		// 		else
		// 		{
		// 			$query="INSERT INTO zipad_trauma_footankle_appearance SET ClinixRID = '$id'";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		// 			$query="SELECT * FROM zipad_trauma_footankle_appearance WHERE ClinixRID = '$id';";
		// 			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		// 			if($r->num_rows > 0) {
		// 				$result = array();
		// 				while($row = $r->fetch_assoc()){
		// 					$result[] = $row;
		// 				}
		// 				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
		// 			}
		// 		}
		// 	}
		// 	$this->response('',204);	// If no records "No Content" status
		// }

		//PHYSICAL EXAMINATIONS - FootAnkleTrauma




		private function apiGetNursesNotes(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];

			$query="SELECT zh_fdare.* 
			, DATE(zh_fdare.dateTimeShift) as addedDateShift
	        , TIME(zh_fdare.dateTimeShift) as addedTimeShift
	        , CONCAT (px_data.FirstName, ' ', SUBSTRING(px_data.MiddleName, 1,1), ' .',px_data.LastName) as signedBy
	        , px_dsig.b64a
	        , px_dok.PRC
			FROM zh_fdare 
			LEFT JOIN px_data ON zh_fdare.signedPxRID = px_data.PxRID 
			LEFT JOIN px_dsig ON zh_fdare.signedPxRID = px_dsig.PxRID 
			LEFT JOIN px_dok ON zh_fdare.signedPxRID = px_dok.PxRID 
			WHERE zh_fdare.ClinixRID = '$ClinixRID' AND zh_fdare.Deleted = 0";

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



		private function apiGetDoctorsList (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

				$query="SELECT 
					px_dok.* 
					, CONCAT(px_data.LastName,', ',px_data.FirstName) as docPxName
					FROM px_dok 
                    INNER JOIN px_data ON px_data.PxRID=px_dok.PxRID 
                    INNER JOIN users ON px_data.PxRID=users.PxRID 
                    WHERE (px_dok.Deleted=0 AND ActivationKey IS NOT NULL AND PRC IS NOT NULL AND UserStatus = 9) ORDER BY LastName, FirstName;";
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


		private function apiGetPxListReport(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$DokPxRID = (int)$this->_request['DokPxRID'];
			$fromDate = (string)$this->_request['fromDate'];
			$toDate = (string)$this->_request['toDate'];

			$query="SELECT
				clinix.*
				, px_data.PxRID
				, SUBSTR(px_data.MiddleName, 1, 1) as MiddleName
				, CONCAT (px_data.FirstName, ' ', SUBSTRING(px_data.MiddleName, 1,1), ' .',px_data.LastName) as pxName
				, CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
				, TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge
				, px_data.Sex
				, px_data.MaritalStatus
				, px_data.foto

				, lkup_TranStatus.TrnSttsRID
				, lkup_TranStatus.preForeColor
				, lkup_TranStatus.preBackColor
				, lkup_TranStatus.TrnStts

				FROM clinix
				INNER JOIN px_data ON clinix.PxRID = px_data.PxRID 
				INNER JOIN lkup_TranStatus ON clinix.TranStatus = lkup_TranStatus.TrnSttsRID 
				WHERE (clinix.AppDateSet >= '$fromDate' AND clinix.AppDateSet <= '$toDate') AND clinix.DokPxRID = '$DokPxRID' 
				ORDER BY clinix.ClinixRID;";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status			
		}



		private function apiGetAllPxListReport(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$fromDate = (string)$this->_request['fromDate'];
			$toDate = (string)$this->_request['toDate'];

			$query="SELECT
				clinix.*
				, px_data.PxRID
				, SUBSTR(px_data.MiddleName, 1, 1) as MiddleName
				, CONCAT (px_data.FirstName, ' ', SUBSTRING(px_data.MiddleName, 1,1), '. ',px_data.LastName) as pxName
				, CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
				, TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge
				, px_data.Sex
				, px_data.MaritalStatus
				, px_data.foto

				, lkup_TranStatus.TrnSttsRID
				, lkup_TranStatus.preForeColor
				, lkup_TranStatus.preBackColor
				, lkup_TranStatus.TrnStts

				FROM clinix
				INNER JOIN px_data ON clinix.PxRID = px_data.PxRID 
				INNER JOIN lkup_TranStatus ON clinix.TranStatus = lkup_TranStatus.TrnSttsRID 
				WHERE (clinix.AppDateSet >= '$fromDate' AND clinix.AppDateSet <= '$toDate') 
				ORDER BY clinix.ClinixRID;";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status			
		}




		//==============
		//knee score
		//===============

		private function apiGetKneeScore(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			$PxRID = (int)$this->_request['PxRID'];
			
			$query = "SELECT * FROM zipad_kneeScore WHERE ClinixRID = '$ClinixRID' AND Deleted = 0 ";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}else{

				$query2 = "INSERT INTO zipad_kneeScore SET
					PxRID = '$PxRID'
					, ClinixRID = '$ClinixRID' ";

				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				$query = "SELECT * FROM zipad_kneeScore WHERE ClinixRID = '$ClinixRID' AND Deleted = 0 ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertKneeScore (){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$pain  = (int)$IntraItem['pain'];
            $flexionContracture  = (int)$IntraItem['flexionContracture'];
            $extensionLag  = (int)$IntraItem['extensionLag'];
            $rangeOfFlexion  = (int)$IntraItem['rangeOfFlexion'];
            $alignment  = (int)$IntraItem['alignment'];
            $anteriorPosterior  = (int)$IntraItem['anteriorPosterior'];
            $mediolateral  = (int)$IntraItem['mediolateral'];
            $walking  = (int)$IntraItem['walking'];
            $stairs  = (int)$IntraItem['stairs'];
            $walkingAids  = (int)$IntraItem['walkingAids'];

			$query = "UPDATE zipad_kneeScore SET 
				pain = '$pain'
	            , flexionContracture = '$flexionContracture'
	            , extensionLag = '$extensionLag'
	            , rangeOfFlexion = '$rangeOfFlexion'
	            , alignment = '$alignment'
	            , anteriorPosterior = '$anteriorPosterior'
	            , mediolateral = '$mediolateral'
	            , walking = '$walking'
	            , stairs = '$stairs'
	            , walkingAids = '$walkingAids'

			WHERE ClinixRID = '$ClinixRID' ";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiRemoveKneeScore (){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$kneeScoreRID  = (int)$IntraItem['kneeScoreRID'];
			

			$query = "UPDATE zipad_kneeScore SET 
				Deleted = 1
	            

			WHERE kneeScoreRID = '$kneeScoreRID' ";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		//==============
		//End knee score
		//===============


		//===============
		//hip score
		//===============

		private function apiGetHipScore(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			$PxRID = (int)$this->_request['PxRID'];
			
			$query = "SELECT * FROM zipad_hipScore WHERE ClinixRID = '$ClinixRID' AND Deleted = 0 ";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()){
					$result = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}else{

				$query2 = "INSERT INTO zipad_hipScore SET
					PxRID = '$PxRID'
					, ClinixRID = '$ClinixRID' ";

				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				$query = "SELECT * FROM zipad_hipScore WHERE ClinixRID = '$ClinixRID' AND Deleted = 0 ";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertHipScore (){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$ClinixRID  = (int)$IntraItem['ClinixRID'];
			$pain  = (int)$IntraItem['pain'];
            $limp  = (int)$IntraItem['limp'];
            $support  = (int)$IntraItem['support'];
            $distanceWalked  = (int)$IntraItem['distanceWalked'];
            $sitting  = (int)$IntraItem['sitting'];
            $publicTranspo  = (int)$IntraItem['publicTranspo'];
            $stairs  = (int)$IntraItem['stairs'];
            $shoesSocks  = (int)$IntraItem['shoesSocks'];
            $deformity  = (int)$IntraItem['deformity'];
            $rangeOfMotion  = (int)$IntraItem['rangeOfMotion'];

            $flexion  = (double)$IntraItem['flexion'];
            $abduction  = (double)$IntraItem['abduction'];
            $externalRotation  = (double)$IntraItem['externalRotation'];
            $adduction  = (double)$IntraItem['adduction'];

            $studyHip  = (int)$IntraItem['studyHip'];
            $intervalHip  = (string)$IntraItem['intervalHip'];

			$query = "UPDATE zipad_hipScore SET 
				pain = '$pain'
	            , limp = '$limp'
	            , support = '$support'
	            , distanceWalked = '$distanceWalked'
	            , sitting = '$sitting'
	            , publicTranspo = '$publicTranspo'
	            , stairs = '$stairs'
	            , shoesSocks = '$shoesSocks'
	            , deformity = '$deformity'
	            , rangeOfMotion = '$rangeOfMotion'
	            , flexion = '$flexion'
	            , abduction = '$abduction'
	            , externalRotation = '$externalRotation'
	            , adduction = '$adduction'

	            , studyHip = '$studyHip'
	            , intervalHip = '$intervalHip'

			WHERE ClinixRID = '$ClinixRID' ";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiRemoveHipScore (){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$hipScoreRID  = (int)$IntraItem['hipScoreRID'];
			

			$query = "UPDATE zipad_hipScore SET 
				Deleted = 1
	            

			WHERE hipScoreRID = '$hipScoreRID' ";


			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		//==============
		//End hip score
		//===============


		//hxvisits
		private function apiGetAllHxVisits(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];
			
			$query = "SELECT clinix.*
			, lkup_TranStatus.TrnStts
			, lkup_TranStatus.preForeColor
			, lkup_TranStatus.preBackColor
			FROM clinix 
			INNER JOIN lkup_TranStatus ON lkup_TranStatus.TrnSttsRID = clinix.TranStatus
			WHERE PxRID = '$PxRID' 
			ORDER BY ClinixRID DESC";
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

		private function apiGetHxVisits(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];
			
			$query = "SELECT clinix.*
			, lkup_TranStatus.TrnStts
			, lkup_TranStatus.preForeColor
			, lkup_TranStatus.preBackColor
			, CONCAT (px_data.LastName, ', ', px_data.FirstName, ', ', px_data.MiddleName) as pxname
			, CONCAT (px_data.Sex,' / ', TIMESTAMPDIFF( YEAR, px_data.DOB, CURDATE( ) ), ' / ', px_data.MaritalStatus) as pxstatus
			, CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
			, TIMESTAMPDIFF( YEAR, px_data.DOB, CURDATE( ) ) as pxAge
			, px_data.foto
			, px_data.RegDate
			, px_data.Balance
			, px_data.Occupation
			, px_data.DOB
			, px_data.Sex
			
			, px_data.ReferralType
			, px_data.ReferredBy

			, px_data.SSS
			, px_data.GSIS
			, px_data.PagIBIG
			, px_data.PhilHealth
			FROM clinix 
			INNER JOIN lkup_TranStatus ON lkup_TranStatus.TrnSttsRID = clinix.TranStatus
			INNER JOIN px_data ON px_data.PxRID = clinix.PxRID
			WHERE ClinixRID = '$ClinixRID' 
			ORDER BY ClinixRID DESC";
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


		private function apiGetHxSchedSurgery(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];
			
				$query="SELECT 
						zipad_diags_schedsurgery.ClinixRID
						, zipad_diags_schedsurgery.SurgeryDate
						, zipad_diags_schedsurgery.SurgeryTime
						, zipad_diags_schedsurgery.SurgeryTimeEnd
						, zipad_diags_schedsurgery.Surgeon
						, zipad_diags_schedsurgery.Assistant
						, zipad_diags_schedsurgery.Cardio
						, zipad_diags_schedsurgery.Anesthesio
						, zipad_diags_schedsurgery.AnesthesiaType
						, zipad_diags_schedsurgery.Hospital
						, zipad_diags_schedsurgery.OrNurse
						, zipad_diags_schedsurgery.SurgeryType
						, px_data.PxRID
						, px_data.FirstName
						, px_data.MiddleName
						, px_data.LastName
						, px_data.Sex
						, TIMESTAMPDIFF( YEAR, DOB, CURDATE( ) ) AS pxAge
						, px_data.PhilHealth
						, px_data.DOB

						, zipad_diagnosis.Diagnosis
						, zipad_ophip_6.Diagnosis as PostOpHipDiagnosis
						, zipad_opknee_5.Diagnosis as PostOpKneeDiagnosis
						, zipad_trauma_op_surgicaltech.Diagnosis as PostOpTraumaDiagnosis
						
					FROM zipad_diags_schedsurgery 
					INNER JOIN clinix ON zipad_diags_schedsurgery.ClinixRID = clinix.ClinixRID
					INNER JOIN px_data ON clinix.PxRID = px_data.PxRID
					LEFT JOIN zipad_diagnosis ON zipad_diagnosis.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					LEFT JOIN zipad_opknee_5 ON zipad_opknee_5.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					LEFT JOIN zipad_ophip_6 ON zipad_ophip_6.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					LEFT JOIN zipad_trauma_op_surgicaltech ON zipad_trauma_op_surgicaltech.ClinixRID = zipad_diags_schedsurgery.ClinixRID
					WHERE zipad_diags_schedsurgery.PxRID = '".$PxRID."'
					ORDER BY zipad_diags_schedsurgery.SurgeryDate DESC";
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


		private function apiGetAllPxChart(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];
			
			$query = "SELECT clinix.*
			, lkup_TranStatus.TrnStts
			, lkup_TranStatus.preForeColor
			, lkup_TranStatus.preBackColor
			, CONCAT (px_data.LastName, ', ', px_data.FirstName, ', ', px_data.MiddleName) as pxname
			, CONCAT (px_data.Sex,' / ', TIMESTAMPDIFF( YEAR, px_data.DOB, CURDATE( ) ), ' / ', px_data.MaritalStatus) as pxstatus
			, CONCAT (px_data.Street, ', ', px_data.City, ', ', px_data.Province) as pxAddress
			, TIMESTAMPDIFF( YEAR, px_data.DOB, CURDATE( ) ) as pxAge
			, px_data.foto
			, px_data.RegDate
			, px_data.Balance
			, px_data.Occupation
			, px_data.DOB
			, px_data.Sex
			
			, px_data.ReferralType
			, px_data.ReferredBy

			, px_data.SSS
			, px_data.GSIS
			, px_data.PagIBIG
			, px_data.PhilHealth
			FROM clinix 
			INNER JOIN lkup_TranStatus ON lkup_TranStatus.TrnSttsRID = clinix.TranStatus
			INNER JOIN px_data ON px_data.PxRID = clinix.PxRID
			WHERE clinix.PxRID = '$PxRID' 
			ORDER BY clinix.ClinixRID ASC";
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






		//HIP Xrays and Videos
		//HIP Xrays and Videos
		//HIP Xrays and Videos

		private function apiGetPREOpHIPXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1001 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPreOpHIPImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1010 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPreOpHIPVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1020 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPostOpHIPXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1030 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPostOpHIPImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1040 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPostOpHIPVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1050 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}
		//END HIP Xrays and Videos
		//END HIP Xrays and Videos
		//END HIP Xrays and Videos



		//KNEE Xrays and Videos
		//KNEE Xrays and Videos
		//KNEE Xrays and Videos
		private function apiGetPREOpKNEEXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1101 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpKNEEImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1110 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpKNEEVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1120 HAVING Deleted = 0 ORDER BY RefDate, Priority";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpKNEEXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1130 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpKNEEImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1140 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpKNEEVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1150 HAVING Deleted = 0 ORDER BY RefDate, Priority";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

//END KNEE Xrays and Videos
//END KNEE Xrays and Videos
//END KNEE Xrays and Videos


//SPINE Xrays and Videos
//SPINE Xrays and Videos
//SPINE Xrays and Videos

		private function apiGetPREOpSPINXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1201 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpSPINImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1210 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpSPINVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1220 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetPOSTOpSPINXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1230 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpSPINImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1240 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpSPINVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1250 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

//END SPINE Xrays and Videos
//END SPINE Xrays and Videos
//END SPINE Xrays and Videos


//GENORTHO Xrays and Videos
//GENORTHO Xrays and Videos
//GENORTHO Xrays and Videos

		private function apiGetPREOpGENORTHOXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1301 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpGENORTHOImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1310 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpGENORTHOVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1320 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetPOSTOpGENORTHOXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1330 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpGENORTHOImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1340 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpGENORTHOVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1350 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

//END GENORTHO Xrays and Videos
//END GENORTHO Xrays and Videos
//END GENORTHO Xrays and Videos

//SKELTRAUMA Xrays and Videos
//SKELTRAUMA Xrays and Videos
//SKELTRAUMA Xrays and Videos

		private function apiGetPREOpSKELTraumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1401 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpSKELTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1410 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREOpSKELTraumaVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1420 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetPOSTOpSKELTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1430 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpSKELTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1440 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPOSTOpSKELTraumaVid(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1450 HAVING Deleted = 0";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

//END SKELTRAUMA Xrays and Videos
//END SKELTRAUMA Xrays and Videos
//END SKELTRAUMA Xrays and Videos




		//Trauma Module

		//Trauma Clavicle Module
		private function apigetClavicleTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaclavicle_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaclavicle_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaclavicle_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiUpdateClavicleInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $RadialNerve= (string)$IntraItem['RadialNerve'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumaclavicle_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {
		    	$querysel = "SELECT * FROM zipad_traumaclavicle_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumaclavicle_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumaclavicle_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}

		    }
		  

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apigetXrayClavicleTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaclavicle_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaclavicle_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaclavicle_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpXrayClavicle(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $XrayOrdered = (string)$IntraItem['XrayOrdered'];
            $APBothShoulders = (string)$IntraItem['APBothShoulders'];
            $CephalicDegrees = (string)$IntraItem['CephalicDegrees'];
            $CaudalDegrees = (string)$IntraItem['CaudalDegrees'];
            $StressViews = (string)$IntraItem['StressViews'];
            $OthersXrayOrdered= (string)$IntraItem['OthersXrayOrdered'];
            $FractureLocation= (string)$IntraItem['FractureLocation'];
            $FractureConfiguration= (string)$IntraItem['FractureConfiguration'];
            $Shortening= (string)$IntraItem['Shortening'];
            $ShorteningMeasurement= (string)$IntraItem['ShorteningMeasurement'];
            $Displacement= (string)$IntraItem['Displacement'];
            $DisplacementMeasurement= (string)$IntraItem['DisplacementMeasurement'];
            $Comminution= (string)$IntraItem['Comminution'];
            $Segmental= (string)$IntraItem['Segmental'];
            $OthersFindings= (string)$IntraItem['OthersFindings'];

             
		    	$query = "UPDATE zipad_traumaclavicle_xray SET
				pxrid = '".$pxrid."',
				APBothShoulders = '".$APBothShoulders."',
            	CephalicDegrees = '".$CephalicDegrees."',
            	CaudalDegrees = '".$CaudalDegrees."',
            	StressViews = '".$StressViews."',
				OthersXrayOrdered = '".$OthersXrayOrdered."',
				FractureLocation = '".$FractureLocation."',
				FractureConfiguration = '".$FractureConfiguration."',
				Shortening = '".$Shortening."',
				ShorteningMeasurement = '".$ShorteningMeasurement."',
				Displacement = '".$Displacement."',
				DisplacementMeasurement = '".$DisplacementMeasurement."',
				Comminution = '".$Comminution."',
				Segmental = '".$Segmental."',
				OthersFindings = '".$OthersFindings."'
		        WHERE ClinixRID = ".$clinix."";
		        
		  //       $wfp = fopen("claviclexray.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Location: ".$FractureLocation."<br>
			// 		Fracture Configuration: ".$FractureConfiguration."<br>
			// 		Shortening: ".$Shortening." <br>
			// 		Shortening Measurement: ".$ShorteningMeasurement." <br>
			// 		Displacement: ".$Displacement."<br>
			// 		Comminution: ".$Comminution."<br>
			// 		Segmental: ".$Segmental."<br>
			// 		Others: ".$OthersFindings;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";
		        
		 //      $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}


		private function apiPREClavicleTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1861 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREClavicleTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1862 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREClavicleTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1863 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTClavicleTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1864 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTClavicleTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1865 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTClavicleTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1866 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}
	//End Clavicle Trauma


	//Trauma Scapula
		private function apigetScapulaTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumascapula_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumascapula_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumascapula_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiUpdateScapulaInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $RadialNerve= (string)$IntraItem['RadialNerve'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumascapula_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {
		    	$querysel = "SELECT * FROM zipad_traumascapula_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumascapula_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";

			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumascapula_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
					
		    	}

		    }
		  		// $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apigetXrayScapulaTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumascapula_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumascapula_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumascapula_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}





		private function apiUpXrayScapula(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $APShoulder = (string)$IntraItem['APShoulder'];
            $ScapulaY = (string)$IntraItem['ScapulaY'];
            $AxillaryView = (string)$IntraItem['AxillaryView'];
            $XrayOrdered = (string)$IntraItem['XrayOrdered'];
           	$FracLocCoracoid = (string)$IntraItem['FracLocCoracoid'];
            $FracLocAcromial = (string)$IntraItem['FracLocAcromial'];
            $FracLocScapneck = (string)$IntraItem['FracLocScapneck'];
            $FracLocScapdeslo = (string)$IntraItem['FracLocScapdeslo'];
            $FracLocScapularbody = (string)$IntraItem['FracLocScapularbody'];
            $FractureLocationOthers= (string)$IntraItem['FractureLocationOthers'];
            $OthersFindings= (string)$IntraItem['OthersFindings'];
            $OthersFindings2 = str_replace("'", "`", $OthersFindings);

             
		    	$query = "UPDATE zipad_traumascapula_xray SET
				pxrid = '".$pxrid."',
            	APShoulder = '".$APShoulder."',
            	ScapulaY = '".$ScapulaY."',
            	AxillaryView = '".$AxillaryView."',
				XrayOrdered = '".$XrayOrdered."',
				FractureLocationOthers = '".$FractureLocationOthers."',
				FracLocCoracoid = '".$FracLocCoracoid."',
	            FracLocAcromial = '".$FracLocAcromial."',
	            FracLocScapneck = '".$FracLocScapneck."',
	            FracLocScapdeslo = '".$FracLocScapdeslo."',
	            FracLocScapularbody = '".$FracLocScapularbody."',
				OthersFindings = '".$OthersFindings2."'
		        WHERE ClinixRID = ".$clinix."";
		        
		  //       $wfp = fopen("claviclexray.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Location: ".$FractureLocation."<br>
			// 		Others: ".$OthersFindings." ";
					

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";
		        
		 //      $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			}

	private function apiPREScapulaTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1867 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREScapulaTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1868 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREScapulaTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1869 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTScapulaTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1870 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTScapulaTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1871 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTScapulaTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1872 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}
		
	//End-Trauma Scapula
	

	//Trauma Shoulder
	private function apigetshoulderTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumashoulder_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumashoulder_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumashoulder_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateShoulderInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $RadialNerve= (string)$IntraItem['RadialNerve'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumashoulder_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {
		    	$querysel = "SELECT * FROM zipad_traumashoulder_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumashoulder_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumashoulder_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
		    }
		  // 		$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apigetXrayShoulderTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumashoulder_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumashoulder_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumashoulder_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpXrayShoulder(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
           	$APShoulder = (string)$IntraItem['APShoulder'];
            $ScapulaY = (string)$IntraItem['ScapulaY'];
            $AxillaryView = (string)$IntraItem['AxillaryView'];
            $ApicalOblique = (string)$IntraItem['ApicalOblique'];
            $Velpeau = (string)$IntraItem['Velpeau'];
            $WestPoint = (string)$IntraItem['WestPoint'];
            $XrayOrdered = (string)$IntraItem['XrayOrdered'];
            $GreaterTub = (string)$IntraItem['GreaterTub'];
	        $LesserTube = (string)$IntraItem['LesserTube'];
	        $Articularsurface = (string)$IntraItem['Articularsurface'];
	        $Shaft = (string)$IntraItem['Shaft'];
            $Displacement = (string)$IntraItem['Displacement'];
            $Varusangulation = (string)$IntraItem['Varusangulation'];
            $Valgusangulation = (string)$IntraItem['Valgusangulation'];
            $Dislocation = (string)$IntraItem['Dislocation'];
            $DislocationAntPos = (string)$IntraItem['DislocationAntPos'];
            $FractureDislocation = (string)$IntraItem['FractureDislocation'];
            $OthersFindings = (string)$IntraItem['OthersFindings'];

              
             
		    	$query = "UPDATE zipad_traumashoulder_xray SET
				pxrid = '".$pxrid."',
				APShoulder = '".$APShoulder."',
	            ScapulaY = '".$ScapulaY."',
	            AxillaryView = '".$AxillaryView."',
	            ApicalOblique = '".$ApicalOblique."',
	            Velpeau = '".$Velpeau."',
	            WestPoint = '".$WestPoint."',
	            XrayOrdered = '".$XrayOrdered."',
	            GreaterTub = '".$GreaterTub."',
		        LesserTube = '".$LesserTube."',
		        Articularsurface = '".$Articularsurface."',
		        Shaft = '".$Shaft."',
	            Displacement = '".$Displacement."',
	            Varusangulation = '".$Varusangulation."',
	            Valgusangulation = '".$Valgusangulation."',
	            Dislocation = '".$Dislocation."',
	            DislocationAntPos = '".$DislocationAntPos."',
	            FractureDislocation = '".$FractureDislocation."',
	            OthersFindings = '".$OthersFindings."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description: ".$FractureDescription."<br>
			// 		Displacement: ".$Displacement."<br>
			// 		Varus Angulation: ".$Varusangulation." <br>
			// 		Valgus Angulation: ".$Valgusangulation." <br>
			// 		Dislocation: ".$Dislocation." <br>
			// 		Type of Dislocation: ".$DislocationAnterior." <br>
			// 		Fracture Dislocation: ".$FractureDislocation." <br>
			// 		Others: ".$OthersFindings;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPREShoulderTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1873 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREShoulderTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1874 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREShoulderTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1875 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTShoulderTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1876 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTShoulderTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1877 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTShoulderTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1878 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

	//End - Trauma Shoulder

	//Humeral Shaft Trauma
		private function apigetHumeralshaftInspection(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumahumeralshaft_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumahumeralshaft_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumahumeralshaft_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateHumeralshaftInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $RadialNerve= (string)$IntraItem['RadialNerve'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumahumeralshaft_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				RadialNerve = '".$RadialNerve."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {

		    	$querysel = "SELECT * FROM zipad_traumahumeralshaft_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumahumeralshaft_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumahumeralshaft_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}

		    }
		  //             $wfp = fopen("humeral.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}

		private function apigetHumeralshaftXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumahumeralshaft_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumahumeralshaft_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumahumeralshaft_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateHumeralshaftXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $XrayOrderedfullap= (string)$IntraItem['XrayOrderedfullap'];
            $XrayOrderedfulllateral= (string)$IntraItem['XrayOrderedfulllateral'];
            $XrayOrderedNotes= (string)$IntraItem['XrayOrderedNotes'];
            $FractureDescription= (string)$IntraItem['FractureDescription'];
            $FractureDescComm= (string)$IntraItem['FractureDescComm'];
            $FractureDescCommDis= (string)$IntraItem['FractureDescCommDis'];
            $FractureDescComp= (string)$IntraItem['FractureDescComp'];
            $Proximal= (string)$IntraItem['Proximal'];
            $VarusAngulation= (string)$IntraItem['VarusAngulation'];
            $ValgusAngulation= (string)$IntraItem['ValgusAngulation'];
            $XrayFindingsOthers= (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumahumeralshaft_xray SET
				pxrid = '".$pxrid."',
				XrayOrderedfullap = '".$XrayOrderedfullap."',
				XrayOrderedfulllateral = '".$XrayOrderedfulllateral."',
				XrayOrderedNotes = '".$XrayOrderedNotes."',
				FractureDescription = '".$FractureDescription."',
				FractureDescComm = '".$FractureDescComm."',
				FractureDescCommDis = '".$FractureDescCommDis."',
				FractureDescComp = '".$FractureDescComp."',
				Proximal = '".$Proximal."',
				VarusAngulation = '".$VarusAngulation."',
				ValgusAngulation = '".$ValgusAngulation."',
				XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description: ".$FractureDescription."<br>
			// 		Proximal: ".$Proximal."<br>
			// 		Varus Angulation: ".$VarusAngulation." <br>
			// 		Valgus Angulation: ".$ValgusAngulation." <br>
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiPREHumeralShaftTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1855 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREHumeralShaftTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1856 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREHumeralShaftTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1857 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTHumeralShaftTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1858 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTHumeralShaftTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1859 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTHumeralShaftTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1860 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}
	//Humeral Shaft Trauma



	//Distal Humerus Trauma
	private function apiDestalhumerusTrauma(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumadestalhumerus_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumadestalhumerus_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumadestalhumerus_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiUpdateDestalhumerusInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others2 = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumadestalhumerus_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others2."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {
		    	$querysel = "SELECT * FROM zipad_traumadestalhumerus_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumadestalhumerus_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumadestalhumerus_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}

		    }
		             

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}

		private function apiDestalhumerusTraumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumadistalhumerus_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumadistalhumerus_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumadistalhumerus_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateDistalhumerusXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $APHumerus = (string)$IntraItem['APHumerus'];
            $LateralHumerus = (string)$IntraItem['LateralHumerus'];
            $APview  = (string)$IntraItem['APview'];
            $LateralElbow = (string)$IntraItem['LateralElbow'];
            $XrayOrderedNotes  = (string)$IntraItem['XrayOrderedNotes'];
            $Supracondylar = (string)$IntraItem['Supracondylar'];
            $SingleColumn = (string)$IntraItem['SingleColumn'];
            $Bicolumnfracture = (string)$IntraItem['Bicolumnfracture'];
            $Coronalshear = (string)$IntraItem['Coronalshear'];
            $Comminution = (string)$IntraItem['Comminution'];
            $DisUndis = (string)$IntraItem['DisUndis'];
            $OpenFructure = (string)$IntraItem['OpenFructure'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumadistalhumerus_xray SET
				pxrid = '".$pxrid."',
				APHumerus = '".$APHumerus."',
	            LateralHumerus = '".$LateralHumerus."',
	            APview  = '".$APview."',
	            LateralElbow = '".$LateralElbow."',
	            XrayOrderedNotes  = '".$XrayOrderedNotes."',
	            Supracondylar = '".$Supracondylar."',
	            SingleColumn = '".$SingleColumn."',
	            Bicolumnfracture = '".$Bicolumnfracture."',
	            Coronalshear = '".$Coronalshear."',
	            Comminution = '".$Comminution."',
	            DisUndis = '".$DisUndis."',
	            OpenFructure = '".$OpenFructure."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //       $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description:<br>
			// 		".$SingleColumn."
			// 		".$Bicolumnfracture."
			// 		".$Coronalshear."
			// 		".$Comminution."
			// 		Open Fracture Descreiption: `".$DisUndis."` ".$OpenFructure."<br>
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiPREdistalhumerusTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1879 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREdistalhumerusTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1880 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREdistalhumerusTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1881 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTdistalhumerusTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1882 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTdistalhumerusTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1883 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTdistalhumerusTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1884 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

	//End- Distal Humerus Trauma



	//Elbow Trauma

	private function apiElbowTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaelbow_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaelbow_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaelbow_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpElbowTraumaInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumaelbow_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {
		    	$querysel = "SELECT * FROM zipad_traumaelbow_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumaelbow_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumaelbow_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}

		    }
		  //             $wfp = fopen("humeral.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}


		private function apiElbowTrumaXrayFindings(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaelbow_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaelbow_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaelbow_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateElbowTraumaXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $APelbow  = (string)$IntraItem['APelbow'];
            $APLateralElbow = (string)$IntraItem['APLateralElbow'];
            $XrayOrderedNotes = (string)$IntraItem['XrayOrderedNotes'];
            $Olecranon = (string)$IntraItem['Olecranon'];
            $Radialhead = (string)$IntraItem['Radialhead'];
            $Coronoidprocess = (string)$IntraItem['Coronoidprocess'];
            $AnteriorDislocation = (string)$IntraItem['AnteriorDislocation'];
            $PosteriorDislocation = (string)$IntraItem['PosteriorDislocation'];
            $FractureDislocation = (string)$IntraItem['FractureDislocation'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumaelbow_xray SET
				pxrid = '".$pxrid."',
				APelbow = '".$APelbow."',
	            APLateralElbow = '".$APLateralElbow."',
	            XrayOrderedNotes = '".$XrayOrderedNotes."',
	            Olecranon = '".$Olecranon."',
	            Radialhead = '".$Radialhead."',
	            Coronoidprocess = '".$Coronoidprocess."',
	            AnteriorDislocation = '".$AnteriorDislocation."',
	            PosteriorDislocation = '".$PosteriorDislocation."',
	            FractureDislocation = '".$FractureDislocation."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //       $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			// if($Olecranon)
			// {
			// 	$Olecranon = $Olecranon."<br>";
			// }
			// if($Radialhead)
			// {
			// 	$Radialhead = $Radialhead."<br>";
			// }
			// if($Coronoidprocess)
			// {
			// 	$Coronoidprocess = $Coronoidprocess."<br>";
			// }
			// if($AnteriorDislocation)
			// {
			// 	$AnteriorDislocation = $AnteriorDislocation."<br>";
			// }
			// if($PosteriorDislocation)
			// {
			// 	$PosteriorDislocation = $PosteriorDislocation."<br>";
			// }
			// if($FractureDislocation)
			// {
			// 	$FractureDislocation = $FractureDislocation."<br>";
			// }
			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description:<br>
			// 		".$Olecranon."
			// 		".$Radialhead."
			// 		".$Coronoidprocess."
			// 		".$AnteriorDislocation."
			// 		".$PosteriorDislocation."
			// 		".$FractureDislocation."
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPREElbowTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1885 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREElbowTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1886 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREElbowTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1887 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTElbowTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1888 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTElbowTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1889 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTElbowTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1890 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

	//End-Elbow Trauma 
	


	//ForeArm Trauma
		private function apiForearmTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaforearm_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaforearm_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaforearm_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpForearmTraumaInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumaforearm_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {

		    	$querysel = "SELECT * FROM zipad_traumaforearm_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumaforearm_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumaforearm_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}

		    }
		  //             $wfp = fopen("humeral.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}

		private function apiForearmTrumaXrayFindings(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaforearm_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaforearm_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaforearm_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateForearmTraumaXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $APForearm  = (string)$IntraItem['APForearm'];
            $LateralForearm = (string)$IntraItem['LateralForearm'];
            $XrayOrderedNotes = (string)$IntraItem['XrayOrderedNotes'];
            $FractureLocation = (string)$IntraItem['FractureLocation'];
            $FractureProx = (string)$IntraItem['FractureProx'];
            $Comminuted = (string)$IntraItem['Comminuted'];
            $Segmental = (string)$IntraItem['Segmental'];
            $Displacement = (string)$IntraItem['Displacement'];
            $MedialAngulation = (string)$IntraItem['MedialAngulation'];
            $MedialAnguDegree = (string)$IntraItem['MedialAnguDegree'];
            $AnteriorAngulation = (string)$IntraItem['AnteriorAngulation'];
            $AnteriorAnguDegree = (string)$IntraItem['AnteriorAnguDegree'];
            $Rotational = (string)$IntraItem['Rotational'];
            $Fractureconfiguration = (string)$IntraItem['Fractureconfiguration'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumaforearm_xray SET
				pxrid = '".$pxrid."',
				APForearm = '".$APForearm."',
	            LateralForearm = '".$LateralForearm."',
	            XrayOrderedNotes = '".$XrayOrderedNotes."',
	            FractureLocation = '".$FractureLocation."',
            	FractureProx = '".$FractureProx."',
	            Comminuted = '".$Comminuted."',
	            Segmental = '".$Segmental."',
	            Displacement = '".$Displacement."',
	            MedialAngulation = '".$MedialAngulation."',
	            MedialAnguDegree = '".$MedialAnguDegree."',
	            AnteriorAngulation = '".$AnteriorAngulation."',
	            AnteriorAnguDegree = '".$AnteriorAnguDegree."',
	            Rotational = '".$Rotational."',
	            Fractureconfiguration = '".$Fractureconfiguration."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //       $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			
			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description:<br>
			// 		Comminuted: ".$Comminuted."<br>
			// 		Segmental: ".$Segmental."<br>
			// 		Displacement: ".$Displacement."<br>
			// 		Angulation: ".$Angulation."<br>
			// 		AngulationDegree: ".$AngulationDegree."<br>
			// 		Rotational: ".$Rotational."<br>
			// 		Fracture Configuration: ".$Fractureconfiguration."<br>
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiPREForearmTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1891 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREForearmTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1892 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREForearmTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1893 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTForearmTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1894 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTForearmTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1895 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTForearmTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1896 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

	//End-Forearm Trauma


	//Wrist Hand Trauma
		private function apiWristhandTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumawristhand_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumawristhand_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumawristhand_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpWristhandTraumaInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumawristhand_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {

		    	$querysel = "SELECT * FROM zipad_traumawristhand_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumawristhand_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumawristhand_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}

		    }
		  //             $wfp = fopen("humeral.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}


		private function apiWristhandTrumaXrayFindings(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumawristhand_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumawristhand_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumawristhand_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateWristhandTraumaXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $APwrist = (string)$IntraItem['APwrist'];
            $APlateral = (string)$IntraItem['APlateral'];
            $Oblique = (string)$IntraItem['Oblique'];
            $XrayOrderedNotes = (string)$IntraItem['XrayOrderedNotes'];
            $FractureLocation = (string)$IntraItem['FractureLocation'];
            $Comminuted = (string)$IntraItem['Comminuted'];
            $Displacement = (string)$IntraItem['Displacement'];
            $DisplaceVolDor = (string)$IntraItem['DisplaceVolDor'];
            $Radialheight = (string)$IntraItem['Radialheight'];
            $RadialheightMeasur = (string)$IntraItem['RadialheightMeasur'];
            $Radialincli = (string)$IntraItem['Radialincli'];
            $RadialincliMeasur = (string)$IntraItem['RadialincliMeasur'];
            $Volartilt = (string)$IntraItem['Volartilt'];
            $VolartiltMeasur = (string)$IntraItem['VolartiltMeasur'];
            $Dorsaltilt = (string)$IntraItem['Dorsaltilt'];
            $DorsaltiltMeasur = (string)$IntraItem['DorsaltiltMeasur'];
            $Stepoff = (string)$IntraItem['Stepoff'];
            $StepoffMesur = (string)$IntraItem['StepoffMesur'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumawristhand_xray SET
				pxrid = '".$pxrid."',
				APwrist = '".$APwrist."',
	            APlateral = '".$APlateral."',
	            Oblique = '".$Oblique."',
	            XrayOrderedNotes = '".$XrayOrderedNotes."',
	            FractureLocation = '".$FractureLocation."',
	            Comminuted = '".$Comminuted."',
	            Displacement = '".$Displacement."',
	            DisplaceVolDor = '".$DisplaceVolDor."',
	            Radialheight = '".$Radialheight."',
	            RadialheightMeasur = '".$RadialheightMeasur."',
	            Radialincli = '".$Radialincli."',
	            RadialincliMeasur = '".$RadialincliMeasur."',
	            Volartilt = '".$Volartilt."',
	            VolartiltMeasur = '".$VolartiltMeasur."',
	            Dorsaltilt = '".$Dorsaltilt."',
	            DorsaltiltMeasur = '".$DorsaltiltMeasur."',
	            Stepoff = '".$Stepoff."',
	            StepoffMesur = '".$StepoffMesur."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  // 		$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			
			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description:<br>
			// 		Comminuted: ".$Comminuted."<br>
			// 		Displacement: ".$Displacement."<br>
			// 		Radialheight: ".$Radialheight."<br>
			// 		Radial height Measurement: ".$RadialheightMeasur."<br>
			// 		Volar tilt: ".$Volartilt."<br>
			// 		Volar tilt Measurement: ".$VolartiltMeasur."<br>
			// 		Dorsal tilt: ".$Dorsaltilt."<br>
			// 		Dorsal tilt Measurement: ".$DorsaltiltMeasur."<br>
			// 		Step off: ".$Stepoff."<br>
			// 		Step off Measurement: ".$StepoffMesur."<br>
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPREWristjointTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1897 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREWristjointTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1898 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREWristjointTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1899 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTWristjointTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1900 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTWristjointTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1901 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTWristjointTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1902 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

	//END - Wrist Hand Trauma

	

	//Hand Trauma
		private function apiHandTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaHand_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaHand_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaHand_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpHandTraumaInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Laterality= (string)$IntraItem['Laterality'];
            $Deformity= (string)$IntraItem['Deformity'];
            $Skintenting= (string)$IntraItem['Skintenting'];
            $SensoryDeficit= (string)$IntraItem['SensoryDeficit'];
            $SensoryDeficitLevel= (string)$IntraItem['SensoryDeficitLevel'];
            $OpenWound= (string)$IntraItem['OpenWound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $DestalpulseRadial= (string)$IntraItem['DestalpulseRadial'];
            $DestalpulseUlnar= (string)$IntraItem['DestalpulseUlnar'];
            $Others= (string)$IntraItem['Others'];
            $Others = str_replace("'", "`", $Others);

            if(!$Laterality)
            {
             
		    	$query = "UPDATE zipad_traumaHand_inspection SET
				pxrid = '".$pxrid."',
				Deformity = '".$Deformity."',
				Skintenting = '".$Skintenting."',
				SensoryDeficit = '".$SensoryDeficit."',
				SensoryDeficitLevel = '".$SensoryDeficitLevel."',
				OpenWound = '".$OpenWound."',
				GustiloClass = '".$GustiloClass."',
				DestalpulseRadial = '".$DestalpulseRadial."',
				DestalpulseUlnar = '".$DestalpulseUlnar."',
				Others = '".$Others."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
		    {
		    	$querysel = "SELECT * FROM zipad_traumaHand_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumaHand_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
		       		WHERE ClinixRID = ".$ClinixRIDdata."";
		       		
			        
		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumaHand_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
		    	
		    }
		  //             $wfp = fopen("humeral.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

		}


		private function apiHandTrumaXrayFindings(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumaHand_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumaHand_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumaHand_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateHandTraumaXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $APhand = (string)$IntraItem['APhand'];
            $LateralHand = (string)$IntraItem['LateralHand'];
            $Oblique = (string)$IntraItem['Oblique'];
            $XrayOrderedNotes = (string)$IntraItem['XrayOrderedNotes'];
            $CarpalBone = (string)$IntraItem['CarpalBone'];
            $Metacarpal = (string)$IntraItem['Metacarpal'];
            $MetacarpalSpec = (string)$IntraItem['MetacarpalSpec'];
            $Phalanges = (string)$IntraItem['Phalanges'];
            $PhalangesSpec = (string)$IntraItem['PhalangesSpec'];
            $Comminuted = (string)$IntraItem['Comminuted'];
            $Displacement = (string)$IntraItem['Displacement'];
            $Sublaxation = (string)$IntraItem['Sublaxation'];
            $Fracture = (string)$IntraItem['Fracture'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumaHand_xray SET
				pxrid = '".$pxrid."',
				APhand = '".$APhand."',
	            LateralHand = '".$LateralHand."',
	            Oblique = '".$Oblique."',
	            XrayOrderedNotes = '".$XrayOrderedNotes."',
	            CarpalBone = '".$CarpalBone."',
	            Metacarpal = '".$Metacarpal."',
	            MetacarpalSpec = '".$MetacarpalSpec."',
	            Phalanges = '".$Phalanges."',
	            PhalangesSpec = '".$PhalangesSpec."',
	            Comminuted = '".$Comminuted."',
	            Displacement = '".$Displacement."',
	            Sublaxation = '".$Sublaxation."',
	            Fracture = '".$Fracture."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  // 		$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			
			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Description:<br>
			// 		Comminuted: ".$Comminuted."<br>
			// 		Displacement: ".$Displacement."<br>
			// 		Radial inclination: ".$Radialheight."<br>
			// 		Radial inclination Measurement: ".$RadialIncliMesure."<br>
			// 		Volar tilt: ".$Volartilt."<br>
			// 		Volar tilt Measurement: ".$VolartiltMesure."<br>
			// 		Dorsal tilt: ".$Dorsaltilt."<br>
			// 		Dorsal tilt Measurement: ".$DorsaltiltMeasure."<br>
			// 		Step off: ".$Stepoff."<br>
			// 		Step off Measurement: ".$StepoffMeasur."<br>
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '".$all."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPREHandTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1903 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREHandTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1904 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREHandTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1905 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTHandTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1906 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTHandTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1907 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTHandTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1908 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


	//End - Hand Trauma


	//Pelvic Hip Trauma

	private function apiPelvicHipTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumapelvicacetabulum_inspection WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumapelvicacetabulum_inspection SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumapelvicacetabulum_inspection WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}




		private function apiUpdatePelvicHipInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $AppDeformed = (string)$IntraItem['AppDeformed'];
            $AppHematoma = (string)$IntraItem['AppHematoma'];
            $AppAbra = (string)$IntraItem['AppAbra'];
            $Openwound= (string)$IntraItem['Openwound'];
            $GustiloClass= (string)$IntraItem['GustiloClass'];
            $TenPressure= (string)$IntraItem['TenPressure'];
            $MotorFunction= (string)$IntraItem['MotorFunction'];
            $LegExternal= (string)$IntraItem['LegExternal'];
            $LegInternally= (string)$IntraItem['LegInternally'];
            $Popliteal= (string)$IntraItem['Popliteal'];
            $Dorsalispedis= (string)$IntraItem['Dorsalispedis'];
            $Head= (string)$IntraItem['Head'];
            $Chest= (string)$IntraItem['Chest'];
            $AbdominalInjury= (string)$IntraItem['AbdominalInjury'];
            $UrogentInjury= (string)$IntraItem['UrogentInjury'];
            $ThoracicInjury= (string)$IntraItem['ThoracicInjury'];
            $Laterality= (string)$IntraItem['Laterality'];

              
            if(!$Laterality)
            {
		    	$query = "UPDATE zipad_traumapelvicacetabulum_inspection SET
				pxrid = '".$pxrid."',
				AppDeformed = '".$AppDeformed."',
	            AppHematoma = '".$AppHematoma."',
	            AppAbra = '".$AppAbra."',
	            Openwound = '".$Openwound."',
	            GustiloClass = '".$GustiloClass."',
	            TenPressure = '".$TenPressure."',
	            MotorFunction = '".$MotorFunction."',
	            LegExternal = '".$LegExternal."',
	            LegInternally = '".$LegInternally."',
	            Popliteal = '".$Popliteal."',
	            Dorsalispedis = '".$Dorsalispedis."',
	            Head = '".$Head."',
	            Chest = '".$Chest."',
	            AbdominalInjury = '".$AbdominalInjury."',
	            UrogentInjury = '".$UrogentInjury."',
	            ThoracicInjury = '".$ThoracicInjury."'
		        WHERE ClinixRID = ".$clinix."";
		   }else
		   {
			   	$querysel = "SELECT * FROM zipad_traumapelvicacetabulum_inspection
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_traumapelvicacetabulum_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT into zipad_traumapelvicacetabulum_inspection SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
		   }
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPelvicHipTrumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_traumapelvicacetabulum_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_traumapelvicacetabulum_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_traumapelvicacetabulum_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdatePelvicHipXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $PelvisAP = (string)$IntraItem['PelvisAP'];
            $Inletview = (string)$IntraItem['Inletview'];
            $Outletview = (string)$IntraItem['Outletview'];
            $JudetR = (string)$IntraItem['JudetR'];
            $JudetL = (string)$IntraItem['JudetL'];
            $Pelvicstressview = (string)$IntraItem['Pelvicstressview'];
            $XrayOrder = (string)$IntraItem['XrayOrder'];
            $RadioNorm = (string)$IntraItem['RadioNorm'];
            $FractureIlium = (string)$IntraItem['FractureIlium'];
            $FractureIshium = (string)$IntraItem['FractureIshium'];
            $FracturePubis = (string)$IntraItem['FracturePubis'];
            $FractureSacJoint = (string)$IntraItem['FractureSacJoint'];
            $Acetabularfracture = (string)$IntraItem['Acetabularfracture'];
            $AnteriorWall = (string)$IntraItem['AnteriorWall'];
            $PosteriorColumn = (string)$IntraItem['PosteriorColumn'];
            $Posteriorwall = (string)$IntraItem['Posteriorwall'];
            $Transverse = (string)$IntraItem['Transverse'];
            $Posteriorcolumnwall = (string)$IntraItem['Posteriorcolumnwall'];
            $Transverseposteriorwall = (string)$IntraItem['Transverseposteriorwall'];
            $AnteriorColumn = (string)$IntraItem['AnteriorColumn'];
            $Tshaped = (string)$IntraItem['Tshaped'];
            $Bothcolumn = (string)$IntraItem['Bothcolumn'];
            $FractureSacroiliacJoint = (string)$IntraItem['FractureSacroiliacJoint'];
            $DislocationSacroiliacJoint = (string)$IntraItem['DislocationSacroiliacJoint'];
            $Symphysis = (string)$IntraItem['Symphysis'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_traumapelvicacetabulum_xray SET
				pxrid = '".$pxrid."',
				PelvisAP = '".$PelvisAP."',
	            Inletview = '".$Inletview."',
	            Outletview = '".$Outletview."',
	            JudetR = '".$JudetR."',
	            JudetL = '".$JudetL."',
	            Pelvicstressview = '".$Pelvicstressview."',
	            XrayOrder = '".$XrayOrder."',
	            RadioNorm = '".$RadioNorm."',
	            FractureIlium = '".$FractureIlium."',
	            FractureIshium = '".$FractureIshium."',
	            FracturePubis = '".$FracturePubis."',
	            FractureSacJoint = '".$FractureSacJoint."',
	            Acetabularfracture = '".$Acetabularfracture."',
	            AnteriorWall = '".$AnteriorWall."',
	            PosteriorColumn = '".$PosteriorColumn."',
	            Posteriorwall = '".$Posteriorwall."',
	            Transverse = '".$Transverse."',
	            Posteriorcolumnwall = '".$Posteriorcolumnwall."',
	            Transverseposteriorwall = '".$Transverseposteriorwall."',
	            AnteriorColumn = '".$AnteriorColumn."',
	            Tshaped = '".$Tshaped."',
	            Bothcolumn = '".$Bothcolumn."',
	            FractureSacroiliacJoint = '".$FractureSacroiliacJoint."',
	            DislocationSacroiliacJoint = '".$DislocationSacroiliacJoint."',
	            Symphysis = '".$Symphysis."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// if($Acetabularfracture)
			// {
			// 	$Acetabularfracture = $Acetabularfracture."<br>";
			// }
			// if($AnteriorWall)
			// {
			// 	$AnteriorWall = $AnteriorWall."<br>";
			// }
			// if($PosteriorColumn)
			// {
			// 	$PosteriorColumn = $PosteriorColumn."<br>";
			// }
			// if($Posteriorwall)
			// {
			// 	$Posteriorwall = $Posteriorwall."<br>";
			// }
			// if($Transverse)
			// {
			// 	$Transverse = $Transverse."<br>";
			// }
			// if($Posteriorcolumnwall)
			// {
			// 	$Posteriorcolumnwall = $Posteriorcolumnwall."<br>";
			// }
			// if($Transverseposteriorwall)
			// {
			// 	$Transverseposteriorwall = $Transverseposteriorwall."<br>";
			// }
			// if($AnteriorColumn)
			// {
			// 	$AnteriorColumn = $AnteriorColumn."<br>";
			// }
			// if($Tshaped)
			// {
			// 	$Tshaped = $Tshaped."<br>";
			// }
			// if($Bothcolumn)
			// {
			// 	$Bothcolumn = $Bothcolumn."<br>";
			// }

			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Ilium: ".$FractureIlium."<br>
			// 		Fracture Ishium: ".$FractureIshium."<br>
			// 		Fracture Pubis: ".$FracturePubis." <br>
			// 		Fracture Sacro-iliac Joint: ".$FractureSacJoint." <br><br>
			// 		Acetabular fracture:<br>
			// 		".$Acetabularfracture."
			// 		".$AnteriorWall."
			// 		".$PosteriorColumn."
			// 		".$Posteriorwall."
			// 		".$Transverse."
			// 		".$Posteriorcolumnwall."
			// 		".$Transverseposteriorwall."
			// 		".$AnteriorColumn."
			// 		".$Tshaped."
			// 		".$Bothcolumn."
			// 		<br><br>
			// 		Fracture Sacro-iliac Joint: ".$FractureSacroiliacJoint."<br>
			// 		Dislocation Sacro-Iliac Joint: ".$DislocationSacroiliacJoint."<br>
			// 		Symphysis Pubis Seperation: ".$Symphysis."<br>
			// 		Others: ".$XrayFindingsOthers;

			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '". $all ."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiPREPelvicacetabulumTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1910 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREPelvicacetabulumTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1911 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREPelvicacetabulumTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1912 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTPelvicacetabulumTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1913 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTPelvicacetabulumTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1914 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTPelvicacetabulumTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1915 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


	//End - Pelvic Hip Trauma

	//Trauma Hip Joint

		private function apiHipJointTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_hipjoint_appearance WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_hipjoint_appearance SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_hipjoint_appearance WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateHipJointInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Fracture = (string)$IntraItem['Fracture'];
            $Dislocation = (string)$IntraItem['Dislocation'];
            $AppNormal = (string)$IntraItem['AppNormal'];
            $AppSwollen = (string)$IntraItem['AppSwollen'];
            $AppDeformed = (string)$IntraItem['AppDeformed'];
            $AppAbrasions = (string)$IntraItem['AppAbrasions'];
            $AppExternally = (string)$IntraItem['AppExternally'];
            $AppInternally = (string)$IntraItem['AppInternally'];
            $AppShortened = (string)$IntraItem['AppShortened'];
            $AppOpenWound = (string)$IntraItem['AppOpenWound'];
            $GustiloClass = (string)$IntraItem['GustiloClass'];
            $Popliteal= (string)$IntraItem['Popliteal'];
            $Dorsalis= (string)$IntraItem['Dorsalis'];
            $Head= (string)$IntraItem['Head'];
            $Chest= (string)$IntraItem['Chest'];
            $Thoracic= (string)$IntraItem['Thoracic'];
            $Abdominal= (string)$IntraItem['Abdominal'];
            $Urogenital= (string)$IntraItem['Urogenital'];
            $Laterality= (string)$IntraItem['Laterality'];
            $LocationAffect= (string)$IntraItem['LocationAffect'];
            
            if(!$Laterality)
            {
		    	$query = "UPDATE zipad_trauma_hipjoint_appearance SET
				pxrid = '".$pxrid."',
				LocationAffect = '".$LocationAffect."',
				Fracture = '".$Fracture."',
				Dislocation = '".$Dislocation."',
	           	AppNormal = '".$AppNormal."',
	            AppSwollen = '".$AppSwollen."',
	            AppDeformed = '".$AppDeformed."',
	            AppAbrasions = '".$AppAbrasions."',
	            AppExternally = '".$AppExternally."',
	            AppInternally = '".$AppInternally."',
	            AppShortened = '".$AppShortened."',
	            AppOpenWound = '".$AppOpenWound."',
	            GustiloClass = '".$GustiloClass."',
	            Popliteal = '".$Popliteal."',
	            Dorsalis = '".$Dorsalis."',
	            Head = '".$Head."',
	            Chest = '".$Chest."',
	            Thoracic = '".$Thoracic."',
	            Abdominal = '".$Abdominal."',
	            Urogenital = '".$Urogenital."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);
			}else
			{
				$querysel = "SELECT * FROM zipad_trauma_hipjoint_appearance
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_trauma_hipjoint_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT INTO zipad_trauma_hipjoint_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
			}

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiHipJointTrumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_hipjoint_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_hipjoint_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_hipjoint_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateHipJointXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $PelvisAP = (string)$IntraItem['PelvisAP'];
            $FemurAPL = (string)$IntraItem['FemurAPL'];
            $HipCrossTable = (string)$IntraItem['HipCrossTable'];
            $XrayOrder = (string)$IntraItem['XrayOrder'];
            $Transverse = (string)$IntraItem['Transverse'];
            $Spiral = (string)$IntraItem['Spiral'];
            $Oblique = (string)$IntraItem['Oblique'];
            $Comminuted = (string)$IntraItem['Comminuted'];
            $Segmental = (string)$IntraItem['Segmental'];
            $FemoralneckFracture = (string)$IntraItem['FemoralneckFracture'];
            $Cervico = (string)$IntraItem['Cervico'];
            $trochanteric = (string)$IntraItem['trochanteric'];
            $Intertroch = (string)$IntraItem['Intertroch'];
            $Subtrochanteric = (string)$IntraItem['Subtrochanteric'];
            $AntPureDislocation = (string)$IntraItem['AntPureDislocation'];
            $AntAcetabularFracture = (string)$IntraItem['AntAcetabularFracture'];
            $AntFemoralHeadFracture = (string)$IntraItem['AntFemoralHeadFracture'];
            $PostPureDislocation = (string)$IntraItem['PostPureDislocation'];
            $PostAnteriorFracture = (string)$IntraItem['PostAnteriorFracture'];
            $PostFemoralFracture = (string)$IntraItem['PostFemoralFracture'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_trauma_hipjoint_xray SET
				pxrid = '".$pxrid."',
				PelvisAP = '".$PelvisAP."',
	            FemurAPL = '".$FemurAPL."',
	            HipCrossTable = '".$HipCrossTable."',
	            XrayOrder = '".$XrayOrder."',
	            Transverse = '".$Transverse."',
	            Spiral = '".$Spiral."',
	            Oblique = '".$Oblique."',
	            Comminuted = '".$Comminuted."',
	            Segmental = '".$Segmental."',
	            FemoralneckFracture = '".$FemoralneckFracture."',
	            Cervico = '".$Cervico."',
	            trochanteric = '".$trochanteric."',
	            Intertroch = '".$Intertroch."',
	            Subtrochanteric = '".$Subtrochanteric."',
	            AntPureDislocation = '".$AntPureDislocation."',
	            AntAcetabularFracture = '".$AntAcetabularFracture."',
	            AntFemoralHeadFracture = '".$AntFemoralHeadFracture."',
	            PostPureDislocation = '".$PostPureDislocation."',
	            PostAnteriorFracture = '".$PostAnteriorFracture."',
	            PostFemoralFracture = '".$PostFemoralFracture."',
	            PostFemoralFracture = '".$PostFemoralFracture."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// if($Transverse)
			// {
			// 	$Transverse = $Transverse."<br>";
			// }
			// if($Spiral)
			// {
			// 	$Spiral = $Spiral."<br>";
			// }
			// if($Oblique)
			// {
			// 	$Oblique = $Oblique."<br>";
			// }
			// if($Comminuted)
			// {
			// 	$Comminuted = $Comminuted."<br>";
			// }
			// if($Segmental)
			// {
			// 	$Segmental = $Segmental."<br>";
			// }
			

			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture Configuration: <br>
			// 		".$Transverse."
			// 		".$Spiral."
			// 		".$Oblique."
			// 		".$Comminuted."
			// 		".$Segmental."
			// 		Femoral neck Fracture (Garden): ".$FemoralneckFracture."<br>
			// 		Cervico-Trochanteric Fracture: ".$Cervico."<br>
			// 		Inter - trochanteric Fracture: ".$trochanteric."<br>
			// 		Intertroch - Subtrochanteric fracture: ".$Intertroch."<br>
			// 		Subtrochanteric: ".$Subtrochanteric."<br>
			// 		Anterior Dislocation: ".$AntPureDislocation." ".$AntAcetabularFracture." ".$AntFemoralHeadFracture."<br>
			// 		Posterior Dislocation: ".$AntPureDislocation." ".$AntAcetabularFracture." ".$AntFemoralHeadFracture."<br>
			// 		Others: ".$XrayFindingsOthers;
	           
	           
			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '". $all ."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPREHipjointTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1916 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREHipjointTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1917 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREHipjointTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1918 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTHipjointTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1919 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTHipjointTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1920 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTHipjointTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1921 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


//End - Trauma Hip Joint

	//Trauma Femor Shaft

		private function apiFemorShaftTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_femoralshaft_appearance WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_femoralshaft_appearance SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_femoralshaft_appearance WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateFemorShaftInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Fracture = (string)$IntraItem['Fracture'];
            $Dislocation = (string)$IntraItem['Dislocation'];
            $AppNormal = (string)$IntraItem['AppNormal'];
            $AppSwollen = (string)$IntraItem['AppSwollen'];
            $AppDeformed = (string)$IntraItem['AppDeformed'];
            $AppAbrasions = (string)$IntraItem['AppAbrasions'];
            $AppHeamatoma = (string)$IntraItem['AppHeamatoma'];
            $AppShortened = (string)$IntraItem['AppShortened'];
            $AppOpenWound = (string)$IntraItem['AppOpenWound'];
            $GustiloClass = (string)$IntraItem['GustiloClass'];
            $Popliteal= (string)$IntraItem['Popliteal'];
            $Dorsalis= (string)$IntraItem['Dorsalis'];
            $Head= (string)$IntraItem['Head'];
            $Chest= (string)$IntraItem['Chest'];
            $Thoracic= (string)$IntraItem['Thoracic'];
            $Abdominal= (string)$IntraItem['Abdominal'];
            $Urogenital= (string)$IntraItem['Urogenital'];
            $Laterality= (string)$IntraItem['Laterality'];
            
            if(!$Laterality){ 
		    	$query = "UPDATE zipad_trauma_femoralshaft_appearance SET
				pxrid = '".$pxrid."',
	            AppSwollen = '".$AppSwollen."',
	            AppDeformed = '".$AppDeformed."',
	            AppAbrasions = '".$AppAbrasions."',
	            AppHeamatoma = '".$AppHeamatoma."',
	            AppShortened = '".$AppShortened."',
	            AppOpenWound = '".$AppOpenWound."',
	            GustiloClass = '".$GustiloClass."',
	            Popliteal = '".$Popliteal."',
	            Dorsalis = '".$Dorsalis."',
	            Head = '".$Head."',
	            Chest = '".$Chest."',
	            Thoracic = '".$Thoracic."',
	            Abdominal = '".$Abdominal."',
	            Urogenital = '".$Urogenital."'
		        WHERE ClinixRID = ".$clinix."";

		    }else
			{
				$querysel = "SELECT * FROM zipad_trauma_femoralshaft_appearance
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_trauma_femoralshaft_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT INTO zipad_trauma_femoralshaft_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
			}
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiFemoralShaftTrumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_femoralshaft_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_femoralshaft_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_femoralshaft_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateFemorshaftXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
           	$XrayOrders = (string)$IntraItem['XrayOrders'];
           	$PelvisAP = (string)$IntraItem['PelvisAP'];
            $FemurAPL = (string)$IntraItem['FemurAPL'];
            $KneeAPL = (string)$IntraItem['KneeAPL'];
            $FractureDis = (string)$IntraItem['FractureDis'];
            $Fracture = (string)$IntraItem['Fracture'];
            $Configuration = (string)$IntraItem['Configuration'];
            $ConfigProximal = (string)$IntraItem['ConfigProximal'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_trauma_femoralshaft_xray SET
				pxrid = '".$pxrid."',
				XrayOrders = '".$XrayOrders."',
				PelvisAP = '".$PelvisAP."',
	            FemurAPL = '".$FemurAPL."',
	            KneeAPL = '".$KneeAPL."',
	            FractureDis = '".$FractureDis."',
	            Fracture = '".$Fracture."',
	            Configuration = '".$Configuration."',
	            ConfigProximal = '".$ConfigProximal."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);



			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture: ".$FractureDis." ".$Fracture."<br>
			// 		Configuration: ".$Configuration." ".$ConfigProximal." ".$AntFemoralHeadFracture."<br>
			// 		Others: ".$XrayFindingsOthers;
	           
	           
			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '". $all ."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPREFemoralshaftTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1922 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREFemoralshaftTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1923 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREFemoralshaftTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1924 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTFemoralshaftTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1925 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTFemoralshaftTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1926 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTFemoralshaftTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1927 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}
//End - Trauma Femor Shaft

		

//Trauma Knee Joint

		private function apiKneeJointTrumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_kneejoint_appearance WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_kneejoint_appearance SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_kneejoint_appearance WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateKneeJointInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Fracture = (string)$IntraItem['Fracture'];
            $Dislocation = (string)$IntraItem['Dislocation'];
            $AppNormal = (string)$IntraItem['AppNormal'];
            $AppSwollen = (string)$IntraItem['AppSwollen'];
            $AppDeformed = (string)$IntraItem['AppDeformed'];
            $AppAbrasions = (string)$IntraItem['AppAbrasions'];
            $AppHeamatoma = (string)$IntraItem['AppHeamatoma'];
            $AppExternally = (string)$IntraItem['AppExternally'];
            $AppInternally = (string)$IntraItem['AppInternally'];
            $AppShortened = (string)$IntraItem['AppShortened'];
            $AppOpenWound = (string)$IntraItem['AppOpenWound'];
            $GustiloClass = (string)$IntraItem['GustiloClass'];
            $LegExternally = (string)$IntraItem['LegExternally'];
            $LegInternally = (string)$IntraItem['LegInternally'];
            $Dorsalis= (string)$IntraItem['Dorsalis'];
            $Head= (string)$IntraItem['Head'];
            $Chest= (string)$IntraItem['Chest'];
            $Thoracic= (string)$IntraItem['Thoracic'];
            $Abdominal= (string)$IntraItem['Abdominal'];
            $Urogenital= (string)$IntraItem['Urogenital'];
            $Laterality= (string)$IntraItem['Laterality'];
            $LocationAffect= (string)$IntraItem['LocationAffect'];

            if(!$Laterality)
            { 
		    	$query = "UPDATE zipad_trauma_kneejoint_appearance SET
				pxrid = '".$pxrid."',
				Fracture = '".$Fracture."',
				Dislocation = '".$Dislocation."',
	           	AppNormal = '".$AppNormal."',
	            AppSwollen = '".$AppSwollen."',
	            AppDeformed = '".$AppDeformed."',
	            AppAbrasions = '".$AppAbrasions."',
	            AppHeamatoma = '".$AppHeamatoma."',
	            AppExternally = '".$AppExternally."',
	            AppInternally = '".$AppInternally."',
	            AppShortened = '".$AppShortened."',
	            AppOpenWound = '".$AppOpenWound."',
	            GustiloClass = '".$GustiloClass."',
	            LegExternally = '".$LegExternally."',
            	LegInternally = '".$LegInternally."',
	            Dorsalis = '".$Dorsalis."',
	            Head = '".$Head."',
	            Chest = '".$Chest."',
	            Thoracic = '".$Thoracic."',
	            Abdominal = '".$Abdominal."',
	            Urogenital = '".$Urogenital."',
	            LocationAffect = '".$LocationAffect."'
		        WHERE ClinixRID = ".$clinix."";
		 	}else
			{
				$querysel = "SELECT * FROM zipad_trauma_kneejoint_appearance
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_trauma_kneejoint_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT INTO zipad_trauma_kneejoint_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
		  //   	$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);
			}

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiKneeJointTrumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_kneejoint_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_kneejoint_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_kneejoint_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateKneeJointXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $PelvisAP = (string)$IntraItem['PelvisAP'];
            $KneeAP = (string)$IntraItem['KneeAP'];
            $XrayOrder = (string)$IntraItem['XrayOrder'];
            $DistaDisplace = (string)$IntraItem['DistaDisplace'];
            $DistalFracture = (string)$IntraItem['DistalFracture'];
            $TibialDisplace = (string)$IntraItem['TibialDisplace'];
            $TibialFracture = (string)$IntraItem['TibialFracture'];
            $PatellarDisplace = (string)$IntraItem['PatellarDisplace'];
            $PatellarFracture = (string)$IntraItem['PatellarFracture'];
            $PatellarDislocation = (string)$IntraItem['PatellarDislocation'];
            $AnteriorDislocation = (string)$IntraItem['AnteriorDislocation'];
            $PosteriorDislocation = (string)$IntraItem['PosteriorDislocation'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_trauma_kneejoint_xray SET
				pxrid = '".$pxrid."',
				PelvisAP = '".$PelvisAP."',
	            KneeAP = '".$KneeAP."',
	            XrayOrder = '".$XrayOrder."',
	           	DistaDisplace = '".$DistaDisplace."',
	            DistalFracture = '".$DistalFracture."',
	            TibialDisplace = '".$TibialDisplace."',
	            TibialFracture = '".$TibialFracture."',
	            PatellarDisplace = '".$PatellarDisplace."',
	            PatellarFracture = '".$PatellarFracture."',
	            PatellarDislocation = '".$PatellarDislocation."',
	            AnteriorDislocation = '".$AnteriorDislocation."',
	            PosteriorDislocation = '".$PosteriorDislocation."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Distal Femur Fracture: ".$DistaDisplace." ".$DistalFracture."<br>
			// 		Tibial plateau fracture: ".$TibialDisplace." ".$TibialFracture."<br>
			// 		Patellar Fracture: ".$PatellarDisplace." ".$PatellarFracture."<br>
			// 		KNEE Dislocation: <br>
			// 		Anterior Dislocation: ".$AnteriorDislocation."<br>
			// 		Posterior Dislocation: ".$PosteriorDislocation."<br>
			// 		Others: ".$XrayFindingsOthers;
	           
	           
			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '". $all ."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiPREKneejointTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1929 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREKneejointTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1930 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREKneejointTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1931 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTKneejointTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1932 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTKneejointTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1933 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTKneejointTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1934 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

//End - Trauma Knee Joint
	
//Trauma Tibia Shaft
		private function apiaTibiaShaftTraumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_tibialshaft_appearance WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_tibialshaft_appearance SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_tibialshaft_appearance WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpdateTibiaShaftInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $DistalFemur = (string)$IntraItem['DistalFemur'];
            $TibaPlateau = (string)$IntraItem['TibaPlateau'];
            $Patella = (string)$IntraItem['Patella'];
            $Dislocation = (string)$IntraItem['Dislocation'];
            $AppNormal = (string)$IntraItem['AppNormal'];
            $AppSwollen = (string)$IntraItem['AppSwollen'];
            $AppDeformed = (string)$IntraItem['AppDeformed'];
            $AppAbrasions = (string)$IntraItem['AppAbrasions'];
            $AppHeamatoma = (string)$IntraItem['AppHeamatoma'];
            $AppShortened = (string)$IntraItem['AppShortened'];
            $AppOpenWound = (string)$IntraItem['AppOpenWound'];
            $GustiloClass = (string)$IntraItem['GustiloClass'];
            $Popliteal= (string)$IntraItem['Popliteal'];
            $Dorsalis= (string)$IntraItem['Dorsalis'];
            $Head= (string)$IntraItem['Head'];
            $Chest= (string)$IntraItem['Chest'];
            $Thoracic= (string)$IntraItem['Thoracic'];
            $Abdominal= (string)$IntraItem['Abdominal'];
            $Urogenital= (string)$IntraItem['Urogenital'];
            $Laterality= (string)$IntraItem['Laterality'];
            
            if(!$Laterality)
            { 
		    	$query = "UPDATE zipad_trauma_tibialshaft_appearance SET
				pxrid = '".$pxrid."',
	            DistalFemur = '".$DistalFemur."',
	            TibaPlateau = '".$TibaPlateau."',
	            Patella = '".$Patella."',
	            Dislocation = '".$Dislocation."',
	            AppSwollen = '".$AppSwollen."',
	            AppDeformed = '".$AppDeformed."',
	            AppAbrasions = '".$AppAbrasions."',
	            AppHeamatoma = '".$AppHeamatoma."',
	            AppShortened = '".$AppShortened."',
	            AppOpenWound = '".$AppOpenWound."',
	            GustiloClass = '".$GustiloClass."',
	            Popliteal = '".$Popliteal."',
	            Dorsalis = '".$Dorsalis."',
	            Head = '".$Head."',
	            Chest = '".$Chest."',
	            Thoracic = '".$Thoracic."',
	            Abdominal = '".$Abdominal."',
	            Urogenital = '".$Urogenital."'
		        WHERE ClinixRID = ".$clinix."";
		    }else
			{
				$querysel = "SELECT * FROM zipad_trauma_tibialshaft_appearance
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_trauma_tibialshaft_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT INTO zipad_trauma_tibialshaft_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
			}

		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiTibiaShaftTrumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_tibiashaft_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_tibiashaft_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_tibiashaft_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiUpdateTibiaShaftXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $PelvisAP = (string)$IntraItem['PelvisAP'];
            $KneeAPL = (string)$IntraItem['KneeAPL'];
            $LegAPL = (string)$IntraItem['LegAPL'];
            $AnkleAPL = (string)$IntraItem['AnkleAPL'];
            $XrayOrder = (string)$IntraItem['XrayOrder'];
            $FractureDis = (string)$IntraItem['FractureDis'];
            $Fracture = (string)$IntraItem['Fracture'];
            $FractureComplete = (string)$IntraItem['FractureComplete'];
            $FractureLocation = (string)$IntraItem['FractureLocation'];
            $Configuration = (string)$IntraItem['Configuration'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];

              
             
		    	$query = "UPDATE zipad_trauma_tibiashaft_xray SET
				pxrid = '".$pxrid."',
				PelvisAP = '".$PelvisAP."',
	            KneeAPL = '".$KneeAPL."',
	            LegAPL = '".$LegAPL."',
	            AnkleAPL = '".$AnkleAPL."',
	            XrayOrder = '".$XrayOrder."',
	            FractureDis = '".$FractureDis."',
	            Fracture = '".$Fracture."',
	            FractureComplete = '".$FractureComplete."',
	            FractureLocation = '".$FractureLocation."',
	            Configuration = '".$Configuration."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Fracture: ".$FractureDis." ".$Fracture."<br>
			// 		Configuration: ".$Configuration."<br>
			// 		Others: ".$XrayFindingsOthers;
	           
	           
			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '". $all ."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiPRETibiashaftTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1935 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPRETibiashaftTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1936 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPRETibiashaftTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1937 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTTibiashaftTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1938 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTTibiashaftTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1939 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTTibiashaftTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1940 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


	
	
//End - Trauma Tibia shaft 


//Trauma Foot and Ankle
		private function apiaFootAnkleTraumaApp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_footankle_appearance WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_footankle_appearance SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_footankle_appearance WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiFootAnkleInspection(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $AppSwollen = (string)$IntraItem['AppSwollen'];
            $AppDeformed = (string)$IntraItem['AppDeformed'];
            $AppSupinated = (string)$IntraItem['AppSupinated'];
            $AppPronated = (string)$IntraItem['AppPronated'];
            $AppHeamatoma = (string)$IntraItem['AppHeamatoma'];
            $AppShortened = (string)$IntraItem['AppShortened'];
            $AppOpenWound = (string)$IntraItem['AppOpenWound'];
            $GustiloClass = (string)$IntraItem['GustiloClass'];
            $Capillary = (string)$IntraItem['Capillary'];
            $Dorsalis = (string)$IntraItem['Dorsalis'];
            $Head = (string)$IntraItem['Head'];
            $Chest = (string)$IntraItem['Chest'];
            $Thoracic = (string)$IntraItem['Thoracic'];
            $Abdominal = (string)$IntraItem['Abdominal'];
            $LateralityAnkle = (string)$IntraItem['LateralityAnkle'];
            $LateralityFoot = (string)$IntraItem['LateralityFoot'];
			
			if($LateralityAnkle || $LateralityFoot)
			{
				$Laterality = $LateralityAnkle." ".$LateralityFoot;
			}else
			{
				$Laterality = "";
			}
			 	
            
            if(!$Laterality)
            { 
		    	$query = "UPDATE zipad_trauma_footankle_appearance SET
				pxrid = '".$pxrid."',
	            AppSwollen = '".$AppSwollen."',
	            AppDeformed = '".$AppDeformed."',
	            AppSupinated = '".$AppSupinated."',
	            AppPronated = '".$AppPronated."',
	            AppHeamatoma = '".$AppHeamatoma."',
	            AppShortened = '".$AppShortened."',
	            AppOpenWound = '".$AppOpenWound."',
	            GustiloClass = '".$GustiloClass."',
	            Capillary = '".$Capillary."',
	            Dorsalis = '".$Dorsalis."',
	            Head = '".$Head."',
	            Chest = '".$Chest."',
	            Thoracic = '".$Thoracic."',
	            Abdominal = '".$Abdominal."'
		        WHERE ClinixRID = ".$clinix."";

		    }else
			{
				$querysel = "SELECT * FROM zipad_trauma_footankle_appearance
		        WHERE ClinixRID = ".$clinix."";
		       	$sQuery = $this->mysqli->query($querysel) or die($this->mysqli->error.__LINE__);
		 		 

		       	while($tblPX = mysqli_fetch_object($sQuery))
				{
					$ClinixRIDdata = $tblPX->ClinixRID;

				}

				 if($ClinixRIDdata)
		    	{
		    		$query = "UPDATE zipad_trauma_footankle_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."'
			        WHERE ClinixRID = ".$clinix."";

		    	}else if(!$ClinixRIDdata)
		    	{
		    		$query = "INSERT INTO zipad_trauma_footankle_appearance SET
					pxrid = '".$pxrid."',
					Laterality = '".$Laterality."',
					ClinixRID = ".$clinix." ";
		    	}
			}
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}

		private function apiFootAnkleTrumaXray(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_footankle_xray WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_footankle_xray SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_footankle_xray WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsFootAnkleTraumaXray(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $AnkleAPL = (string)$IntraItem['AnkleAPL'];
            $FootAPO = (string)$IntraItem['FootAPO'];
            $FootLateral = (string)$IntraItem['FootLateral'];
            $XrayOrder = (string)$IntraItem['XrayOrder'];
            $PilonFracDis = (string)$IntraItem['PilonFracDis'];
            $Pilonfracture = (string)$IntraItem['Pilonfracture'];
            $MalleolarfracDis = (string)$IntraItem['MalleolarfracDis'];
            $MalleInfrasyndesmotic = (string)$IntraItem['MalleInfrasyndesmotic'];
            $MalleTranssyndesmotic = (string)$IntraItem['MalleTranssyndesmotic'];
            $MalleSuprasyndesmotic = (string)$IntraItem['MalleSuprasyndesmotic'];
            $MalleLateralmallcolus = (string)$IntraItem['MalleLateralmallcolus'];
            $MalleMiddialmallcolus = (string)$IntraItem['MalleMiddialmallcolus'];
            $MalleBimallcolus = (string)$IntraItem['MalleBimallcolus'];
            $MalleTrimallcolus = (string)$IntraItem['MalleTrimallcolus'];
            $Talus = (string)$IntraItem['Talus'];
            $TalusDis = (string)$IntraItem['TalusDis'];
            $Calcaneus = (string)$IntraItem['Calcaneus'];
            $CalcaneusDis = (string)$IntraItem['CalcaneusDis'];
            $MetaDis = (string)$IntraItem['MetaDis'];
            $MetaFracture = (string)$IntraItem['MetaFracture'];
            $MetaDislocation = (string)$IntraItem['MetaDislocation'];
            $MetaFracDis = (string)$IntraItem['MetaFracDis'];
            $MetaBase = (string)$IntraItem['MetaBase'];
            $MetaShaft = (string)$IntraItem['MetaShaft'];
            $MetaHeat = (string)$IntraItem['MetaHeat'];
            $MetaIntraArticular = (string)$IntraItem['MetaIntraArticular'];
            $MetaExtraArticular = (string)$IntraItem['MetaExtraArticular'];
            $PhalDis = (string)$IntraItem['PhalDis'];
            $PhalFracture = (string)$IntraItem['PhalFracture'];
            $PhalDislocation = (string)$IntraItem['PhalDislocation'];
            $PhalFracdislocation = (string)$IntraItem['PhalFracdislocation'];
            $PhalBase = (string)$IntraItem['PhalBase'];
            $PhalShaft = (string)$IntraItem['PhalShaft'];
            $PhalHeat = (string)$IntraItem['PhalHeat'];
            $PhalIntraArticular = (string)$IntraItem['PhalIntraArticular'];
            $PhalExtraArticular = (string)$IntraItem['PhalExtraArticular'];
            $XrayFindingsOthers = (string)$IntraItem['XrayFindingsOthers'];
            
		    	$query = "UPDATE zipad_trauma_footankle_xray SET
				pxrid = '".$pxrid."',
				AnkleAPL = '".$AnkleAPL."',
				FootAPO = '".$FootAPO."',
	            FootLateral = '".$FootLateral."',
	            XrayOrder = '".$XrayOrder."',
	            PilonFracDis = '".$PilonFracDis."',
	            Pilonfracture = '".$Pilonfracture."',
	            MalleolarfracDis = '".$MalleolarfracDis."',
	            MalleInfrasyndesmotic = '".$MalleInfrasyndesmotic."',
	            MalleTranssyndesmotic = '".$MalleTranssyndesmotic."',
	            MalleSuprasyndesmotic = '".$MalleSuprasyndesmotic."',
	            MalleLateralmallcolus = '".$MalleLateralmallcolus."',
	            MalleMiddialmallcolus = '".$MalleMiddialmallcolus."',
	            MalleBimallcolus = '".$MalleBimallcolus."',
	            MalleTrimallcolus = '".$MalleTrimallcolus."',
	            Talus = '".$Talus."',
	            TalusDis = '".$TalusDis."',
	            Calcaneus = '".$Calcaneus."',
	            CalcaneusDis = '".$CalcaneusDis."',
	            MetaDis = '".$MetaDis."',
	            MetaFracture = '".$MetaFracture."',
	            MetaDislocation = '".$MetaDislocation."',
	            MetaFracDis = '".$MetaFracDis."',
	            MetaBase = '".$MetaBase."',
	            MetaShaft = '".$MetaShaft."',
	            MetaHeat = '".$MetaHeat."',
	            MetaIntraArticular = '".$MetaIntraArticular."',
	            MetaExtraArticular = '".$MetaExtraArticular."',
	            PhalDis = '".$PhalDis."',
	            PhalFracture = '".$PhalFracture."',
	            PhalDislocation = '".$PhalDislocation."',
	            PhalFracdislocation = '".$PhalFracdislocation."',
	            PhalBase = '".$PhalBase."',
	            PhalShaft = '".$PhalShaft."',
	            PhalHeat = '".$PhalHeat."',
	            PhalIntraArticular = '".$PhalIntraArticular."',
	            PhalExtraArticular = '".$PhalExtraArticular."',
	            XrayFindingsOthers = '".$XrayFindingsOthers."'
		        WHERE ClinixRID = ".$clinix."";
		        
		  //       $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			
			if($MalleInfrasyndesmotic)
			{
				$MalleInfrasyndesmotic2 = $MalleInfrasyndesmotic."<br>";
			}
			if($MalleTranssyndesmotic)
			{
				$MalleTranssyndesmotic2 = $MalleTranssyndesmotic."<br>";
			}
			if($MalleSuprasyndesmotic)
			{
				$MalleSuprasyndesmotic2 = $MalleSuprasyndesmotic."<br>";
			}
			if($MalleLateralmallcolus)
			{
				$MalleLateralmallcolus2MalleLateralmallcolus2 = $MalleLateralmallcolus."<br>";
			}
			if($MalleMiddialmallcolus)
			{
				$MalleMiddialmallcolus2 = $MalleMiddialmallcolus."<br>";
			}
			if($MalleBimallcolus)
			{
				$MalleBimallcolus2 = $MalleBimallcolus."<br>";
			}
			if($MalleTrimallcolus)
			{
				$MalleTrimallcolus2 = $MalleTrimallcolus."<br>";
			}



	        if($MetaFracture)
			{
				$MetaFracture2 = $MetaFracture."<br>";
			}
			if($MetaDislocation)
			{
				$MetaDislocation2 = $MetaDislocation."<br>";
			}
			if($MetaFracDis)
			{
				$MetaFracDis2 = $MetaFracDis."<br>";
			}
			if($MetaBase)
			{
				$MetaBase2 = $MetaBase."<br>";
			}
			if($MetaShaft)
			{
				$MetaShaft2 = $MetaShaft."<br>";
			}
			if($MetaHeat)
			{
				$MetaHeat2 = $MetaHeat."<br>";
			}
			if($MetaIntraArticular)
			{
				$MetaIntraArticular2 = $MetaIntraArticular."<br>";
			}
			if($MetaExtraArticular)
			{
				$MetaExtraArticular2 = $MetaExtraArticular."<br>";
			}


			
			// if($PhalFracture)
			// {
			// 	$PhalFracture2 = $PhalFracture."<br>";
			// }

			// if($PhalDislocation)
			// {
			// 	$PhalDislocation2 = $PhalDislocation."<br>";
			// }
			// if($PhalFracdislocation)
			// {
			// 	$PhalFracdislocation2 = $PhalFracdislocation."<br>";
			// }

			// if($PhalBase)
			// {
			// 	$PhalBase2 = $PhalBase."<br>";
			// }

			// if($PhalShaft)
			// {
			// 	$PhalShaft2 = $PhalShaft."<br>";
			// }
			// if($PhalHeat)
			// {
			// 	$PhalHeat2 = $PhalHeat."<br>";
			// }
			// if($PhalIntraArticular)
			// {
			// 	$PhalIntraArticular2 = $PhalIntraArticular."<br>";
			// }
			// if($PhalExtraArticular)
			// {
			// 	$PhalExtraArticular2 = $PhalExtraArticular."<br>";
			// }



			// $all = "<b><i>XRAY FINDINGS:</i></b><br>
			// 		Pilon Fracture: ".$PilonFracDis." ".$Pilonfracture."<br>
			// 		Malleolar fracture: ".$MalleolarfracDis."<br>
			// 		".$MalleInfrasyndesmotic2."
			// 		".$MalleTranssyndesmotic2."
			// 		".$MalleSuprasyndesmotic2."
			// 		".$MalleLateralmallcolus2."
			// 		".$MalleMiddialmallcolus2."
			// 		".$MalleBimallcolus2."
			// 		".$MalleTrimallcolus2."
			// 		Talus: ".$TalusDis." ".$Talus."<br>
			// 		Calcaneus: ".$CalcaneusDis." ".$Calcaneus."<br>
			// 		Metatarsal: ".$MetaDis."<br>
			// 		".$MetaFracture2."
			// 		".$MetaDislocation2."
			// 		".$MetaFracDis2."
			// 		".$MetaBase2."
			// 		".$MetaShaft2."
			// 		".$MetaHeat2."
			// 		".$MetaIntraArticular2."
			// 		".$MetaExtraArticular2."
			// 		Phalanges: ".$PhalDis."<br>
			// 		".$PhalFracture2."
			// 		".$PhalDislocation2."
			// 		".$PhalFracdislocation2."
			// 		".$PhalBase2."
			// 		".$PhalShaft2."
			// 		".$PhalHeat2."
			// 		".$PhalIntraArticular2."
			// 		".$PhalExtraArticular2."
			// 		Others: ".$XrayFindingsOthers;
	           
	           
			// $query = "UPDATE zipad_diagnosis SET
			// 	pxrid = '".$pxrid."',
			// 	Diagnosis = '". $all ."'
		 //        WHERE ClinixRID = ".$clinix."";

			// $r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}


		private function apiPREFootAnkleTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1941 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREFootAnkleTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1942 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPREFootAnkleTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1943 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiPOSTFootAnkleTraumaXRays(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1944 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTFootAnkleTraumaImg(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1945 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiPOSTFootAnkleTraumaVideo(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			

					
					$query2="SELECT * FROM lab_results WHERE PxRID = '$id' AND HangRID = 1946 HAVING Deleted = 0 ORDER BY Priority ASC";
					$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					if($r2->num_rows > 0) {
						$result2 = array();
						while($row2 = $r2->fetch_assoc()){
							$result2[] = $row2;
						}
						$this->response($this->json($result2, JSON_NUMERIC_CHECK), 200); // send user details
					}
			$this->response('',204);	// If no records "No Content" status
		}

	
	
//End - Trauma Foot and Ankle 

//Trauma Pre-Operative
		
	private function apiTraumaPreForm(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_preop_preform WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_preop_preform SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_preop_preform WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiTraumaInsPreOp(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Pre01 = (string)$IntraItem['Pre01'];
            $Pre02 = (string)$IntraItem['Pre02'];
            $Pre03 = (string)$IntraItem['Pre03'];
            $Pre04 = (string)$IntraItem['Pre04'];
            $Pre05 = (string)$IntraItem['Pre05'];
            $Pre06 = (string)$IntraItem['Pre06'];
            $Pre07 = (string)$IntraItem['Pre07'];
            $Pre08 = (string)$IntraItem['Pre08'];
            $Pre09CBC = (string)$IntraItem['Pre09CBC'];
            $Pre09ECG = (string)$IntraItem['Pre09ECG'];
            $Pre09BloodType = (string)$IntraItem['Pre09BloodType'];
            $Pre09ChestXray = (string)$IntraItem['Pre09ChestXray'];
            $Pre09Others = (string)$IntraItem['Pre09Others'];
            $Pre10 = (string)$IntraItem['Pre10'];
            $Pre010Secure = (string)$IntraItem['Pre010Secure'];
            $Pre11 = (string)$IntraItem['Pre11'];
            $Pre12 = (string)$IntraItem['Pre12'];
            $Pre13 = (string)$IntraItem['Pre13'];
            $PreOthers = (string)$IntraItem['PreOthers'];
             
		    	$query = "UPDATE zipad_trauma_preop_preform SET
				pxrid = '".$pxrid."',
	           	Pre01 = '".$Pre01."',
	            Pre02 = '".$Pre02."',
	            Pre03 = '".$Pre03."',
	            Pre04 = '".$Pre04."',
	            Pre05 = '".$Pre05."',
	            Pre06 = '".$Pre06."',
	            Pre07 = '".$Pre07."',
	            Pre08 = '".$Pre08."',
	            Pre09CBC = '".$Pre09CBC."',
	            Pre09ECG = '".$Pre09ECG."',
	            Pre09BloodType = '".$Pre09BloodType."',
	            Pre09ChestXray = '".$Pre09ChestXray."',
	            Pre09Others = '".$Pre09Others."',
	            Pre10 = '".$Pre10."',
	            Pre010Secure = '".$Pre010Secure."',
	            Pre11 = '".$Pre11."',
	            Pre12 = '".$Pre12."',
	            Pre13 = '".$Pre13."',
	            PreOthers = '".$PreOthers."'
		        WHERE ClinixRID = ".$clinix."";
		 		//$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			$query2 = "UPDATE zipad_diags_schedsurgery SET
	            Anesthesio = '".$Pre07."',
	            Cardio = '".$Pre08."'
		        WHERE ClinixRID = ".$clinix."";
		 		//$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
		}
//End-Trauma Pre-Operative


//Trauma Operative

		private function apiUpOpSurgSched(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $SurgeryType = (string)$IntraItem['SurgeryType'];
	        $SurgeryDate = (string)$IntraItem['SurgeryDate'];
	        $SurgeryTime = (string)$IntraItem['SurgeryTime'];
	        $Surgeon = (string)$IntraItem['Surgeon'];
	        $Assistant = (string)$IntraItem['Assistant'];
	        $Cardio = (string)$IntraItem['Cardio'];
	        $Anesthesio = (string)$IntraItem['Anesthesio'];
	        $AnesthesiaType = (string)$IntraItem['AnesthesiaType'];    
	        $AnestTypeLocal = (string)$IntraItem['AnestTypeLocal'];    
	        $AnestTypeSpinal = (string)$IntraItem['AnestTypeSpinal'];    
	        $AnestTypeEpi = (string)$IntraItem['AnestTypeEpi'];    
	        $AnestTypeNerveBlock = (string)$IntraItem['AnestTypeNerveBlock'];    
	        $AnestTypeGen = (string)$IntraItem['AnestTypeGen'];    
	        $AnesthTypeOthers = (string)$IntraItem['AnesthTypeOthers']; 
	        $Hospital = (string)$IntraItem['Hospital'];
	        $OrNurse = (string)$IntraItem['OrNurse'];
	        $Others = (string)$IntraItem['Others'];
	        $Others2 = str_replace("`","'",$Others);
             
		    	$query = "UPDATE zipad_diags_schedsurgery SET
				pxrid = '".$pxrid."',
	           	SurgeryType = '".$SurgeryType."',
		        SurgeryDate = '".$SurgeryDate."',
		        SurgeryTime = '".$SurgeryTime."',
		        Surgeon = '".$Surgeon."',
		        Assistant = '".$Assistant."',
		        Cardio = '".$Cardio."',
		        Anesthesio = '".$Anesthesio."',
		        AnesthesiaType = '".$AnesthesiaType."',
		        AnestTypeLocal = '".$AnestTypeLocal."',    
		        AnestTypeSpinal = '".$AnestTypeSpinal."',    
		        AnestTypeEpi = '".$AnestTypeEpi."',    
		        AnestTypeNerveBlock = '".$AnestTypeNerveBlock."',    
		        AnestTypeGen = '".$AnestTypeGen."',    
		        AnesthTypeOthers = '".$AnesthTypeOthers."',    
		        Hospital = '".$Hospital."',
		        OrNurse = '".$OrNurse."',
		        Others = '".$Others2."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			
		}

		private function apiTraumaOpImplant(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_trauma_OP_Implant WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}else
				{
					$query="INSERT INTO zipad_trauma_OP_Implant SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_OP_Implant WHERE ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpOpImplant(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $ImplantNailType = (string)$IntraItem['ImplantNailType'];
	        $ImplantNailSize = (string)$IntraItem['ImplantNailSize'];
	        $ImplantPlateType = (string)$IntraItem['ImplantPlateType'];
	        $ImplantPlateSize = (string)$IntraItem['ImplantPlateSize'];
	        $ImplantScrewsType = (string)$IntraItem['ImplantScrewsType'];
	        $ImplantScrewsSize = (string)$IntraItem['ImplantScrewsSize'];
	        $ImplantPinType = (string)$IntraItem['ImplantPinType'];
	        $ImplantPinSize = (string)$IntraItem['ImplantPinSize']; 
	        $Others  = (string)$IntraItem['Others'];
	        $Others2 = str_replace("`","'",$Others);
             
		    	$query = "UPDATE zipad_trauma_OP_Implant SET
				pxrid = '".$pxrid."',
	           	ImplantNailType = '".$ImplantNailType."',
		        ImplantNailSize = '".$ImplantNailSize."',
		        ImplantPlateType = '".$ImplantPlateType."',
		        ImplantPlateSize = '".$ImplantPlateSize."',
		        ImplantScrewsType = '".$ImplantScrewsType."',
		        ImplantScrewsSize = '".$ImplantScrewsSize."',
		        ImplantPinType = '".$ImplantPinType."',
		        ImplantPinSize = '".$ImplantPinSize."', 
		        Others = '".$Others2."'
		        WHERE ClinixRID = ".$clinix."";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


			
		}


		private function apiTraumaOpSurgicalTech(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_trauma_OP_Surgicaltech WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}else
				{
					$query="INSERT INTO zipad_trauma_OP_Surgicaltech SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_OP_Surgicaltech WHERE ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpOpSurgicalTech(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Tourniquet = (string)$IntraItem['Tourniquet'];
	        $ReleaseTech = (string)$IntraItem['ReleaseTech'];
	        $SurgicalApproach = (string)$IntraItem['SurgicalApproach'];
	        $SurgicalAppOthers = (string)$IntraItem['SurgicalAppOthers'];
	        $BloodLoss = (string)$IntraItem['BloodLoss'];
	        $Closure = (string)$IntraItem['Closure'];
	        $OperativeCourse = (string)$IntraItem['OperativeCourse'];
	        $Findings = (string)$IntraItem['Findings'];
	        $Diagnosis = (string)$IntraItem['Diagnosis'];
	        $OpDuration = (string)$IntraItem['OpDuration'];
	        $XRays = (string)$IntraItem['XRays'];
	        $Others = (string)$IntraItem['Others'];
             
		    	$query = 
		    	"UPDATE zipad_trauma_OP_Surgicaltech SET 
		    	PxRID= ".$pxrid.",
		    	Tourniquet='".$Tourniquet."',
		    	ReleaseTech='".$ReleaseTech."',
		    	SurgicalApproach='".$SurgicalApproach."',
		    	SurgicalAppOthers='".$SurgicalAppOthers."',
		    	BloodLoss='".$BloodLoss."',
		        Closure='".$Closure."',
		        OperativeCourse='".$OperativeCourse."',
		        Findings='".$Findings."',
		        Diagnosis='".$Diagnosis."',
		        OpDuration='".$OpDuration."',
		        XRays='".$XRays."',
		        Others='".$Others."'
		    	 WHERE ClinixRID=".$clinix." ";
		        
		  		//$wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			
		}

		private function apiTraumaOpSurgical(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_trauma_OP_Surgical WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}else
				{
					$query="INSERT INTO zipad_trauma_OP_Surgical SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_OP_Surgical WHERE ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiUpOpSurgical(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $BloodLoss = (string)$IntraItem['BloodLoss'];
	        $Closure = (string)$IntraItem['Closure'];
	        $OperativeCourse = (string)$IntraItem['OperativeCourse'];
	        $Findings = (string)$IntraItem['Findings'];
	        $Diagnosis = (string)$IntraItem['Diagnosis'];
	        $OpDuration = (string)$IntraItem['OpDuration'];
	        $XRays = (string)$IntraItem['XRays'];
	        $Others = (string)$IntraItem['Others'];
             
		    	$query = 
		    	"UPDATE `zipad_trauma_OP_Surgical` SET 
		    	`PxRID`= ".$pxrid.",
		    	BloodLoss= '".$BloodLoss."',
		        Closure= '".$Closure."',
		        OperativeCourse= '".$OperativeCourse."',
		        Findings= '".$Findings."',
		        Diagnosis= '".$Diagnosis."',
		        OpDuration= '".$OpDuration."',
		        XRays= '".$XRays."',
		        Others= '".$Others."'
		    	 WHERE `ClinixRID`=".$clinix." ";
		  //             $wfp = fopen("applong.txt", "w");
				// fwrite($wfp, $query);
				// fclose($wfp);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			
		}
//End-Trauma Operative


//Trauma Post-Operative
		private function apiTraumaPostForm(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0)
			{	
				$query="SELECT * FROM zipad_trauma_postop_preform WHERE ClinixRID = '$id';";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
				else
				{
					$query="INSERT INTO zipad_trauma_postop_preform SET ClinixRID = '$id'";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					$query="SELECT * FROM zipad_trauma_postop_preform WHERE ClinixRID = '$id';";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					if($r->num_rows > 0) {
						$result = array();
						while($row = $r->fetch_assoc()){
							$result = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiTraumaInsPostOp(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$IntraItem = json_decode(file_get_contents("php://input"),true);

			$clinix  = (int)$IntraItem['clinix'];
			$pxrid = (int)$IntraItem['pxrid'];
            $Post01 = (string)$IntraItem['Post01'];
            $Post02SurgPro = (string)$IntraItem['Post02SurgPro'];
            $Post03 = (string)$IntraItem['Post03'];
            $Post04 = (string)$IntraItem['Post04'];
            $Post05 = (string)$IntraItem['Post05'];
            $Post06 = (string)$IntraItem['Post06'];
            $Post07 = (string)$IntraItem['Post07'];
            $Post08 = (string)$IntraItem['Post08'];
            $Post09 = (string)$IntraItem['Post09'];
            $Post10 = (string)$IntraItem['Post10'];
            $PostOthers = (string)$IntraItem['PostOthers'];
             
		    	$query = "UPDATE zipad_trauma_postop_preform SET
				pxrid = '".$pxrid."',
	           	Post01 = '".$Post01."',
	            Post02SurgPro = '".$Post02SurgPro."',
	            Post03 = '".$Post03."',
	            Post04 = '".$Post04."',
	            Post05 = '".$Post05."',
	            Post06 = '".$Post06."',
	            Post07 = '".$Post07."',
	            Post08 = '".$Post08."',
	            Post09 = '".$Post09."',
	            Post10 = '".$Post10."',
	            PostOthers = '".$PostOthers."'
		        WHERE ClinixRID = ".$clinix." ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		}
//END- Trauma Post Operative



//Trauma module end

		# STRUCTURED DIAGNOSIS
		private function apiGetStrucDiscSumm(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){
				// Extract from DIAGNOSIS TABLE, forwarded data
				$query="SELECT * FROM zipad_Diagnosis WHERE ClinixRID = '$id'
					ORDER BY DiagnosisRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		# STRUCTURED SCHEDULE FOR SURGERY
		private function apiGetStrucDiscSumm_SchedSurgery(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				// this was forwared, so use that DIAGS table, not this = zipad_StructuredSchedsurgery
				
				$query="SELECT * FROM zipad_diags_schedsurgery WHERE ClinixRID = '$id'
					ORDER BY DiagsSchedSurgRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		# STRUCTURED HOSPITALIZATION
		private function apiGetStrucDiscSumm_Hospitalize(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_StructuredHospitalization WHERE ClinixRID = '$id'
					ORDER BY StructuredHospitalzRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		# STRUCTURED LABS ON DISCHARGE
		private function apiGetStrucDiscSumm_StructuredLABS(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_StructuredDischargeLabs WHERE ClinixRID = '$id'
					ORDER BY StructLabsRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		# STRUCTURED DISPOSITION
		private function apiGetStrucDiscSumm_Disposition(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_StructuredDisposition WHERE ClinixRID = '$id'
					ORDER BY StrucDispoRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		# STRUCTURED MANAGEMENT
		private function apiGetStrucDiscSumm_Management(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_StructuredManagement WHERE ClinixRID = '$id'
					ORDER BY StructuredMgmtRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		# STRUCTURED MEDICATION
		private function apiGetStrucDiscSumm_Medication(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_StructuredMeds WHERE ClinixRID = '$id'
					ORDER BY StructuredMedsRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetStrucDiscSumm_FollowUp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_structuredfollow WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		# STRUCTURED DISCHARGE SUMMARY - floor


		# PRE-OP HIPs
		# PRE-OP HIPs
		# PRE-OP HIPs

		private function apiGetPREopHIP_prefrom(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_HIP_Preform WHERE ClinixRID = '$id'
					ORDER BY PreOpHIPpreformRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREopHIP_contact(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_HIP_Contact WHERE ClinixRID = '$id'
					ORDER BY PreOpHIPcontactRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREopHIP_antibio(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_HIP_AntiBio WHERE ClinixRID = '$id'
					ORDER BY PreOpHIPantibioRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREopHIP_repeatBilateral(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_HIP_RepeatB WHERE ClinixRID = '$id'
					ORDER BY PreOpHIPrepbilRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		// OPERATIVE HIP
		// OPERATIVE HIP
		// OPERATIVE HIP

		private function apiGet_OP_HIP_1(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){
				//EXTRACT FROM Diags, forwarded
				$query="SELECT * FROM zipad_Diagnosis WHERE ClinixRID = '$id'
					ORDER BY DiagnosisRID;";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGet_OP_HIP_2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	

				// FORWARDED from DIAGS
				$query="SELECT * FROM zipad_diags_schedsurgery WHERE ClinixRID = '$id'
					ORDER BY DiagsSchedSurgRID;";
					
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGet_OP_HIP_3(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPHIP_3 WHERE ClinixRID = '$id'
					ORDER BY OPHIP_3RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGet_OP_HIP_4(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPHIP_4 WHERE ClinixRID = '$id'
					ORDER BY OPHIP_4RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGet_OP_HIP_5(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPHIP_5 WHERE ClinixRID = '$id'
					ORDER BY OPHIP_5RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGet_OP_HIP_6(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPHIP_6 WHERE ClinixRID = '$id'
					ORDER BY OPHIP_6RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		// POST-OP HIP
		private function apiGetPOSTopHIP_prefrom(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_POSTop_HIP_Preform WHERE ClinixRID = '$id'
					ORDER BY PostOpHIPpreformRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		// KNEE
		// KNEE

		private function apiGetPREopKNEE_prefrom(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_KNEE_Preform WHERE ClinixRID = '$id'
					ORDER BY PreOpKNEEpreformRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREopKNEE_contact(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_KNEE_Contact WHERE ClinixRID = '$id'
					ORDER BY PreOpKNEEcontactRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREopKNEE_antiBio(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_KNEE_AntiBio WHERE ClinixRID = '$id'
					ORDER BY PreOpKNEEantibioRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetPREopKNEE_repeatBilateral(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_PREop_KNEE_RepeatB WHERE ClinixRID = '$id'
					ORDER BY PreOpKNEErepbilRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		// OPERATIVE  KNEE
		// OPERATIVE  KNEE
		// OPERATIVE  KNEE
		private function apiGet_OP_KNEE_1(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_Diagnosis WHERE ClinixRID = '$id'
					ORDER BY DiagnosisRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGet_OP_KNEE_2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_diags_schedsurgery WHERE ClinixRID = '$id'
					ORDER BY DiagsSchedSurgRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGet_OP_KNEE_3(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPKNEE_3 WHERE ClinixRID = '$id'
					ORDER BY OPKNEE_3RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGet_OP_KNEE_4(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPKNEE_4 WHERE ClinixRID = '$id'
					ORDER BY OPKNEE_4RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGet_OP_KNEE_5(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_OPKNEE_5 WHERE ClinixRID = '$id'
					ORDER BY OPKNEE_5RID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		// POST op KNEE
		private function apiGetPOSTopKNEE_prefrom(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_POSTop_KNEE_Preform WHERE ClinixRID = '$id'
					ORDER BY PostOpKNEEpreformRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		//PRE Operative OR - Start


		private function apiGetORpreOp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORpreOp WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		
		
		private function apiGetORPreOpMedHis(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORMedHist WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetORPreSocHabits(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORSocHab WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function apiGetORPreSocHabits2(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORSocialHabits2 WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiGetORPreSocHabits3(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORSocialHabits3 WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}



		private function apiGetORPreOpPINORnurse(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$clinixrid = (int)$this->_request['clinixrid'];

			if($clinixrid > 0){	
				$query="SELECT PIN_ORNurse FROM zipad_orpredsig 
					WHERE zipad_orpredsig.ClinixRID = '$clinixrid' LIMIT 1";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$zipaddsig = array();
					while($row = $r->fetch_assoc()) {
						$zipaddsig[] = $row;
						$PINORnurse = $zipaddsig[0]['PIN_ORNurse'];
					}

					$qryDsig="SELECT 
						a.b64a
						, CONCAT(b.LastName,', ',b.FirstName) AS NameORNurse
						, a.PIN
					FROM px_dsig AS a
					INNER JOIN px_data AS b ON a.PxRID = b.PxRID
					WHERE a.PIN = '$PINORnurse' LIMIT 1";

					$rDsig = $this->mysqli->query($qryDsig) or die($this->mysqli->error.__LINE__);
					if($rDsig->num_rows > 0) {

						$result = array();
						while($row = $rDsig->fetch_assoc()) {
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}else
						$this->response('',204);	// If no records "No Content" status
				}
				else
					$this->response('',204);	// If no records "No Content" status
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetORPreOpPINSurgeon(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$clinixrid = (int)$this->_request['clinixrid'];

			if($clinixrid > 0){	
				$query="SELECT PIN_Surgeon FROM zipad_orpredsig 
					WHERE zipad_orpredsig.ClinixRID = '$clinixrid' LIMIT 1";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$zipaddsig = array();
					while($row = $r->fetch_assoc()) {
						$zipaddsig[] = $row;
						$PINSurgeon = $zipaddsig[0]['PIN_Surgeon'];
					}

					$qryDsig="SELECT 
						a.b64a
						, CONCAT(b.LastName,', ',b.FirstName) AS NameSurgeon
						, a.PIN
					FROM px_dsig AS a
					INNER JOIN px_data AS b ON a.PxRID = b.PxRID
					WHERE a.PIN = '$PINSurgeon' LIMIT 1";

					$rDsig = $this->mysqli->query($qryDsig) or die($this->mysqli->error.__LINE__);
					if($rDsig->num_rows > 0) {

						$result = array();
						while($row = $rDsig->fetch_assoc()) {
							$result[] = $row;
						}
						$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
					}else
						$this->response('',204);	// If no records "No Content" status
				}
				else
					$this->response('',204);	// If no records "No Content" status
			}
			$this->response('',204);	// If no records "No Content" status
		}
		//PRE Operative OR - End




		






		//POST Operative OR - Start

		private function apiGetORPostPass(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORPass WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetPostORpostOpRec (){ 
			if($this->get_request_method() != "GET"){
				$this->response('Use GET Method!',406);
			}

			$id = (int)$this->_request['id'];

			if($id > 0){	
				$query="SELECT * FROM zipad_ORPostOpRec WHERE ClinixRID = '$id'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
		}

		//POST Operative OR - END









		//INTRA Operative OR - Start

		private function apiGetORIntraOp(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORIntraOp WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetORSkinPrep(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORSkinPrep WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetORBladder(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORBladder WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetORPotenProb(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			
			if($id > 0){	
				$query="SELECT * FROM zipad_ORPotProb WHERE ClinixRID = '$id'
					ORDER BY ORRID;";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = array();
					while($row = $r->fetch_assoc()){
						$result[] = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}
		

		//============
	    //Hx Medication
	    //============

		private function apiGetAllHxMedications(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];
			
			$query = "SELECT prescriptions.*
			, clinix.DateVisit
			FROM prescriptions 
			LEFT JOIN clinix ON clinix.ClinixRID = prescriptions.ClinixRID
			WHERE prescriptions.PxRID = '$PxRID' 
			ORDER BY prescriptions.PrescRID DESC";
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

		private function apiGetAllHxMedicationsDetails(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PrescRID = (int)$this->_request['PrescRID'];
			
			$query = "SELECT *
			FROM prescriptions_detail 
			WHERE prescriptions_detail.PrescRID = '$PrescRID' ";
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


		private function apiGetHxMedications(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$PxRID = (int)$this->_request['PxRID'];
			
			$query = "SELECT *
			FROM clinix 
			WHERE clinix.PxRID = '$PxRID' AND clinix.Medication IS NOT NULL
			ORDER BY ClinixRID DESC";
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



		//============
	    //End Hx Medication
	    //============



		//===========================
		// laboratory request
		//===========================

		private function apiInsertLabRequest()
		{
			if($this->get_request_method() != "POST")
			{
				$this->response('',406);
			}

			$labRequest = json_decode(file_get_contents("php://input"),true);

			$PxRID  = (int)$labRequest['PxRID'];
			$LabRID  = (int)$labRequest['LabRID'];
			$ClinixRID=(int)$labRequest['ClinixRID'];
			$HospRID=(int)$labRequest['HospRID'];
			$LabTypes=(string)$labRequest['LabTypes'];
			$ReferredBy=(string)$labRequest['ReferredBy'];
			$DateRequested=(string)$labRequest['DateRequested'];
			$EnteredBy=(string)$labRequest['EnteredBy'];
			date_default_timezone_set('Asia/Manila');
			$DateEntered= date("Y-m-d H:i:s");
			$LabStatusRID=(int)$labRequest['LabStatusRID'];

			if ($LabRID > 0) {

				$query = "UPDATE lab_request SET
					PxRID = '$PxRID'
					,ClinixRID = '$ClinixRID'
					,HospRID = '$HospRID'
					,DateEntered = '$DateEntered'
					,LabStatusRID = '$LabStatusRID'
					,LabTypes = '$LabTypes'
					,EnteredBy = '$EnteredBy'
					,ReferredBy = '$ReferredBy'
					,DateRequested = '$DateRequested'
				WHERE LabRID = '$LabRID'";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				
				//to clear the lab_request_details
				$query3="DELETE FROM lab_request_details WHERE LabRID = '$LabRID' ";
				$r3= $this->mysqli->query($query3) or die($this->mysqli->error.__LINE__);

				//to select the labRID
				$query2="SELECT LabRID FROM lab_request WHERE LabRID = '$LabRID' ORDER BY LabRID DESC LIMIT 1;";
				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				if($r2->num_rows > 0){
					$result = array();
					while($row = $r2->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
					$this->response('',204);	// If no records "No Content" status

			}else {

				// $query2="SELECT PxRID FROM clinix WHERE ClinixRID = '$ClinixRID' ;";
				// $r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

					// if($r2->num_rows > 0){
					// 	while($row = $r2->fetch_assoc()){
					// 		$PxRID = $row['PxRID'];

						$query = "INSERT INTO lab_request SET
							PxRID = '".$PxRID."'
							,ClinixRID = '".$ClinixRID."'
							,HospRID = '".$HospRID."'
							,DateEntered = '".$DateEntered."'
							,LabStatusRID = '".$LabStatusRID."'
							,LabTypes = '".$LabTypes."'
							,EnteredBy = '".$EnteredBy."'
							,ReferredBy = '".$ReferredBy."'
							,DateRequested = '".$DateRequested."'
							";
						$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


						$query2="SELECT LabRID FROM lab_request WHERE ClinixRID = '$ClinixRID' ORDER BY LabRID DESC LIMIT 1;";

						$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

						if($r2->num_rows > 0){
							$result = array();
							while($row = $r2->fetch_assoc()){
								$result = $row;
							}
							$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
						}
				// 	}
				// }
					$this->response('',204);	// If no records "No Content" status
			}
		}

		private function apiGetLabRequest()
		{
			if($this->get_request_method() != "GET")
			{
				$this->response('',406);
			}

			$ClinixRID = (int)$this->_request['ClinixRID'];

			$query2="SELECT LabRID, PxRID 
				FROM lab_request 
				WHERE ClinixRID = '$ClinixRID' 
				ORDER BY LabRID DESC LIMIT 1;";

				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				if($r2->num_rows > 0){
					$result = array();
					while($row = $r2->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiInsertLabRequestDetails()
		{
			if($this->get_request_method() != "POST")
			{
				$this->response('',406);
			}

			$labRequestDetails = json_decode(file_get_contents("php://input"),true);

			$PxRID  = (int)$labRequestDetails['PxRID'];
			$LRID  = (string)$labRequestDetails['LRID'];
			$LabRID  = (string)$labRequestDetails['LabRID'];


			$query2 = "INSERT INTO lab_request_details SET
				LabRID = '".$LabRID."'
				,LRID = '".$LRID."'
				";
			$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
		}

		private function apiGetAllPxLabRequest()
		{	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
						
			// $query="SELECT * FROM lab_request WHERE PxRID = '$PxRID';";
			$query="SELECT 
			lab_request.LabRID
			,lab_request.PxRID
			,lab_request.ClinixRID
			,lab_request.LabStatusRID 
			,lab_request.DateEntered
			,lab_request.SignedDate
			,lab_request.LabTypes
			,lab_request.DateRequested
			,lab_request.ReferredBy
			,lab_request.SignedPxRID

			, lkup_lab_status.StatusDesc
			, CONCAT (px_data.FirstName,' ',px_data.LastName) as EnteredBy
			, CONCAT (px_data1.FirstName,' ',px_data1.LastName) as SignedBy		

			FROM lab_request
			LEFT JOIN px_data ON lab_request.EnteredBy = px_data.PxRID
			LEFT JOIN px_data AS px_data1 ON lab_request.SignedPxRID = px_data1.PxRID
			LEFT JOIN lkup_lab_status ON lab_request.LabStatusRID = lkup_lab_status.LabStatusRID 
			WHERE lab_request.PxRID = '$id' AND lab_request.LCatRID = 0 ORDER BY lab_request.LabRID DESC";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0)
			{
				$result = array();
				while($row = $r->fetch_assoc())
				{
					$result[] = $row;
				}

				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function apiSignLabRequest()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$labRequest = json_decode(file_get_contents("php://input"),true);

			$PxRID  = (int)$labRequest['PxRID'];
			$LabRID  = (int)$labRequest['LabRID'];
			$SignedPxRID  = (int)$labRequest['SignedPxRID'];
			date_default_timezone_set('Asia/Manila');
			$SignedDate  = date("Y-m-d H:i:s");

			if ($LabRID > 0) {

				$query="UPDATE lab_request SET 
				
				SignedPxRID='$SignedPxRID'
				, SignedDate = '$SignedDate'
				WHERE PxRID = $PxRID AND LabRID = $LabRID; ";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			}
			else 
			{
				$query="SELECT LabRID FROM lab_request WHERE PxRID = '$PxRID' ORDER BY LabRID DESC LIMIT 1;";
				
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				if ($r->num_rows > 0) {
					while($row = $r->fetch_assoc()) {
						// echo "<br> LabRID: ". $row["LabRID"]."<br>";
						$LabRID= $row['LabRID'];
						$query2="UPDATE lab_request SET 
							SignedPxRID='$SignedPxRID'
						, SignedDate = '$SignedDate'
						WHERE PxRID = $PxRID AND LabRID = $LabRID; ";
						$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);
					}
				}
			}
		}

		private function apiGetLabRequestDetails()
		{	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$LabRID = (int)$this->_request['LabRID'];

			$query = "SELECT * FROM lab_request_details WHERE LabRID ='$LabRID'";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}
		//===========================
		// end laboratory request
		//===========================


		

		//===========================
		// laboratory result
		//===========================

		private function apiGetAllPxLabResult()
		{
			if($this->get_request_method() != "GET")
			{
				$this->response('',406);
			}
			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT 
			lab_results.LabRexRID
			, lab_results.LabRID
			, lab_results.LabTypes
			, lab_results.PxRID
			, lab_results.DateEntered
			, lab_results.SignedPxRID 
			, lab_results.SignedDate
			, lab_results.EnteredBy as EnteredPxRID

			, lab_request.DateRequested
			, lab_request.ReferredBy

			, CONCAT (px_data.FirstName,' ',px_data.LastName) as EnteredBy
			, CONCAT (px_data1.FirstName,' ',px_data1.LastName) as SignedBy

			, px_dok.PRC 

			FROM lab_results
			LEFT JOIN lab_request ON lab_results.LabRID = lab_request.LabRID
			LEFT JOIN px_data ON lab_results.EnteredBy = px_data.PxRID
			LEFT JOIN px_data AS px_data1 ON lab_results.SignedPxRID = px_data1.PxRID
			LEFT JOIN px_dok ON lab_results.SignedPxRID = px_dok.PxRID
			WHERE lab_results.PxRID = '$PxRID' AND (lab_results.LCatRID = 34 OR lab_results.LCatRID = 35 OR lab_results.LCatRID = 36 OR lab_results.LCatRID = 37 OR lab_results.LCatRID = 38)";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0)
			{
				$result = array();
				while($row = $r->fetch_assoc())
				{
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}	

		private function apiGetLabResultDetails()
		{
			if($this->get_request_method() != "GET")
			{
				$this->response('',406);
			}

			$LabRexRID = (int)$this->_request['LabRexRID'];

			$query="SELECT * FROM lab_results_details WHERE LabRexRID = '$LabRexRID'";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0)
			{
				$result = array();
				while($row = $r->fetch_assoc())
				{
					$result[] = $row;
				}

			$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}	
		
		//===========================
		// end laboratory result
		//===========================

		//===========================
		// xray request
		//===========================

		private function apiGetAllPxXrayRequest()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$PxRID = (int)$this->_request['PxRID'];
						
			// $query="SELECT * FROM lab_request WHERE PxRID = '$PxRID';";
			$query="SELECT 
			lab_request.*

			, lkup_lab_status.StatusDesc
			, CONCAT (px_data.FirstName,' ',px_data.LastName) as EnteredByName
			, CONCAT (px_data1.FirstName,' ',px_data1.LastName) as SignedByName	

			FROM lab_request
			LEFT JOIN px_data ON lab_request.EnteredBy = px_data.PxRID
			LEFT JOIN px_data AS px_data1 ON lab_request.SignedPxRID = px_data1.PxRID
			INNER JOIN lkup_lab_status ON lab_request.LabStatusRID = lkup_lab_status.LabStatusRID 
			WHERE lab_request.LabStatusRID = 0 AND lab_request.PxRID = '$PxRID' AND (lab_request.LCatRID = 8 OR lab_request.LCatRID = 6) ORDER BY lab_request.LabRID DESC";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0)
			{
				$result = array();
				while($row = $r->fetch_assoc())
				{
					$result[] = $row;
				}

				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiInsertXrayRequest()
		{
		if($this->get_request_method() != "POST")
			{
				$this->response('',406);
			}

			$xrayRequest = json_decode(file_get_contents("php://input"),true);

			$PxRID  = (int)$xrayRequest['PxRID'];
			$LabRID  = (int)$xrayRequest['LabRID'];
			$ClinixRID=(int)$xrayRequest['ClinixRID'];
			$HospRID=(int)$xrayRequest['HospRID'];
			$LabTypes=(string)$xrayRequest['LabTypes'];
			$ReferredBy=(string)$xrayRequest['ReferredBy'];
			$DateRequested=(string)$xrayRequest['DateRequested'];
			$EnteredBy=(int)$xrayRequest['EnteredBy'];

			date_default_timezone_set('Asia/Manila');

			$DateEntered= date("Y-m-d H:i:s");
			$LabStatusRID=(int)$xrayRequest['LabStatusRID'];
			$LCatRID=(int)$xrayRequest['LCatRID'];
			$StudyToBeDone=(string)$xrayRequest['StudyToBeDone'];
			$ChiefComplaint=(string)$xrayRequest['ChiefComplaint'];
			$History=(string)$xrayRequest['History'];

			if ($LabRID > 0) {

				$query = "UPDATE lab_request SET
					PxRID = '$PxRID'
					,ClinixRID = '$ClinixRID'
					,HospRID = '$HospRID'
					,DateEntered = '$DateEntered'
					,LabStatusRID = '$LabStatusRID'
					,LabTypes = '$LabTypes'
					,EnteredBy = '$EnteredBy'
					,ReferredBy = '$ReferredBy'
					,DateRequested = '$DateRequested'
					,LCatRID = '$LCatRID'
					,StudyToBeDone = '$StudyToBeDone'
					,ChiefComplaint = '$ChiefComplaint'
					,History = '$History'
				WHERE LabRID = '$LabRID'";

				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				
				//to clear the lab_request_details
				$query3="DELETE FROM lab_request_details WHERE LabRID = '$LabRID' ";
				$r3= $this->mysqli->query($query3) or die($this->mysqli->error.__LINE__);

				//to select the labRID
				$query2="SELECT LabRID FROM lab_request WHERE LabRID = '$LabRID' ORDER BY LabRID DESC LIMIT 1;";
				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				if($r2->num_rows > 0){
					$result = array();
					while($row = $r2->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
					$this->response('',204);	// If no records "No Content" status

			}else {

				$query = "INSERT INTO lab_request SET
					PxRID = '".$PxRID."'
					,ClinixRID = '".$ClinixRID."'
					,HospRID = '".$HospRID."'
					,DateEntered = '".$DateEntered."'
					,LabStatusRID = '".$LabStatusRID."'
					,LabTypes = '".$LabTypes."'
					,EnteredBy = '".$EnteredBy."'
					,ReferredBy = '".$ReferredBy."'
					,DateRequested = '".$DateRequested."'
					,LCatRID = '".$LCatRID."'
					,StudyToBeDone = '".$StudyToBeDone."'
					,ChiefComplaint = '".$ChiefComplaint."'
					,History = '".$History."'
					";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);


				$query2="SELECT LabRID FROM lab_request WHERE PxRID = '$PxRID' AND ClinixRID = '$ClinixRID' ORDER BY LabRID DESC LIMIT 1;";

				$r2 = $this->mysqli->query($query2) or die($this->mysqli->error.__LINE__);

				if($r2->num_rows > 0){
					$result = array();
					while($row = $r2->fetch_assoc()){
						$result = $row;
					}
					$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
				}
					$this->response('',204);	// If no records "No Content" status
			}
		}


		private function apiSignXrayRequest()
		{
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$xrayRequest = json_decode(file_get_contents("php://input"),true);

			$LabRID  = (int)$xrayRequest['LabRID'];
			$SignedPxRID  = (int)$xrayRequest['SignedPxRID'];
			date_default_timezone_set('Asia/Manila');
			$SignedDate  = date("Y-m-d H:i:s");


			$query="UPDATE lab_request SET 
			
			SignedPxRID='$SignedPxRID'
			, SignedDate = '$SignedDate'
			WHERE LabRID = $LabRID ";

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			
		}

		//===========================
		// End xray request
		//===========================

		//===========================
		// Xray result
		//===========================
		private function apiGetAllPxXrayResult()
		{
			if($this->get_request_method() != "GET")
			{
				$this->response('',406);
			}
			$PxRID = (int)$this->_request['PxRID'];

			$query="SELECT 
			lab_results.LabRexRID
			, lab_results.LabRID
			, lab_results.LabTypes
			, lab_results.PxRID
			, lab_results.DateEntered
			, lab_results.SignedPxRID 
			, lab_results.SignedDate
			, lab_results.EnteredBy as EnteredPxRID

			, lab_request.DateRequested
			, lab_request.ReferredBy

			, CONCAT (px_data.FirstName,' ',px_data.LastName) as EnteredBy
			, CONCAT (px_data1.FirstName,' ',px_data1.LastName) as SignedBy

			, px_dok.PRC 

			FROM lab_results
			LEFT JOIN lab_request ON lab_results.LabRID = lab_request.LabRID
			LEFT JOIN px_data ON lab_results.EnteredBy = px_data.PxRID
			LEFT JOIN px_data AS px_data1 ON lab_results.SignedPxRID = px_data1.PxRID
			LEFT JOIN px_dok ON lab_results.SignedPxRID = px_dok.PxRID
			WHERE lab_results.PxRID = '$PxRID' AND (lab_results.LCatRID = 6 OR lab_results.LCatRID = 8) AND lab_request.LabStatusRID = 9";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0)
			{
				$result = array();
				while($row = $r->fetch_assoc())
				{
					$result[] = $row;
				}
				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}


		private function apiGetXrayResult()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$LabRID = (int)$this->_request['LabRID'];


			$query="SELECT
			lab_results.LabRexRID 
			, lab_results.LabRID
			, lab_results.PxRID
			, lab_results.ClinixRID
			, lab_results.DateEntered
			, lab_results.SignedDate
			, lab_results.LabTypes
			, lab_results.EnteredBy as EnteredPxRID
			, lab_results.SignedPxRID

			, lab_request.DateRequested
			, lab_request.ReferredBy

			, CONCAT (px_data.FirstName,' ',px_data.LastName) as EnteredBy
			, CONCAT (px_data1.FirstName,' ',px_data1.LastName) as SignedBy	

			, lab_results_xray_details.ResultTitle
			, lab_results_xray_details.ResultDesc
			, lab_results_xray_details.RemarksOrImpression
			, lab_results_xray_details.RemarksOrImpressionDesc

			FROM lab_results
			INNER JOIN lab_results_xray_details ON lab_results.LabRexRID = lab_results_xray_details.LabRexRID
			LEFT JOIN px_data ON lab_results.EnteredBy = px_data.PxRID
			LEFT JOIN px_data as  px_data1 ON lab_results.SignedPxRID = px_data1.PxRID
			LEFT JOIN lab_request ON lab_request.LabRID = lab_results.LabRID

			WHERE lab_results.LabRID = '$LabRID'";
				
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0)
			{
				$result = array();
				while($row = $r->fetch_assoc())
				{
					$result = $row;
				}

				$this->response($this->json($result, JSON_NUMERIC_CHECK), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		//===========================
		// Xray result
		//===========================


		//med abstract

		private function apiGetMedAbstract (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$PxRID = (int)$this->_request['PxRID'];

				$query="SELECT *
				FROM forms_medabstract 
				WHERE PxRID = '$PxRID' AND Deleted = 0";
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


		//MedCertificate
		
		private function apiGetMedCertificate (){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			
			$PxRID = (int)$this->_request['PxRID'];

				$query="SELECT *
				FROM forms_medcertificate 
				WHERE PxRID = '$PxRID' AND Deleted = 0";
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