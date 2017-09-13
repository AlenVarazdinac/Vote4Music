<?php
include_once 'config.php';

if(isset($_GET["currTime"])) {
    $command = $conn->prepare("SELECT lobby_song_time=:lobby_song_time FROM lobby WHERE lobby_id=1");
    $command->execute(array("lobby_song_time"=>$_GET["currTime"]));
    echo json_encode($command->fetchAll(PDO::FETCH_OBJ));
}
