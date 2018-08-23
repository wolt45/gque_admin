<?php
# Functional PHP scripts by: Walter Frederick Seballos
@session_start();

#require '/wchensATH.php';
#require 'wchensATHi.php';

/*
function wfsDBMEDIX()
{
   #mysql_select_db("medix") or die(mysqli_error($db_connect));
   mysql_select_db("a3950990_medix") or die(mysqli_error($db_connect));
}*/
#include_once 'wfsDB.php';

function add_date($givendate,$day=0,$mth=0,$yr=0) {
		$cd = strtotime($givendate);
		$newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
	date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
	date('d',$cd)+$day, date('Y',$cd)+$yr));
	return $newdate;
}

function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
{
    /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
    (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33".
                 The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
    */

    if (!$using_timestamps) {
        $datefrom = strtotime($datefrom, 0);
        $dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds

    switch($interval) {
    case 'yyyy': // Number of full years
        $years_difference = floor($difference / 31536000);
        if (mktime(date("H", $datefrom),
                              date("i", $datefrom),
                              date("s", $datefrom),
                              date("n", $datefrom),
                              date("j", $datefrom),
                              date("Y", $datefrom)+$years_difference) > $dateto) {

        $years_difference--;
        }
        if (mktime(date("H", $dateto),
                              date("i", $dateto),
                              date("s", $dateto),
                              date("n", $dateto),
                              date("j", $dateto),
                              date("Y", $dateto)-($years_difference+1)) > $datefrom) {

        $years_difference++;
        }
        $datediff = $years_difference;
        break;

    case "q": // Number of full quarters
        $quarters_difference = floor($difference / 8035200);
        while (mktime(date("H", $datefrom),
                                   date("i", $datefrom),
                                   date("s", $datefrom),
                                   date("n", $datefrom)+($quarters_difference*3),
                                   date("j", $dateto),
                                   date("Y", $datefrom)) < $dateto) {

        $months_difference++;
        }
        $quarters_difference--;
        $datediff = $quarters_difference;
        break;

    case "m": // Number of full months
        $months_difference = floor($difference / 2678400);
        while (mktime(date("H", $datefrom),
                                   date("i", $datefrom),
                                   date("s", $datefrom),
                                   date("n", $datefrom)+($months_difference),
                                   date("j", $dateto), date("Y", $datefrom)) < 7)
                        { // Sunday
        $days_remainder--;
        }
        if ($odd_days > 6) { // Saturday
        $days_remainder--;
        }
        $datediff = ($weeks_difference * 5) + $days_remainder;
        break;

    case "ww": // Number of full weeks
        $datediff = floor($difference / 604800);
        break;

    case "h": // Number of full hours
        $datediff = floor($difference / 3600);
        break;

    case "n": // Number of full minutes
        $datediff = floor($difference / 60);
        break;

    case "d": // Number of full days
        $datediff = floor($difference / 86400);
        break;

    default: // Number of full seconds (default)
        $datediff = $difference;
        break;
    }

    return $datediff;
}

function wfs_Date_from_DATE($msDate, $outSty)
{
   #echo "<script>alert('$msDate');</script>";
   #   echo $msDate."<br><br>";
   
   #0-YYYY-Mmm-dd
   #1-FYY-Mmm-dd
   #$today = date("F j, Y, g:i a"); //ex. March 10, 2001, 5:16 pm

   switch ($outSty)
   {
   case 1:  $format = "Y-M-d"; break;
   case 2:  $format = "y-M-d"; break;
   case 3:  $format = "g:i a"; break;
   case 4:  $format = "y-M-d g:i a"; break;
   case 5:  $format = "F d, Y"; break;
   case 6:  $format = "m/d/y"; break;
   case 7:  $format = "m/d/Y"; break;
   case 8:  $format = "ymdGi"; break;
   case 9:  $format = "M d, y"; break;
   case 10:  $format = "M d"; break;
   case 11:  $format = "M d, Y g:i a"; break;
   case 12:  $format = "M d, Y"; break;
   case 13:  $format = "g:i:s a"; break;
   case 14:  $format = "y-M-d g:i:s a"; break;
   case 15:  $format = "M d, Y (l)"; break;
   case 16:  $format = "M Y"; break;
   case 17:  $format = "M"; break;
   case 18:  $format = "Y"; break;
   case 19:  $format = "F"; break;
   case 20:  $format = "m"; break;
   }

   #0123456789012345678
   #2010-01-06 13:09:40

   $yr = str_pad(substr($msDate, 0, 4), 4, "0");
   $mon = str_pad(substr($msDate, 5, 2), 2, "0");
   $dey = str_pad(substr($msDate, 8, 2), 2, "0");

   $hr = str_pad(substr($msDate, 11, 2), 2, "0");
   $min = str_pad(substr($msDate, 14, 2), 2, "0");
   $secs = str_pad(substr($msDate, 17, 2), 2, "0");

   #$dt = $yr . "-" . $mon . "-" . $day . "<br>" .$hr . ":" . $min . ":" . $secs;
   #echo $yr . "-" . $mon . "-" . $dey;
   #echo "<br><br>";
   #echo "<br>" .$hr . ":" . $min . ":" . $secs;
   #echo "<br>";
   
	$dt = date($format, mktime($hr, $min, $secs, $mon, $dey, $yr));
    if ($dt=="99-Nov-30") 
        return "&nbsp;";
    else
       return $dt;
}

function wfsGetSysDate($xkind)
{   
	date_default_timezone_set('Asia/Manila');
	$xdt = getdate();

    $xRet="";
    $ts = mktime($xdt['hours'], $xdt['minutes'], $xdt['seconds'], $xdt['mon'], $xdt['mday'], $xdt['year']);

    if ($xkind == 0)# yyyy-mm-dd
        $xRet =  date("Y-m-d", $ts);
   elseif ($xkind == 1)# mm/dd/yy
        $xRet =  date("m/d/y", $ts);
    elseif ($xkind == 2)# mm/dd/yyyy
		$xRet =  date("m/d/Y", $ts);
    elseif ($xkind == 3)
        $xRet =  date("m/d/Y g:i:s a", $ts);
   elseif ($xkind == 4)
        $xRet =  date("M d, Y g:i:s a", $ts);
   elseif ($xkind == 5)
        $xRet =  date("M d, Y", $ts);
    elseif ($xkind == 6)
    $xRet =  date("l M d, Y", $ts);
    elseif ($xkind == 8)
        $xRet =  date("ymdGi", $ts);
	elseif ($xkind == 9)
        $xRet =  date("M d, Y g:i:s", $ts);
    return $xRet;
}

function wfs_POPDate_Convert($mDate)
{
    #echo "$mDate";
    $my = substr($mDate, -4);
    $mm = substr($mDate, 0, 2);
    $md = substr($mDate, 3, 2);
    $new = $my."-".$mm."-".$md; 
    #echo "<br>$new";
    return $new;
}

function wfs_POPDate_RePOP($mDate) #YYYY-MM-DD to mm-dd-YY
{
    #echo "<script>alert('$mDate');</script>";
    $mDate = substr($mDate,0,10);
    #echo "<script>alert('$mDate');</script>";

    $my = substr($mDate, 0, 4);
    $mm = substr($mDate, 5, 2);
    $md = substr($mDate, -2);
    $new = $mm."/".$md."/".$my; 
    #echo "<br>$new";
    if ($mDate=="--")
        return NULL;
    else
        return $new;
}

function FindDefAmount($dok, $FeeRID)
{
	require 'wchensATHi.php';

	$mSql = "SELECT * FROM lkup_clinixchargesdefs WHERE PxRID='$dok' AND FeeRID='$FeeRID';";
	$tblQry = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));

	if ($tblDef = $tblQry->fetch_object())
	{
		return $tblDef->DefaultAmount;
	}
	else
	{
		return 0;
	}
}		

