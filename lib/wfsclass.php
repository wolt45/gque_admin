<?php
#Class written by: Walter Frederick Seballos, Jan 13m 2009
#use without permission is allowed but please give credit to the author by mentioning on your documents

class clsWalnet
{
    var $cfg_org		= "MEDIX";

	var $UserDefADMIN 		= 100;
	var $UserDefDOCTOR		= 60;
	var $UserDefCLERK		= 40;
	var $UserDefRegGUEST 	= 10;
	
	var $userTypeADMIN  = "ADMIN";
	var $userTypeDOC 	= "DOCTOR";
	var $userTypeCLERK 	= "CLERK";
	var $userTypeGUEST 	= "GUEST";
	var $userTypePATIENT = "PATIENT";

	var $dokStatus0=0; #not activated, not verified;
	var $dokStatus1=1; #not yet activated, verified;
	var $dokStatus8=8; #bad/blocked/cancelled;
	var $dokStatus9=9; #activated;
	
	function SayUserStatus($WhichOne)
	{
		if ($WhichOne==$this->dokStatus0) return "for verification";
		elseif ($WhichOne==$this->dokStatus1) return "for activation";
		elseif ($WhichOne==$this->dokStatus8) return "cancelled";
		elseif ($WhichOne==$this->dokStatus9) return "active";
		else return "undefined dok status";
	}
	
	function UserRowData($PxRID)
	{
		@session_start();
		include_once 'WalnetFunctionsMDX.php';
		#require 'wchensATHi.php';
		require 'wchensATHi.php';

		#******************************** destroy the session first
		$_SESSION["sesnLOGGEDPxRIDrbg"]				= NULL;
		$_SESSION["sesnLOGGEDUserTyperbg"] 			= NULL;
		$_SESSION["sesnLOGGEDUserNamerbg"] 			= NULL;
		$_SESSION["sesnLOGGEDUserIPXrbg"] 				= NULL;
		$_SESSION["sesnLOGGEDUserActivationKeyrbg"] 	= NULL;
		$_SESSION["sesnLOGGEDUserActivationDaterbg"] 	= NULL;
		$_SESSION["sesnLOGGEDUserStatusrbg"]			= NULL;

		$_SESSION["sesnLOGGEDPxRIDrbgrbg"]				= NULL;
		#******************************** destroy the session first - end
		
		$sql = "SELECT * FROM px_data WHERE PxRID = '$PxRID'";
			$r = mysqli_query($db_connect, $sql) OR DIE("$sql.<br>".mysqli_error($db_connect));;
			if ($tblQ = $r->fetch_object())
			{
				$_SESSION["sesnLOGGEDFotorbg"]	= $tblQ->foto;
			}

		$mSql = "SELECT * FROM users WHERE PxRID='$PxRID';";
		$mQry = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));
		if ($tblQ = $mQry->fetch_object())
		{
			$_SESSION["sesnLOGGEDPxRIDrbg"] 			= $tblQ->PxRID;
			$_SESSION["sesnLOGGEDUserTyperbg"] 			= $tblQ->UserType;
			$_SESSION["sesnLOGGEDUserNamerbg"] 			= $tblQ->UserName;
			
			$_SESSION["sesnLOGGEDUserIPXrbg"] 			= $tblQ->logip;
			$_SESSION["sesnLOGGEDUserLoggedrbg"] 		= $tblQ->Logged;
			$_SESSION["sesnLOGGEDUserActivationKeyrbg"] 	= $tblQ->ActivationKey;
			$_SESSION["sesnLOGGEDUserActivationDaterbg"] 	= $tblQ->ActivationDate;
			$_SESSION["sesnLOGGEDUserStatusrbg"]			= $tblQ->UserStatus;
			$_SESSION["sesnLOGGEDUserLevelrbg"]			= $tblQ->UserLevel;

			
			
			#Dok Check
			if ($tblQ->UserType==$this->userTypeDOC)
			{
				$mSql = "SELECT * FROM px_dok WHERE PxRID=$PxRID;";
				$mQry = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));
				if ($tblDok = $mQry->fetch_object())
				{
					$_SESSION['sesnPxDokSpecialty'] = $tblDok->Specialty;
					$_SESSION['sesnPxDokClinicSchedule'] = $tblDok->ClinicSchedule;
					$_SESSION['sesnPxDokPRC'] = $tblDok->PRC;
					$_SESSION['sesnPxDokPHC'] = $tblDok->PHC;
					$_SESSION['sesnPxDokPMA'] = $tblDok->PMA;
				}
			}
		}
	}
	
	function OrgData()
	{
		@session_start();
		include_once('WalnetFunctionsMDX.php');
		#require 'wchensATHi.php';
		require 'wchensATHi.php';

		$_SESSION["sesnORGStatus"]	= NULL;
		
		$mSql = "SELECT * FROM orgparms WHERE Status=1";
		$mQry = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));
		if ($tblQ = $mQry->fetch_object())
		{
			$_SESSION["sesnORGStatus"] 	= $tblQ->Status;
			$_SESSION["sesnORGCompany"] = $tblQ->Company;
			$_SESSION["sesnORGBranch"] 	= $tblQ->Branch;
		}	
	}
}

/*
	var $UserDefADMIN 		= 100;
	var $UserDefDOCTOR		= 60;
	var $UserDefCLERK		= 40;
	var $UserDefRegGUEST 	= 10;
	var $UserDefGUEST		= 1;
	var $UserDefPATIENT		= 0;
	
	function UserDefDesc($UserLevel)
	{
		if ($UserLevel== $this->UserDefADMIN) return "ADMIN";
		elseif ($UserLevel== $this->UserDefDOCTOR) return "DOCTOR";
		elseif ($UserLevel== $this->UserDefSECRETARY) return "CLERK";
		elseif ($UserLevel== $this->UserDefRegGUEST) return "Registered GUEST";
		elseif ($UserLevel== $this->UserDefGUEST) return "GUEST";
		elseif ($UserLevel== $this->UserDefPATIENT) return "PATIENT";	
		else return "UNDEFINED";
	}
*/	

?>
