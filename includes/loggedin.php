<?php
function isLoggedIn(){
    if($_SESSION['user'] == null){
        header("location: index.php");
    }
}
?>