function GetUserInfo($UserRID, $dig)
{
	require 'wchensATHi.php';
	
    $mSql = "SELECT * FROM users WHERE PxRID='$UserRID' LIMIT 1;";
    $mQry = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tbl=$mQry->fetch_object())
    {
        if ($dig==1) return $tbl->UserName;
		if ($dig==2) return $tbl->UserType;
    }
    else
        return NULL;
}

function IsDoorOpen($UserRID, $DoorKnob)
{
	require 'wchensATHi.php';

    $mSql = "SELECT * FROM sys_doorkeys WHERE PxRID='$UserRID' AND DoorKnob='$DoorKnob' LIMIT 1;";
    $mQryxx = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblxx = $mQryxx->fetch_object())
    {
        return TRUE;
    }
    else
        return FALSE;
}

function GetDrugDetails($DrugRID, $Det)
{
	require 'wchensATHi.php';
	
    $mSql = "SELECT * FROM drugs WHERE DrugRID='$DrugRID' AND InActive=0 LIMIT 1;";
    $mQryxx = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblxx=$mQryxx->fetch_object())
    {
        switch ($Det)
		{
			case 1:  $r=$tblxx->GenericName; break;
			case 2:  $r=$tblxx->BrandName; break;
			case 3:  $r=$tblxx->DefDosage; break;
			case 4:  $r=$tblxx->DefDrugDesperseRID; break;
			case 5:  $r=$tblxx->DefMedBagnosis; break;
			case 6:  $r=$tblxx->DefIntervalRID; break;
			case 7:  $r=$tblxx->DefXDays; break;
			default: $r=NULL;
		}
		return $r;
	}
    else
        return NULL;
}

