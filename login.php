<?php
    session_start();
    include("dbConfig.php");
    $user = $pass = $userEr = $passEr = $err ="";
    $admin = "ADM1n";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_SESSION['user']))){ 
        $conn = setupDB($dbhost,$dbSelectUsername,$dbSelectPassword);             
        if(!empty($_POST["user"])){
            $user = $_POST["user"];
        }
        if(!empty($_POST["pass"])){
            $pass = $_POST["pass"];
        }
        if(!empty($user) && !empty($pass)){
            login($conn,$user,$pass); 
        }
    }
	
    if (isset($_SESSION['user'])){ //Regenerate the session ID every time the login.php page is included and a session is already active
	    session_regenerate_id(TRUE);
	}

	function login($conn,$user,$pass){
	    global $admin;
	    $pass = hash("sha256",$pass); //simpele hash functie zonder salt
	    try{
	        $res = $conn->prepare("SELECT * FROM user WHERE username=:un AND pass=:p;");
	        $res -> bindParam(':un', $user);
	        $res -> bindParam(':p', $pass);
	        $res -> execute();
	        $row = $res->fetch();
	        if($row){
	            $_SESSION['user'] = $row[1];    //Fills session with username
	            $res = null;
	            $conn = null;
	            if($row[1] === $admin){
	                header("location: admin.php");
	                exit();
	            }
	            else {
	                header("location: index.php");
	                exit();
	            }
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
