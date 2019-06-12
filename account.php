<!DOCTYPE html>
<html lang="en">
<?php
    include("login.php");
    include("includes/head.php");
    include("includes/basket.php");
    include("includes/loggedin.php");
    isLoggedIn();
    $form = '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="POST">
            <p>Enter your old password:</p>
            <input type="password" name="old_pass" class="form"><br>
            <p>Enter the new password:</p>
            <input type="password" name="pass" class="form"><br>
            <p>Enter the new password again:</p>
            <input type="password" name="new_pass" class="form"><br>
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Change</button>
            </span>
            </form>';
    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['pass'])){
        try{
            $conn = setupDb($dbhost,$dbUpdateUsername,$dbUpdatePassword);
            //if (!empty($_POST['pass']) /*&& !empty($_POST['old_pass']) && $_POST['pass'] === $_POST['new_pass']*/){
                $pass = hash("sha256",$_POST['pass']);
                $new_pass = hash("sha256",$_POST['new_pass']);
                $old_pass = hash("sha256",$_POST['old_pass']);
                changePass($conn, $old_pass, $new_pass);
            //}
        }
        catch(PDOException $e){
            //echo $e->getMessage(); //debug
        }
    }
    function changePass($conn,$pass,$new_pass){
        try{
            $res = $conn->prepare("SELECT * FROM user WHERE username=:un AND pass=:p;");
            $res -> bindParam(':un', $_SESSION['user']);
            $res -> bindParam(':p', $pass);
            $res -> execute();
            $row = $res->fetch();
            $res = null;
            if($row){
                $res = $conn->prepare("UPDATE user SET pass = :np WHERE username=:un AND pass=:p;");
                $res -> bindParam(':np', $new_pass);
                $res -> bindParam(':un', $_SESSION['user']);
                $res -> bindParam(':p', $pass);
                $res -> execute();
                $res = null;
                $conn = null;
            }
            else{
                echo "Username or password invalid!";
            }
        }
        catch(PDOException $e){
            echo "Login error";
        }
    }
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
            <div class="container">
            <?php
                if(!isset($_GET['action'])){
                    echo'
                    <div class="box">
                        <div class="box">
                            <a href="account.php?action=pwchange">Change password</a>
                        </div>
                        <div class="box">
                            <a href="account.php?action=basket">Basket</a>
                        </div>
                    </div>
                    ';
                }
                else if($_GET['action'] === "pwchange"){ 
                echo'
                <div class="box">
                    <h4>Account Information:</h4>
                    <p>'. (isset($_SESSION["user"])? "Name: ".$_SESSION["user"]: "Not logged in"). '</p>
                    <hr>
                    '. (isset($_SESSION["user"]) ? $form : "Please login to use this function.").'
                </div>';}
                else if($_GET['action'] === "basket"){
                echo '
                <div class="box">
                    <h4>Products in cart:</h4>
                        <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Product</th>
                                <th style="width: 100px; text-align: center;">Amount</th>
                                <th style="width: 100px; text-align: center;">Price</th>
                                <th style="width: 100px; text-align: center;">Total</th>
                            </tr>
                        </thead>
                        <tbody>';
                        ReturnProductsInBasket();
			echo'
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="2" style="text-align: right;">'; TotalPriceOfCart(); echo '</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>';}
                ?>
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