function GetlkupInterval($IntervalRID, $dig)
{
	require 'wchensATHi.php';

    $mSql = "SELECT * FROM  lkup_drug_take_intervals WHERE IntervalRID='$IntervalRID' AND InActive=0 LIMIT 1;";
    $mQryxx = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblxx=$mQryxx->fetch_object())
    {
        switch ($dig)
		{
			case 1:  $r=$tblxx->Abbrev; break;
			case 2:  $r=$tblxx->PxEquivalent; break;
			case 3:  $r=$tblxx->Factor; break;
			default: $r=NULL;
		}
		return $r;
	}
    else
        return NULL;
}

function GetMedUnits($DrugUnitRID, $dig)
{
	require 'wchensATHi.php';

	$mSql = "SELECT * FROM units_drug WHERE DrugUnitRID='$DrugUnitRID' AND InActive=0;";
	$mQryxx = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));	
	if ($tblxx=$mQryxx->fetch_object())
    {
        switch ($dig)
		{
			case 1:  $r=$tblxx->ShortName; break;
			default: $r=NULL;
		}
		return $r;
	}
    else
        return NULL;		
}		

function GetDespersionUnits($DrugDesperseRID, $dig)
{
	require 'wchensATHi.php';

	$mSql = "SELECT * FROM units_drug_despersion WHERE DrugDesperseRID='$DrugDesperseRID' AND InActive=0;";
	$mQryxx = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));	
	if ($tblxx=$mQryxx->fetch_object())
    {
        switch ($dig)
		{
			case 1:  $r=$tblxx->ShortName; break;
			case 2:  $r=$tblxx->Name; break;
			default: $r=NULL;
		}
		return $r;
	}
    else
        return NULL;		
}	

function PrinterSettings($PrnNo, $dig)
{
	require 'wchensATHi.php';

	$mSql = "SELECT * FROM printer_settings WHERE PrinterRID='$PrnNo' AND InActive=0;";
	$mQryxx = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));	
	if ($tblxx=$mQryxx->fetch_object())
    {
        switch ($dig)
		{
			case 1:  $r=$tblxx->Tall; break;
			case 2:  $r=$tblxx->Wide; break;
			default: $r=NULL;
		}
		return $r;
	}
    else
        return NULL;		
}

function GetRisitraPresetInfo($PNo, $dig)
{
	require 'wchensATHi.php';

	$mSql = "SELECT * FROM prescriptions_preset WHERE PrescPresetRID='$PNo' AND Deleted=0;";
	$mQryxx = mysqli_query($db_connect,$mSql) OR DIE("$mSql.<br>".mysqli_error($db_connect));	
	if ($tblxx=$mQryxx->fetch_object())
    {
        switch ($dig)
		{
			case 1:  $r=$tblxx->PresetName; break;
			default: $r=NULL;
		}
		return $r;
	}
    else
        return NULL;		
}

function GetPatientInfo($RID, $dig)
{
	require 'wchensATHi.php';
	
    $mSql = "SELECT * FROM px_data WHERE PxRID='$RID' LIMIT 1;";
    $mQry = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tbl=$mQry->fetch_object())
    {
        if ($dig==1) return $tbl->foto;
		if ($dig==2) return $tbl->LastName.', '.$tbl->FirstName;
		if ($dig==3) return $tbl->LastVisit;
		if ($dig==4) return $tbl->VisitCount;
		
		if ($dig==5) return $tbl->LastName;
		if ($dig==6) return $tbl->FirstName;
		if ($dig==7) return $tbl->MiddleName;
		if ($dig==8) return $tbl->DOB;
		if ($dig==9) return $tbl->Nationality;
		if ($dig==10) return $tbl->Occupation;
		if ($dig==11) return $tbl->Employer;	
		if ($dig==12) return $tbl->BusinessName;	
		if ($dig==13) return $tbl->BusinessAddress;		
		
		if ($dig==14) return $tbl->street;		
		if ($dig==15) return $tbl->postal_code;		
		if ($dig==16) return $tbl->city;		
		if ($dig==17) return $tbl->province;	
		
		if ($dig==18) return $tbl->EmployerAddress;
		if ($dig==19) return $tbl->MonthlyIncome;

		if ($dig==20) return $tbl->SSS;
		if ($dig==21) return $tbl->GSIS;
		
		if ($dig==22) return $tbl->MaritalStatus;
		if ($dig==23) return $tbl->SpouseName;
		
		if ($dig==24) return $tbl->Gender;
		if ($dig==25) return $tbl->ReferredBy;
		if ($dig==26) return $tbl->email;
		if ($dig==27) return $tbl->NumberOfChildren;
		
		if ($dig==28) return $tbl->MedHistory;
		if ($dig==29) return $tbl->ProblemsList;
    }
    else
        return NULL;
}

