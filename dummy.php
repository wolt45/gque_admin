



    
<?php
        $HospRID = $_REQUEST['HospRID'];
        // echo "$clin: " . $clin;
        
        session_start();
        session_regenerate_id();
        // $ClinixRID = $_POST['Clinix'];

        // echo $ClinixRID;
        $_SESSION['SESS_HospRID'] = $HospRID;

        // echo $_SESSION['SESS_ClinixRID'];
        session_write_close();
  
?>

<div>Loading Data....</div>

<script type="text/javascript">
    location = '../gmmr3/app/home.php';
</script>   
    



