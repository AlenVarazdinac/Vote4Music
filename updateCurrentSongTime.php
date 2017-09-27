<?php

include_once 'config.php';

if(isset($_GET["currentsongtime"])) {

    $currentSongTime = $_GET["currentsongtime"];

    $command = $conn->prepare("UPDATE lobby SET lobby_song_time=:lobby_song_time WHERE lobby_id=1");
    $command->execute(array("lobby_song_time"=>$currentSongTime));
}

if(isset($_GET["totalsongtime"])) {

    $totalSongTime = $_GET["totalsongtime"];

    $command = $conn->prepare("UPDATE lobby SET lobby_total_song_time=:lobby_total_song_time WHERE lobby_id=1");
    $command->execute(array("lobby_total_song_time"=>$_GET["totalsongtime"]));
}

if($currentSongTime === $totalSongTime) {
    $command = $conn->query("UPDATE lobby SET lobby_player='', lobby_song_time=0.000, lobby_song='' WHERE lobby_id=1");
}

echo $_GET["currentsongtime"];
echo "<br/>";
echo $_GET["totalsongtime"];

?>
