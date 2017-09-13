<?php
include_once 'config.php';

if(isset($_GET["videocurrtime"])) {
    $command = $conn->prepare("UPDATE lobby SET lobby_song_time=:lobby_song_time WHERE lobby_id=1");
    $command->execute(array("lobby_song_time"=>$_GET["videocurrtime"]));
}