function GetLabRexInfo($RID, $dig)
{
	require 'wchensATHi.php';
	
    $mSql = "SELECT * FROM lab_results WHERE LabRexRID='$RID' LIMIT 1;";
    $mQry = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tbl=$mQry->fetch_object())
    {
        if ($dig==1) return $tbl->ImageName;
        if ($dig==2) return $tbl->Category;
	}
	else
		return NULL;
}

function GetLabDocuCategory($RID, $dig)
{
	require 'wchensATHi.php';
	
    $mSql = "SELECT * FROM lkup_docu_cat WHERE LCatRID='$RID' LIMIT 1;";
    $mQry = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tbl=$mQry->fetch_object())
    {
        if ($dig==1) return $tbl->Category;
	}
	else
		return NULL;
}

function Getlkup_pays_details($RID, $dig)
{
	require 'wchensATHi.php';

    $mSql = "SELECT * FROM lkup_pays_details 
		WHERE PayTypeDetailRID='$RID' LIMIT 1;";
    $mQryZX = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblZX=$mQryZX->fetch_object())
    {
        if ($dig==1) return $tblZX->Header;
        if ($dig==2) return $tblZX->Description;
        if ($dig==3) return $tblZX->SortOrder;
        if ($dig==4) return $tblZX->ThisDataType;
        if ($dig==5) return $tblZX->ThisRequired;
	}
}

function Getlkup_pays($RID, $dig)
{
	require 'wchensATHi.php';

    $mSql = "SELECT * FROM lkup_pays WHERE PayTypeRID='$RID' LIMIT 1;";
    $mQryZX = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblZX=$mQryZX->fetch_object())
    {
        if ($dig==1) return $tblZX->Description;
	}
}

function Getllkup_clinixcharges($RID, $dig)
{
	require 'wchensATHi.php';

    $mSql = "SELECT * FROM  lkup_clinixcharges WHERE FeeRID='$RID' LIMIT 1;";
    $mQryZX = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblZX=$mQryZX->fetch_object())
    {
        if ($dig==1) return $tblZX->Description;
	}
}


function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'LINUX';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac &trade;';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows &trade; ';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
	else
	{
		#wfs
	    $bname = '(UNKNOWN)'; 
        $ub = "(unknown)"; 
	}
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

function GetSchematix($ScmtxCode,$dig)
{
	require 'wchensATHi.php';

    $mSql = "SELECT * FROM lkup_template WHERE schematix_code='$ScmtxCode' LIMIT 1;";
    $mQryZX = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tblZX=$mQryZX->fetch_object())
    {
        if ($dig==1) return $tblZX->Description;
        if ($dig==2) return $tblZX->foto;
	}
	else
		return NULL;
}

function GetOrgSetUp($dig)
{
	require 'wchensATHi.php';
	
    $mSql = "SELECT * FROM orgparms WHERE Deleted=0;";
    $mQryORG = mysqli_query($db_connect,$mSql) or die("$mSql<br>".mysqli_error($db_connect));
    if ($tbl=$mQryORG->fetch_object())
    {
		if (($tbl->OrgRID<=0) OR ($tbl->TerminalNo==""))
		{
			DIE("ORG PARAMETER IS NOT PROPERLY CONFIGURED, please call Mxi ADMIN for assistance! 476-6640, 0939-200-3410, 0932-706-0670");
		}
		
  		if ($dig==1) return $tbl->Company;
  		if ($dig==2) return $tbl->Branch;
  		if ($dig==3) return $tbl->Address;
  		if ($dig==4) return $tbl->RisitaMode;
    }
    else
	{
		echo "<script>alert('ORGPARMS not set! Program haulted.');</script>";
		#die();
	}
}

function ZERO_check($number,$num)
{
	$result = "";
	if($number==0 || $number==null){
		$result = "";
	}else{
		$result = number_format($number,$num);
	}
	return $result;
}
?>