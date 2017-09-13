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
    // Logged in
    $_SESSION["logged"]=$user;
    $command = $conn->query("SELECT lobby_song, lobby_song_time, lobby_player FROM lobby WHERE lobby_id=1");
    $command->execute();
    while($row = $command->fetch()) {
        $videoId = $row['lobby_song'];
        $userPlaying = $row['lobby_player'];
        $songTime = $row['lobby_song_time'];
        header ("location: lobby.php?song_link=" . $videoId . "&user_playing=" . $userPlaying . "&videocurrtime=" . $songTime);
    }
} else {
    // Not logged
    header ("location: login.php");
}
