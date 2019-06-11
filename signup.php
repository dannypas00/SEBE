<html>
    <?php
        include("dbConfig.php");
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if($conn == null){
                $conn = setupDB($dbhost,$dbInsertUsername,$dbInsertPassword);
            }
            if (accountExists($conn, $_POST["user"]) == false) {
                if(newAccount($conn,$_POST["user"],$_POST["pass"])){
                    echo "Succesfull account creation";
                }
                else{
                    echo "failed to create account";
                }
            }
            else {
                echo "accountname already exists";
            }
        }
        
        
        function newAccount($conn,$u,$p){
            try{
                //$prep = $conn->prepare(""); //TODO check for duplicate username
                $prep = $conn->prepare("INSERT INTO user (username,pass) VALUES(:user,:pass)");
                $prep->bindParam(':user',$u);
                $prep->bindParam(':pass',hash("sha256",$p)); //simpele hash zonder salt
                $prep->execute();
                $prep = null;
                $conn = null;
                return true;
            }
            catch(PDOException $e){
                echo $e->getMessage();
                return false;
            }
        }
        
        function accountExists($conn,$u) {
            try {
                $prep = $conn->prepare("SELECT username FROM user WHERE username = :user");
                $prep->bindParam(':user', $u);
                $prep->execute();
                $num_rows = $prep->fetch(PDO::FETCH_ASSOC);
                echo $user;
                $prep = null;
                $conn = null;
                if ($num_rows === null) {
                    return false;
                }
                else {
                    return true;
                }
            }
            catch (PDOException $e) {
                echo $e->getMessage();
                return;
            }
        }
    ?>
    <body>
    	<h1>Testing Fase</h1>
        <h1>Sign Up</h1>
        <h4> Create an account</h4> 
            <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                User: <input type="text" name="user"><br>
                Password: <input type="password" name="pass"><br>
                <input type="submit">
            </form>
    </body>
</html>