<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Signature Pad demo</title>
  <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <link rel="stylesheet" href="css/signature-pad.css">

  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-39365077-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

    // wfs added 20160318

    document.domain='<?=$_SERVER['SERVER_NAME']?>';
    function popUpClosed() 
    {
      window.location.reload();
    }

    function funcDetectKey(evt)
    {
      switch(evt.keyCode)
      {
        case 27:    //ESCped
          window.close();
            break;
      }//end of switch  
    }

    function decision(message)
    {
      if(confirm(message) )
      {
        return true;
      }
      else
      {
        return false;
      }
    }
  </script>
</head>
<body onselectstart="return false" onKeyDown='funcDetectKey(event);'
    onunload="window.opener.popUpClosed();">

<?php
@session_start();

include_once "../lib/dbcon.php";
include_once "../lib/WalnetFunctionsIPADMR.php";



@$cmdSave = $_REQUEST['cmdSave'];
if ($cmdSave == 'SAVE') {

  $sesnSPECIMEN = $_SESSION['sesnSPECIMEN'];
  $sesnDSIG_PxRID = $_SESSION['sesnDSIG_PxRID'];



  @$txtb64 = $_REQUEST['txtb64'];
  if ($txtb64 == null) {
    echo "<script>alert('Please sign a specimen Signature and click Generate below!');</script>";
  }
  else {
    // $wfp = fopen("zzz_dsig.txt", "w");
    // fwrite($wfp, $txtb64);
    // fclose($wfp);

    $sesnSPECIMEN = $_SESSION['sesnSPECIMEN'];
    //echo "<script>alert($sesnSPECIMEN);</script>";
    if ($sesnSPECIMEN == 0 || $sesnSPECIMEN == 1)
      $spec = "b64a";
    elseif ($sesnSPECIMEN == 2)   
      $spec = "b64b";
    elseif ($sesnSPECIMEN == 3)   
      $spec = "b64c";

    $mSql = "SELECT PxRID FROM px_dsig 
      WHERE PxRID = '". $sesnDSIG_PxRID ."';";
    $mQry = mysqli_query($db_ipadrbg, $mSql) or die($this->mysqli->error.__LINE__);
    if ($tblDsig = $mQry->fetch_object()) {
      $mSql = "UPDATE px_dsig SET 
        $spec = '" . $txtb64 . "'
        WHERE PxRID = '". $sesnDSIG_PxRID ."'
        ;";
    }
    else
    {
      $mSql = "INSERT INTO px_dsig SET 
        PxRID = '" . $sesnDSIG_PxRID . "'
        , ".$spec." = '" . $txtb64 . "'
        ;";
    }



    mysqli_query($db_ipadrbg, $mSql) or die(mysqli_error($db_ipadrbg));

    echo "<script>alert('Signature Saved Successfully');</script>";
    echo "<script>window.close();</script>";
  }
}
elseif ($cmdSave == 'CANCEL') {
  echo "<script>window.close();</script>";
}

$sesnSPECIMEN = $_SESSION['sesnSPECIMEN'];
$sesnDSIG_PxRID = $_SESSION['sesnDSIG_PxRID'];

?>
  GUSTILO DIGITAL SIGNATURE MANAGER
  <br>

  <?php
  $mx = GetPXInfo($sesnDSIG_PxRID, 2);
  echo $mx;
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>Signature Specimen #";
  echo $sesnSPECIMEN . ": ";
  ?>

  <form action="index.php" method="POST">
    <input type="text" id="idb64" name="txtb64" READONLY>  
    

    <input type="submit" name="cmdSave" value="SAVE">
    <input type="submit" name="cmdSave" value="CANCEL">
  </form>


  <div id="signature-pad" class="m-signature-pad">
    <div class="m-signature-pad--body">
      <canvas></canvas>
    </div> 
    <div class="m-signature-pad--footer">
      <div class="description">please sign on the blank space</div>
      <button type="button" class="button clear" data-action="clear">
        Clear</button>
      <button type="button" class="button save" data-action="save">
        Generate</button>
    </div>
  </div>

  <script src="js/signature_pad.js"></script>
  <script src="js/app.js"></script>
  <script src="js/jquery-1.7.1.min.js"></script>
</body>
</html>
