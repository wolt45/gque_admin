<?php
# Functional PHP scripts by: Walter Frederick Seballos
@session_start();

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
   case 21:  $format = "Y-m-d"; break;
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

function GetPXInfo($RID, $dig)
{
  require 'dbcon.php';
  
    $mSql = "SELECT * FROM px_data WHERE PxRID='$RID' LIMIT 1;";
    $mQry = mysqli_query($db_ipadrbg,$mSql) or die("$mSql<br>".mysqli_error($db_ipadrbg));
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
    if ($dig==28) return $tbl->NameTitlePrefix; 
    }
    else
        return NULL;
}
?>