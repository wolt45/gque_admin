<?php
@session_start();

class Clinix
{
	var $TranStatusDONEUTANG 	= -10;
	var $TranStatusDONE 		= -9;
	var $TranStatusCANC 		= -1;
	var $TranStatusNOSHOW 		= -2;
	var $TranStatusOPEN 		= 0;
	var $TranStatusDONEUNPAID 	= 8;

	var $strTran_StatusOPEN = "OPEN";
	var $strTran_StatusNOSHOW = "NO-SHOW";
	var $strTran_StatusCANC = "CANCELLED";
	var $strTran_StatusDONEUNPAID = "UNPAID";
	var $strTran_StatusDONEUTANG = "CHARGED";
	var $strTran_StatusDONE = "COMPLETED";
	
	var $ApptType0 = "out-patient";
	var $ApptType1 = "admitted";
	var $ApptType2 = "home-service";
	var $ApptType3 = "others";

	function ClinixFindOpen($PxRID) 
	{
		require 'connPayTab.php';
		include_once "wfslib/WalnetFunctionsIPADMR.php";
		$today = wfsGetSysDate(0);

		$mSql = "SELECT * FROM clinix 
			WHERE PxRID='$PxRID' 
				AND DateVisit = '$today'
				AND TranStatus = 0;";
		$mQryFindOpen = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));

		if ($tblClinixOpen = $mQryFindOpen->fetch_object())
		{
			#echo "<script>alert('FOUND CLINIX!');</script>";
			$this->ClinixRow($tblClinixOpen->ClinixRID);
		}
		else
		{
			$mSql = "INSERT INTO clinix 
				SET PxRID='$PxRID', DateVisit = '$today', TranStatus = 0;";
			mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
			
			$mSql = "SELECT MAX(ClinixRID) FROM clinix 
				WHERE PxRID='$PxRID' 
					AND DateVisit = '$today'
					AND TranStatus = 0;";
			$mQryNew = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
			if ($tblNewCilinx = $mQryNew->fetch_object())
				$this->ClinixRow($tblNewCilinx->ClinixRID);
		}	
	}
	
    public function ClinixRow($QueRID) 
	{
		#include 'wfslib/WalnetFunctionsIPADMR.php';
		require 'connPayTab.php';

		$mSql = "SELECT * FROM clinix WHERE ClinixRID='$QueRID'";
		$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));

		if ($tblQ = $mQry->fetch_object())
		{
			#$this->RID = $tblQ->RID;
			$_SESSION['sesnClinixRID'] = $tblQ->ClinixRID;
			$_SESSION['sesnClinixDateVisit'] = $tblQ->DateVisit;
			
			$_SESSION['sesnClinixDokPxRID'] = $tblQ->DokPxRID;
			
			$_SESSION['sesnClinixChiefComplaint'] = $tblQ->ChiefComplaint;
			$_SESSION['sesnClinixSUBJECTIVE'] = $tblQ->Subjective;
			$_SESSION['sesnClinixOBJECTIVE'] = $tblQ->Objective;
			$_SESSION['sesnClinixASSESSMENT'] = $tblQ->Assessment;
			$_SESSION['sesnClinixPlanManagement'] = $tblQ->PlanManagement;
			
			$_SESSION['sesnClinixMedication'] = $tblQ->Medication;
			
			$_SESSION['sesnClinixFOLLOWUP'] = $tblQ->FollowUpDate;
			$_SESSION['sesnClinixCHARGE'] = $tblQ->Charges;
			$_SESSION['sesnClinixDISCOUNT'] = $tblQ->Discount;
			$_SESSION['sesnClinixAMOUNTDUE'] = $tblQ->AmountDue;
			
			$_SESSION['sesnClinixTENDERED'] = $tblQ->TenderedAmount;
			$_SESSION['sesnClinixKAMBYO'] = $tblQ->ChangeAmount;
			
			$_SESSION['sesnClinixPAIDAMOUNT'] = $tblQ->PaidAmount;
			$_SESSION['sesnClinixApptType'] = $tblQ->ApptType;
			$_SESSION['sesnClinixTranStatus'] = $tblQ->TranStatus;
			
			$_SESSION['sesnClinixDateSet'] = $tblQ->AppDateSet;
			
			#$this->ClinixTextInfo($_SESSION['sesnTextType']);
			
			#pick up the prescription
			#$this->RisitaRow($tblQ->ClinixRID);
		}
    }
	
	function TranStatus($TStatus)
	{
		if ($TStatus == $this->TranStatusOPEN) 			return $this->strTran_StatusOPEN;
		elseif ($TStatus == $this->TranStatusNOSHOW) 	return $this->strTran_StatusNOSHOW;
		elseif ($TStatus == $this->TranStatusCANC) 		return $this->strTran_StatusCANC;
		elseif ($TStatus == $this->TranStatusDONEUNPAID) return $this->strTran_StatusDONEUNPAID;
		elseif ($TStatus == $this->TranStatusDONEUTANG) return $this->strTran_StatusDONEUTANG;
		elseif ($TStatus == $this->TranStatusDONE) 		return $this->strTran_StatusDONE;
		else return "(undefined)";
	}
	
	function ApptType($ApptType)
	{
		if ($ApptType == 0) return $this->ApptType0;
		elseif ($ApptType == 1) return $this->ApptType1;
		elseif ($ApptType == 2) return $this->ApptType2;
		elseif ($ApptType == 3) return $this->ApptType3;
		else return "(undefined)";
	}
	
	function ClinixTextInfo($TextType) 
	{
		#echo "<script>alert('$TextType');</script>";
		
		$_SESSION['sesnTabDef_chief'] 	= NULL;
		$_SESSION['sesnTabDef_subj']  	= NULL;
		$_SESSION['sesnTabDef_obj'] 	= NULL;
		$_SESSION['sesnTabDef_asse'] 	= NULL;
		$_SESSION['sesnTabDef_plan'] 	= NULL;
		$_SESSION['sesnTabDef_medi'] 	= NULL;
		
		if ($TextType == "ChiefComplaint")
		{
			$_SESSION['sesnTextType'] = "ChiefComplaint";
			$_SESSION['sesnTextData'] = $_SESSION['sesnClinixChiefComplaint'];
			$_SESSION['sesnTabDef_chief'] = "tabbertabdefault";
		}	
		elseif ($TextType == "Subjective")
		{
			$_SESSION['sesnTextType'] = "Subjective";
			$_SESSION['sesnTextData'] = $_SESSION['sesnClinixSUBJECTIVE'];
			$_SESSION['sesnTabDef_subj']  = "tabbertabdefault";
		}
		elseif ($TextType == "Objective")
		{
			$_SESSION['sesnTextType'] = "Objective";
			$_SESSION['sesnTextData'] = $_SESSION['sesnClinixOBJECTIVE'];
			$_SESSION['sesnTabDef_obj'] = "tabbertabdefault";
		}	
		elseif ($TextType == "Assessment")
		{
			$_SESSION['sesnTextType'] = "Assessment";
			$_SESSION['sesnTextData'] = $_SESSION['sesnClinixASSESSMENT'];
			$_SESSION['sesnTabDef_asse'] = "tabbertabdefault";
		}	
		elseif ($TextType == "PlanManagement")
		{
			$_SESSION['sesnTextType'] = "PlanManagement";
			$_SESSION['sesnTextData'] = $_SESSION['sesnClinixPlanManagement'];
			$_SESSION['sesnTabDef_plan'] = "tabbertabdefault";
		}		
		elseif ($TextType == "Medication")
		{
			$_SESSION['sesnTextType'] = "Medication";
			$_SESSION['sesnTextData'] = $_SESSION['sesnClinixMedication'];
			$_SESSION['sesnTabDef_medi'] = "tabbertabdefault";
		}			
		else
		{
			$_SESSION['sesnTextType'] = NULL;
			$_SESSION['sesnTextData'] = NULL;
		}
	}
	
	function RisitaRow($ClinixRID) 
	{
		# prescriptions handler
		include_once 'wfslib/WalnetFunctionsIPADMR.php';
		require 'connPayTab.php';

		$sesnLOGGEDPxRID = $_SESSION["sesnLOGGEDPxRID"];
		$sesnPxRID = $_SESSION['sesnPxRID'];
		
		$mSql = "SELECT * FROM prescriptions WHERE ClinixRID = '$ClinixRID'";
		$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
		if ($tblQ = $mQry->fetch_object())
		{
			#$this->RID = $tblQ->RID;
			$_SESSION['sesnRisitaRID'] = $tblQ->PrescRID;
			$_SESSION['sesnRisitaPresetRID'] = $tblQ->FromPresetRID;
		}
		else
		{
			#note, DateEntered may differ from Clinix row date as the doctor might issue Risita some other time.
			$mSql = "INSERT INTO prescriptions SET 
				ClinixRID = '$ClinixRID',
				PxRID = '$sesnPxRID ',
				EnteredBy = '$sesnLOGGEDPxRID',
				DateEntered= NOW()
				;";
			#echo $mSql;
			@mysqli_query($db_ipadrbg,$mSql) OR die("$mSql<br>".mysqli_error($db_ipadrbg));
			
			$mQryMax = mysqli_query($db_ipadrbg,"SELECT MAX(PrescRID) as MaxRID FROM prescriptions 
				WHERE EnteredBy='$sesnLOGGEDPxRID' AND Deleted=0;") OR DIE(mysqli_error($db_ipadrbg));
			$tblMaxRID = $mQryMax->fetch_object();
			$MaxRID = $tblMaxRID->MaxRID;
	
			$_SESSION['sesnRisitaRID'] = $MaxRID;
		}
	}
}
?>