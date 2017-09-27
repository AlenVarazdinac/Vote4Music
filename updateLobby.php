<?php

include_once 'config.php';

$command = $conn->prepare("SELECT * FROM queue");
$command->execute();
while ($row = $command->fetch()) {
    $queue_user_1 = $row['queue_user_1'];
    $queue_user_2 = $row['queue_user_2'];
    $queue_user_3 = $row['queue_user_3'];
    $queue_user_4 = $row['queue_user_4'];
    $queue_user_5 = $row['queue_user_5'];
}

//echo $_POST["song_link"] . " " . $_POST["user_jqueue"];

if(isset($_POST["song_link"]) && $_POST["user_jqueue"]) {
    echo $_POST["song_link"] . " " . $_POST["user_jqueue"];
    $songLink = $_POST["song_link"];
    $userJQueue = $_POST["user_jqueue"];
    $command = $conn->prepare("UPDATE user SET user_song=:user_song WHERE user_name=:user_name");
    $command->execute(array("user_song"=>$songLink, "user_name"=>$userJQueue));
    if($queue_user_1=="") {
        $command = $conn->prepare("UPDATE queue SET queue_user_1=:queue_user_1 WHERE queue_id=1");
        $command->execute(array("queue_user_1"=>$userJQueue));
        header("location: lobby.php");
    }
}


?>
