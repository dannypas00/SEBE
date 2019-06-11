<html>
    <?php
        include("dbConfig.php");
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if ($_POST["user"] != null and $_POST["pass"] != null ) {
                if($conn == null){
                    $conn = setupDB($dbhost,$dbInsertUsername,$dbInsertPassword);
                }
                if (!accountExists($conn, $_POST["user"])) {
                    if(newAccount($conn,$_POST["user"],$_POST["pass"])){
                        echo "Succesful account creation";
                        header("location: index.php");
                    }
                    else{
                        echo "failed to create account";
                    }
                }
                else {
                    echo "accountname already exists";
                }
            }
            else {
                echo "please fill in both textboxes";
            }
        }
        
        #Creates a new account by sending the giving data to the database
        function newAccount($conn,$u,$p){
            try{
                //$prep = $conn->prepare(""); //TODO check for duplicate username
                $prep = $conn->prepare("INSERT INTO user (username,pass) VALUES(:user,:pass);");
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
        
        #Checks if the given name corresponds with an existing name in the databse
        function accountExists($conn,$u) {
            try {
                $prep = $conn->prepare("SELECT Count(username) AS Count FROM user WHERE username = :user;");
                $prep->bindParam(':user', $u);
                $prep->execute();
                $count = $prep->fetch();
                $prep = null;
                $conn = null;
                if ($count[0] == 0) {
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
        <h1>Sign Up</h1>
        <h4> Create an account</h4> 
            <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                User: <input type="text" name="user"><br>
                Password: <input type="password" name="pass"><br>
                <input type="submit">
            </form>
    </body>
</html>