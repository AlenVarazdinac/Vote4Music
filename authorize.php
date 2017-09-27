<?php
include_once 'config.php';

if(!isset($_POST["login_username"]) || !isset($_POST["login_username"])) {
    header("location: login.php");
}

// Prepare
$command = $conn->prepare("SELECT * FROM user WHERE user_name=:user_name AND user_pw=md5(:user_pw)");
// Assing values
$command->execute(array("user_name"=>$_POST["login_username"],"user_pw"=>$_POST["login_pw"]));
$user = $command->fetch(PDO::FETCH_OBJ);

if($user!=null) {
    // If logged in
    $_SESSION["logged"]=$user;
    header("location: lobby.php");
} else {
    // If not logged in
    header ("location: login.php");
}
