<?php
# Functional PHP scripts by: Walter Frederick Seballos
@session_start();

# 1-HIP, 2-KNEE
function narrcheck($bodyPart) {
	include ("dbcon.php");

	$mSql = "SELECT zipad_zclinix.ClinixRID
		, HIP
		, KNEE 
		, GENORTHO
		, SKELTRAUMA
		, ANKLEFOOT
		, KNEESPORTS
		, SHOULDERARM
		, SPINE
		, TraumaPelvis
		, TraumaHip
		, TraumaWristFinger
		, TraumaAnkle
		, TraumaFoot
		, TraumaFemur
		, TraumaTibia
		, TraumaRadiusUlna
		FROM zipad_zclinix 
		INNER JOIN zipad_zclinixnow ON zipad_zclinix.ClinixRID = zipad_zclinixnow.ClinixRID;";
	$mQry = mysqli_query($db_ipadrbg, $mSql) OR DIE("$mSql.<br>".mysqli_error($db_ipadrbg));
	
	if ($tblOBJ = $mQry->fetch_object()) {
	    switch($bodyPart) {
		    case 1  : return $tblOBJ->HIP; break;
		    case 2  : return $tblOBJ->KNEE; break;

		    case 3  : return $tblOBJ->GENORTHO; break;
		    case 4  : return $tblOBJ->SKELTRAUMA; break;

		    case 5  : return $tblOBJ->ANKLEFOOT; break;
		    case 6  : return $tblOBJ->KNEESPORTS; break;
		    case 7  : return $tblOBJ->SHOULDERARM; break;

		    case 8  : return $tblOBJ->ELBOW; break;
		    case 9  : return $tblOBJ->WRISTHAND; break;
		    case 10 : return $tblOBJ->THIGH; break;

		    case 11 : return $tblOBJ->SPINE; break;

		    case 12 : return $tblOBJ->TraumaPelvis; break;
		    case 13 : return $tblOBJ->TraumaHip; break;
		    case 14 : return $tblOBJ->TraumaWristFinger; break;
		    case 15 : return $tblOBJ->TraumaAnkle; break;
		    case 16 : return $tblOBJ->TraumaFoot; break;

		    case 17 : return $tblOBJ->TraumaFemur; break;
		    case 18 : return $tblOBJ->TraumaTibia; break;
		    case 19 : return $tblOBJ->TraumaRadiusUlna; break;

		    case 99 : return $tblOBJ->ClinixRID; break;
		    otherwise: return 0;

		    
		    
		    

		}
	}
	else {
		return 0;
	}
}
?>