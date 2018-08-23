<?php 
    include "head.php";
?>

<body class="nav-md menu_fixed footer_fixed">
    <div class="container body">
        <div class="main_container">
            <!-- sidemenu -->
            <?php 
                include "sidemenu.php";
            ?>
            <!-- /sidemenu -->
           <!--  <ui-view name="sidemenu"></ui-view> -->

            <!-- top navigation -->
            <?php 
                include "topnav.php";
            ?>
            <!-- <ui-view name="topnav"></ui-view> -->
            <!-- <div ui-view="main"></div> -->
            <!-- /top navigation -->
            <!-- <ui-view name="main"></ui-view> -->
            <!-- page content -->
            <ui-view></ui-view>
            <!-- /page content -->

            <!-- footer content -->
            <?php 
                // include "footer.php";
            ?>
            <!-- /footer content -->
        </div>
    </div>



</body>
<?php
    include "footerscripts.php";
?>
</html>

           