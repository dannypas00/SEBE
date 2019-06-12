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
        
        include("includes/scripts.php");
        
    ?>
    </div>
    <div id="all">
        <div id="content">
            <div id="stockchecker">
                <div class="container" style="height:48px">
                  <div>
                    <?php
                    // TODO: items below should be tidied!
                    
                    if (isset($_GET['id']) && isset($_GET['number'])){
                      $idProduct      = $_GET['id'];
                      $itemsRequested = $_GET['number'];
                      if (is_numeric($idProduct) == true && is_numeric($itemsRequested) == true){
                      $conn = setupDB($dbhost,$dbSelectUsername,$dbSelectPassword);
                      $query = "SELECT product_id as id, name as n
                                FROM product
                               WHERE nrInStock >= :ite 
                                 AND product_id = :idp
                              order by name;";
					  $query2 = "SELECT product_id as id, name as n
                                FROM product
                               WHERE product_id = :idp;";
					  
                      try {
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':ite', $itemsRequested);
                        $stmt->bindParam(':idp', $idProduct);
                        $stmt->execute();
                        
                        $row = $stmt->fetch();

                        if ($row !== false){
                          echo "<div>Van het product " . $row['n'] ." is nog voldoende voorraad ($itemsRequested gevraagd) </div>";
                        }
                        else{
						// have to fetch product name, since prev query didnt return a row
						  $stmt = $conn->prepare($query2);
						  $stmt->bindParam(':idp', $idProduct);
						  $stmt->execute();
						  $row = $stmt->fetch();
                          echo "<div>Van het product ". $row['n'] ." is niet voldoende voorraad ($itemsRequested gevraagd) </div>";
                        }
                        
                      } catch (Exception $e) {
                        //echo "<div>SQL Foutmelding: " . $e->getMessage();
                        echo "<div> Oeps! er is iets mis gegaan.</div>"; 
                      }
                    }//if $_POST parameters present
                    else{
                      echo "<div>U heeft geen product en aantal opgegeven.</div>";
                    }
                    }
                    ?>
                  </div>
                </div>
            </div>
            <?php
                include("includes/index/advantages.php");
                include("includes/index/hotproducts.php"); 
            ?>
        </div>
            <?php
                include("includes/footer.php");
                include("includes/copyright.php");
            ?>
    </div>
    <?php include("includes/scripts.php"); ?>
</body>
</html>