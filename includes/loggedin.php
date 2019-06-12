<?php
/*
isLoggedIn : void
Verifies user login and redirects to index.php if the user is not logged in
 */

function isLoggedIn(){
    if($_SESSION['user'] == null){
        header("location: index.php");
    }
}
?>