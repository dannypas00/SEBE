<?php
include("login.php");
if($_SESSION['user'] === $admin){
    echo '<h2>Welcome back admin!</h2><br><h4>You have unlocked product editing<h4></h4><br><a href="index.php">HOME</a>';
}
else{
    header("location: index.php");
    exit();
}
?>