
<!DOCTYPE html>
<html lang="en">
<?php
    include("login.php");
    include("includes/head.php");
?>
<body>
    <div id="navb">
        <?php
        include("includes/topbar.php");
        include("includes/navbar.php");
        ?>
    </div>
    <div id="all">
        <div id="content">
            <div id="hot">
                <div class="box">
                    <div class="container">
                        <div class="col-md-11">
                            <h2>Terms of use</h2>
                            <?php
                            //direct laden van tekst bestand
                            $termstext = file_get_contents("terms.txt");
                            
                            //als bestand bestaat dan laten zien, anders zeggen dat bestand niet bestaat.
                            if (file_exists("terms.txt"))
                                   {
                                       echo "<pre>$termstext</pre>";
                                   }
                                   else 
                                   {
                                       echo "<strong>file does not exist</strong>";
                                   }
                            ?>
            </div>
        </div>
    </div>
        </div>
        <?php
            include("includes/footer.php");
            include("includes/copyright.php");
        ?>
    </div>
    <?php include("includes/scripts.php"); ?>
</body>
</html>
