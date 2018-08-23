<?php
@session_start();
class PX
{
    function PXRow($MRID) 
	{
		@session_start();
		include_once('wfslib/WalnetFunctionsIPADMR.php');
		require 'connPayTab.php';

		$mSql = "SELECT * FROM px_data WHERE PxRID='$MRID'";
		$mQry = mysqli_query($db_ipadrbg,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
		if ($tblQ = $mQry->fetch_object())
		{
			#echo "<script>alert('PASSED! $MRID');</script>";
			$_SESSION['sesnPXfoto'] 	= $tblQ->foto;
			$_SESSION['sesnPXBalance'] = $tblQ->Balance;			
			$_SESSION['sesnPXDokPxRID'] = $tblQ->DokPxRID;
			
			$_SESSION['sesnPXTitleP'] = $tblQ->NameTitlePrefix;
			
			$_SESSION['sesnPX_FullName'] = $tblQ->LastName.", ".$tblQ->FirstName." ".$tblQ->MiddleName;
			$_SESSION['sesnPXLastName'] = $tblQ->LastName;
			$_SESSION['sesnPXFirstName'] = $tblQ->FirstName;
			$_SESSION['sesnPXMiddleName'] = $tblQ->MiddleName;			
			$_SESSION['sesnPXLastVisit'] = $tblQ->LastVisit;
			#$_SESSION['sesnPXVisitCount'] = $tblQ->VisitCount;
			
			$_SESSION['sesnPXDOB'] = $tblQ->DOB;
			
			$today = wfsGetSysDate(0);
			$age = datediff('yyyy', $tblQ->DOB, $today, FALSE);
			$_SESSION['sesnPXAGE'] = $age ;
			
			$_SESSION['sesnPXNationality'] = $tblQ->Nationality;

			$_SESSION['sesnPXEmployer'] = $tblQ->Employer;
			$_SESSION['sesnPXOccupation'] = $tblQ->Occupation;
			$_SESSION['sesnPXEmployerAddress'] = $tblQ->EmployerAddress;
			$_SESSION['sesnPXMonthlyIncome'] = $tblQ->MonthlyIncome;			
			
			$_SESSION['sesnPXBusinessName'] = $tblQ->BusinessName;
			$_SESSION['sesnPXBusinessAddress'] = $tblQ->BusinessAddress;

			$_SESSION['sesnPXstreet'] = $tblQ->Street;
			$_SESSION['sesnPXcity'] = $tblQ->City;
			$_SESSION['sesnPXprovince'] = $tblQ->Province;
			$_SESSION['sesnPXpostal_code'] = $tblQ->PostalCode;
			
			$_SESSION['sesnPXTIN'] = $tblQ->TIN;
			$_SESSION['sesnPXSSS'] = $tblQ->SSS;
			$_SESSION['sesnPXGSIS'] = $tblQ->GSIS;
			
			$_SESSION['sesnPXMaritalStatus'] = $tblQ->MaritalStatus;
			
			$_SESSION['sesnPXSpouseName'] = $tblQ->SpouseName;
			$_SESSION['sesnPXGender'] = $tblQ->Gender;
			$_SESSION['sesnPXReferredBy'] = $tblQ->ReferredBy;
			$_SESSION['sesnPXemail'] = $tblQ->Email;
			$_SESSION['sesnPXNumberOfChildren'] = $tblQ->NumberOfChildren;
			
			$_SESSION['sesnPXDeceased'] 		= $tblQ->Deceased;
			$_SESSION['sesnPXDeceasedDate'] 	= $tblQ->DeceasedDate;
			$_SESSION['sesnPXDeceasedReason'] 	= $tblQ->DeceasedReason;
			
			$_SESSION['sesnPXPersonDataType'] = $tblQ->PersonDataType;
			$_SESSION['sesnPXPatchForwarder'] = $tblQ->PatchForwarder;
		}
    }
}
?>