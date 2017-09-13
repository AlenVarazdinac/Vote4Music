<?php
include_once 'config.php';

if(isset($_POST["song_link"]) && isset($_POST["user_playing"])){
    $videoId = substr($_POST["song_link"], -11);
    $userPlaying = $_POST["user_playing"];
    header("location: lobby.php?song_link=" . $videoId . "&user_playing=" . $userPlaying);
} else {
    echo $userPlaying . $videoId;
}
