<?php
//setting header to json
header('Content-Type: application/json');

//database
define('DB_HOST', '127.0.0.1');
define('DB_USERNAME', 'softmo_admin');
define('DB_PASSWORD', 'MedixMySqlServerBox1');
define('DB_NAME', 'ipadrbg');

//get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(!$mysqli){
	die("Connection failed: " . $mysqli->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
	$Year1 = $_POST['Year1'];
	$Year2 = $_POST['Year2'];

	$Oldjanuary = $Year1.'-01-01';
	$Oldjanuary2 = $Year1.'-01-31';
	$Newjanuary = $Year2.'-01-01';
	$Newjanuary2 = $Year2.'-01-31';

	$Oldfebruary = $Year1.'-02-01';
	$Oldfebruary2 = $Year1.'-02-28';
	$Newfebruary = $Year2.'-02-01';
	$Newfebruary2 = $Year2.'-02-28';
	
	$OldMarch = $Year1.'-03-01';
	$OldMarch2 = $Year1.'-03-31';
	$NewMarch = $Year2.'-03-01';
	$NewMarch2 = $Year2.'-03-31';

	$OldApril = $Year1.'-04-01';
	$OldApril2 = $Year1.'-04-30';
	$NewApril = $Year2.'-04-01';
	$NewApril2 = $Year2.'-04-30';

	$OldMay = $Year1.'-05-01';
	$OldMay2 = $Year1.'-05-31';
	$NewMay = $Year2.'-05-01';
	$NewMay2 = $Year2.'-05-31';

	$OldJune = $Year1.'-06-01';
	$OldJune2 = $Year1.'-06-30';
	$NewJune = $Year2.'-06-01';
	$NewJune2 = $Year2.'-06-30';

	$OldJuly = $Year1.'-07-01';
	$OldJuly2 = $Year1.'-07-31';
	$NewJuly = $Year2.'-07-01';
	$NewJuly2 = $Year2.'-07-31';

	$OldAugust = $Year1.'-08-01';
	$OldAugust2 = $Year1.'-08-31';
	$NewAugust = $Year2.'-08-01';
	$NewAugust2 = $Year2.'-08-31';

	$OldSeptember = $Year1.'-09-01';
	$OldSeptember2 = $Year1.'-09-30';
	$NewSeptember = $Year2.'-09-01';
	$NewSeptember2 = $Year2.'-09-30';

	$OldOctober = $Year1.'-10-01';
	$OldOctober2 = $Year1.'-10-31';
	$NewOctober = $Year2.'-10-01';
	$NewOctober2 = $Year2.'-10-31';

	$OldNovember = $Year1.'-11-01';
	$OldNovember2 = $Year1.'-11-30';
	$NewNovember = $Year2.'-11-01';
	$NewNovember2 = $Year2.'-11-30';

	$OldDecember = $Year1.'-12-01';
	$OldDecember2 = $Year1.'-12-31';
	$NewDecember = $Year2.'-12-01';
	$NewDecember2 = $Year2.'-12-31';


		$queryDel = "TRUNCATE TABLE rep_CountMonth";
			$QueryDel = $mysqli->query($queryDel);

		//query to get data from the table
		$queryJanuary = "SELECT COUNT(PxRID) as CountJanuary, RegDate FROM px_data WHERE RegDate BETWEEN '$Oldjanuary' AND '$Oldjanuary2' AND RegDate != '0000-00-00'";

		//execute query
		$resultJanuary = $mysqli->query($queryJanuary);

		$getJanuary = mysqli_fetch_array($resultJanuary);
		$CountJanuary = $getJanuary['CountJanuary'];



		$queryJanuary2 = "SELECT COUNT(PxRID) as CountJanuary2, RegDate FROM px_data WHERE RegDate BETWEEN '$Newjanuary' AND '$Newjanuary2' AND RegDate != '0000-00-00'";

		//execute query
		$resultJanuary2 = $mysqli->query($queryJanuary2);

		$getJanuary2 = mysqli_fetch_array($resultJanuary2);
		$CountJanuary2 = $getJanuary2['CountJanuary2'];






		$queryFebruary = "SELECT COUNT(PxRID) as CountFebruary, RegDate FROM px_data WHERE RegDate BETWEEN '$Oldfebruary' AND '$Oldfebruary2' AND RegDate != '0000-00-00'";

		//execute query
		$resultFebruary = $mysqli->query($queryFebruary);

		$getFebruary = mysqli_fetch_array($resultFebruary);
		$CountFebruary = $getFebruary['CountFebruary'];

		$queryFebruary2 = "SELECT COUNT(PxRID) as CountFebruary2, RegDate FROM px_data WHERE RegDate BETWEEN '$Newfebruary' AND '$Newfebruary2' AND RegDate != '0000-00-00'";

		//execute query
		$resultFebruary2 = $mysqli->query($queryFebruary2);

		$getFebruary2 = mysqli_fetch_array($resultFebruary2);
		$CountFebruary2 = $getFebruary2['CountFebruary2'];





		$queryMarch = "SELECT COUNT(PxRID) as CountMarch, RegDate FROM px_data WHERE RegDate BETWEEN '$OldMarch' AND '$OldMarch2' AND RegDate != '0000-00-00'";

		//execute query
		$resultMarch = $mysqli->query($queryMarch);

		$getdataMarch = mysqli_fetch_array($resultMarch);
		$CountMarch = $getdataMarch['CountMarch'];

		$queryMarch2 = "SELECT COUNT(PxRID) as CountMarch2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewMarch' AND '$NewMarch2' AND RegDate != '0000-00-00'";

		//execute query
		$resultMarch2 = $mysqli->query($queryMarch2);

		$getMarch2 = mysqli_fetch_array($resultMarch2);
		$CountMarch2 = $getMarch2['CountMarch2'];





		$queryApril = "SELECT COUNT(PxRID) as CountApril, RegDate FROM px_data WHERE RegDate BETWEEN '$OldApril' AND '$OldApril2' AND RegDate != '0000-00-00'";

		//execute query
		$resultApril = $mysqli->query($queryApril);

		$getdataApril = mysqli_fetch_array($resultApril);
		$CountApril = $getdataApril['CountApril'];

		$queryApril2 = "SELECT COUNT(PxRID) as CountApril2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewApril' AND '$NewApril2' AND RegDate != '0000-00-00'";

		//execute query
		$resultApril2 = $mysqli->query($queryApril2);

		$getApril2 = mysqli_fetch_array($resultApril2);
		$CountApril2 = $getApril2['CountApril2'];




		$queryMay = "SELECT COUNT(PxRID) as CountMay, RegDate FROM px_data WHERE RegDate BETWEEN '$OldMay' AND '$OldMay2' AND RegDate != '0000-00-00'";

		//execute query
		$resultMay = $mysqli->query($queryMay);

		$getdataMay = mysqli_fetch_array($resultMay);
		$CountMay = $getdataMay['CountMay'];

		$queryMay2 = "SELECT COUNT(PxRID) as CountMay2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewMay' AND '$NewMay2' AND RegDate != '0000-00-00'";

		//execute query
		$resultMay2 = $mysqli->query($queryMay2);

		$getdataMay2 = mysqli_fetch_array($resultMay2);
		$CountMay2 = $getdataMay2['CountMay2'];






		$queryJune = "SELECT COUNT(PxRID) as CountJune, RegDate FROM px_data WHERE RegDate BETWEEN '$OldJune' AND '$OldJune2' AND RegDate != '0000-00-00'";

		//execute query
		$resultJune = $mysqli->query($queryJune);

		$getdataJune = mysqli_fetch_array($resultJune);
		$CountJune = $getdataJune['CountJune'];

		$queryJune2 = "SELECT COUNT(PxRID) as CountJune2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewJune' AND '$NewJune2' AND RegDate != '0000-00-00'";

		//execute query
		$resultJune2 = $mysqli->query($queryJune2);

		$getdataJune2 = mysqli_fetch_array($resultJune2);
		$CountJune2 = $getdataJune2['CountJune2'];





		$queryJuly = "SELECT COUNT(PxRID) as CountJuly, RegDate FROM px_data WHERE RegDate BETWEEN '$OldJuly' AND '$OldJuly2' AND RegDate != '0000-00-00'";

		//execute query
		$resultJuly = $mysqli->query($queryJuly);

		$getdataJuly = mysqli_fetch_array($resultJuly);
		$CountJuly = $getdataJuly['CountJuly'];

		$queryJuly2 = "SELECT COUNT(PxRID) as CountJuly2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewJuly' AND '$NewJuly2' AND RegDate != '0000-00-00'";

		//execute query
		$resultJuly2 = $mysqli->query($queryJuly2);

		$getdataJuly2 = mysqli_fetch_array($resultJuly2);
		$CountJuly2 = $getdataJuly2['CountJuly2'];






		$queryAugust = "SELECT COUNT(PxRID) as CountAugust, RegDate FROM px_data WHERE RegDate BETWEEN '$OldAugust' AND '$OldAugust2' AND RegDate != '0000-00-00'";

		//execute query
		$resultAugust = $mysqli->query($queryAugust);

		$getdataAugust = mysqli_fetch_array($resultAugust);
		$CountAugust = $getdataAugust['CountAugust'];

		$queryAugust2 = "SELECT COUNT(PxRID) as CountAugust2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewAugust' AND '$NewAugust2' AND RegDate != '0000-00-00'";

		//execute query
		$resultAugust2 = $mysqli->query($queryAugust2);

		$getdataAugust2 = mysqli_fetch_array($resultAugust2);
		$CountAugust2 = $getdataAugust2['CountAugust2'];





		$querySeptember = "SELECT COUNT(PxRID) as CountSeptember, RegDate FROM px_data WHERE RegDate BETWEEN '$OldSeptember' AND '$OldSeptember2' AND RegDate != '0000-00-00'";

		//execute query
		$resultSeptember = $mysqli->query($querySeptember);

		$getdataSeptember = mysqli_fetch_array($resultSeptember);
		$CountSeptember = $getdataSeptember['CountSeptember'];

		$querySeptember2 = "SELECT COUNT(PxRID) as CountSeptember2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewSeptember' AND '$NewSeptember2' AND RegDate != '0000-00-00'";

		//execute query
		$resultSeptember2 = $mysqli->query($querySeptember2);

		$getdataSeptember2 = mysqli_fetch_array($resultSeptember2);
		$CountSeptember2 = $getdataSeptember2['CountSeptember2'];



		$queryOctober = "SELECT COUNT(PxRID) as CountOctober, RegDate FROM px_data WHERE RegDate BETWEEN '$OldOctober' AND '$OldOctober2' AND RegDate != '0000-00-00'";

		//execute query
		$resultOctober = $mysqli->query($queryOctober);

		$getdataOctober = mysqli_fetch_array($resultOctober);
		$CountOctober = $getdataOctober['CountOctober'];

		$queryOctober2 = "SELECT COUNT(PxRID) as CountOctober2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewOctober' AND '$NewOctober2' AND RegDate != '0000-00-00'";

		//execute query
		$resultOctober2 = $mysqli->query($queryOctober2);

		$getdataOctober2 = mysqli_fetch_array($resultOctober2);
		$CountOctober2 = $getdataOctober2['CountOctober2'];





		$queryNovember = "SELECT COUNT(PxRID) as CountNovember, RegDate FROM px_data WHERE RegDate BETWEEN '$OldNovember' AND '$OldNovember2' AND RegDate != '0000-00-00'";

		//execute query
		$resultNovember = $mysqli->query($queryNovember);

		$getdataNovember = mysqli_fetch_array($resultNovember);
		$CountNovember = $getdataNovember['CountNovember'];

		$queryNovember2 = "SELECT COUNT(PxRID) as CountNovember2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewNovember' AND '$NewNovember2' AND RegDate != '0000-00-00'";

		//execute query
		$resultNovember2 = $mysqli->query($queryNovember2);

		$getdataNovember2 = mysqli_fetch_array($resultNovember2);
		$CountNovember2 = $getdataNovember2['CountNovember2'];





		$queryDecember = "SELECT COUNT(PxRID) as CountDecember, RegDate FROM px_data WHERE RegDate BETWEEN '$OldDecember' AND '$OldDecember2' AND RegDate != '0000-00-00'";

		//execute query
		$resultDecember = $mysqli->query($queryDecember);

		$getdataDecember = mysqli_fetch_array($resultDecember);
		$CountDecember = $getdataDecember['CountDecember'];

		$queryDecember2 = "SELECT COUNT(PxRID) as CountDecember2, RegDate FROM px_data WHERE RegDate BETWEEN '$NewDecember' AND '$NewDecember2' AND RegDate != '0000-00-00'";

		//execute query
		$resultDecember2 = $mysqli->query($queryDecember2);

		$getdataDecember2 = mysqli_fetch_array($resultDecember2);
		$CountDecember2 = $getdataDecember2['CountDecember2'];





		$queryIns = "INSERT INTO rep_CountMonth SET 
							CountJanuary = '$CountJanuary', 
							CountJanuary2 = '$CountJanuary2', 
							CountFebruary = '$CountFebruary', 
							CountFebruary2 = '$CountFebruary2', 
							CountMarch = '$CountMarch', 
							CountMarch2 = '$CountMarch2', 
							CountApril = '$CountApril', 
							CountApril2 = '$CountApril2', 
							CountMay = '$CountMay', 
							CountMay2 = '$CountMay2', 
							CountJune = '$CountJune', 
							CountJune2 = '$CountJune2', 
							CountJuly = '$CountJuly', 
							CountJuly2 = '$CountJuly2', 
							CountAugust = '$CountAugust', 
							CountAugust2 = '$CountAugust2', 
							CountSeptember = '$CountSeptember', 
							CountSeptember2 = '$CountSeptember2', 
							CountOctober = '$CountOctober', 
							CountOctober2 = '$CountOctober2', 
							CountNovember = '$CountNovember', 
							CountNovember2 = '$CountNovember2', 
							CountDecember = '$CountDecember', 
							CountDecember2 = '$CountDecember2', 
							SelectYear = $Year1,
							SelectYear2 = $Year2";
		$resultInsert = $mysqli->query($queryIns);






		$query = "SELECT * FROM rep_CountMonth";

		//execute query
		$result = $mysqli->query($query);

		//loop through the returned data
		$data = array();
		foreach ($result as $row) {
			$data[] = $row;
		}






		//free memory associated with result
		$result->close();

		//close connection
		$mysqli->close();

		//now print the data
		print json_encode($data);

}