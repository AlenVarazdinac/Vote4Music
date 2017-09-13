<?php include_once 'config.php';
require_once 'vendor/autoload.php';
?>


<?php
    if(isset($_GET["song_link"]) && isset($_GET["user_playing"])) {
        $videoId = $_GET["song_link"];
        $userPlaying = $_GET["user_playing"];
        $currentSongTime = $_GET["videocurrtime"];
        $currentlyPlaying = $_SESSION["logged"]->user_name;
        if($currentlyPlaying == $userPlaying) {
            $command = $conn->prepare("UPDATE lobby SET lobby_song=:lobby_song, lobby_player=:lobby_player WHERE lobby_id=1");
            $command->execute(array("lobby_song"=>$_GET["song_link"],"lobby_player"=>$_GET["user_playing"]));   
        }
        
    } else if (!isset($_GET["song_link"])){
        $command = $conn->query("SELECT lobby_song, lobby_player FROM lobby WHERE lobby_id=1");
        $command->execute();
        
        while($row = $command->fetch()) {
            $videoId = $row['lobby_song'];
            $userPlaying = $row['lobby_player'];
            echo "<p>" . $videoId . " " . $userPlaying . "</p>";
        }
    }
?>

<p class="time"></p>
<p id="curPlaying">Currently playing: <span id="curPlayingAdd"></span></p>
<p>Player - <?php echo $userPlaying;?></p>
<!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
<div id="player"></div>

<form method="post" action="updateSong.php">
    <input type="link" id="song_link" name="song_link" placeholder="Paste YouTube Video Link" />
    <input type="hidden" id="user_playing" name="user_playing" 
    value="<?php 
           $command = $conn->prepare("SELECT user_name FROM user WHERE user_name=:user_name"); 
           $command->execute(array("user_name"=>print_r($_SESSION["logged"]->user_name))); 
           ?>" />
    <input type="submit" value="Submit" />
</form>

<p>Logged in as:
    <?php echo $_SESSION["logged"]->user_name; ?>
</p>

<a href="logout.php">Log out</a>

<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- My JS 
<script src="script.js"></script>
-->




<script>
    var videoId = "<?php echo $videoId;?>";
    var checkInt;
    var userPlaying = "<?php echo $userPlaying;?>";
    var currentlyPlaying = "<?php echo $currentlyPlaying;?>";
    var currentSongTime = "<?php echo $currentSongTime;?>";
    
    console.log(videoId);
    $(document).ready(function() {
        // Get video duration
        $.get(
            "https://www.googleapis.com/youtube/v3/videos", {
                part: 'contentDetails',
                id: videoId,
                key: 'AIzaSyBnubL-IbeKX57-xce3LngeOTGsEP8WK4g'
            },
            function(data) {
                $.each(data.items, function(i, item) {
                    console.log(item);
                    videoDuration = item.contentDetails.duration;
                    getDuration(videoDuration);
                    console.log(videoDuration);
                })
            }
        );
        // Get video title
        function getDuration(videoDuration) {
            $.get(
                "https://www.googleapis.com/youtube/v3/videos", {
                    part: 'snippet',
                    id: videoId,
                    key: 'AIzaSyBnubL-IbeKX57-xce3LngeOTGsEP8WK4g'
                },
                function(data) {
                    $.each(data.items, function(i, item) {
                        console.log(item);
                        videoTitle = item.snippet.title;
                        console.log(videoTitle);
                        $("#curPlayingAdd").text(videoTitle);
                    })
                }
            );
        }

    });

    // YOUTUBE PLAYER
    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '390',
            width: '640',
            videoId: videoId,
            autoplay: 1,
            disablekb: 1,
            controls: 0,
            fs: 0,
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
        startInterval();
    }
    
    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();
        event.target.seekTo(0, true);
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
            
        }
    }
    
    // Checks every 100ms to see if the video has reached total video duration
    var videoCurrentTime = player.getCurrentTime();
    
    function startInterval() {
        checkInt = setInterval(function() {
            
            if (player.getCurrentTime() == player.getDuration()) {
                say();
                clearInterval(checkInt);
            };
            
            if(currentlyPlaying == userPlaying) {
                $.get("updateCurrTime.php?videocurrtime=" + player.getCurrentTime(), function(data){
                    $(".time").html(data);
                });  
            };
            
        }, 100)
    }

    function say() {
        
        console.log("Current video time: " + player.getCurrentTime());
        console.log("Total video time: " + player.getDuration());
    }
    
    
    if(videoCurrentTime != 0) {
        $.get("currentTime.php?currTime=" + videoCurrentTime, function(serverResponse) {
            var results = jQuery.parseJSON(serverResponse);
            console.log(results);
        });
    
    }
    
    
    
    function stopVideo() {
        player.stopVideo();
    }

</script>
