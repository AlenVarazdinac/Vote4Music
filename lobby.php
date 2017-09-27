<?php include_once 'config.php';
require_once 'vendor/autoload.php';
?>

<?php
    $songLink = "";
    $userJQueue = "";
    $currentSongTime = 0;
    $totalSongTime = 0;
    $lobbyPlayer = "";

    $command = $conn->query("SELECT * FROM lobby WHERE lobby_id=1");
    $command->execute();
    while($row = $command->fetch()) {
        $songLink = $row['lobby_song'];
        $lobbyPlayer = $row['lobby_player'];
        $currentSongTime = $row['lobby_song_time'];
    }

    // Get users from queue
    $command = $conn->query("SELECT queue_user_1, queue_user_2, queue_user_3, queue_user_4, queue_user_5 FROM queue WHERE queue_id=1");
    $command->execute();
    while($row = $command->fetch()) {
        $queueUser1 = $row['queue_user_1'];
        $queueUser2 = $row['queue_user_2'];
        $queueUser3 = $row['queue_user_3'];
        $queueUser4 = $row['queue_user_4'];
        $queueUser5 = $row['queue_user_5'];
    }


    if($lobbyPlayer=="" && $queueUser1!="") {
        $command = $conn->prepare("SELECT * FROM user WHERE user_name=:user_name");
        $command->execute(array("user_name"=>$queueUser1));
        while ($row = $command->fetch()) {
            $userSong = $row['user_song'];
        }
        $songLink = substr($userSong, -11);
        $command = $conn->prepare("UPDATE user SET user_song='' WHERE user_name=:user_name");
        $command->execute(array("user_name"=>$queueUser1));
        $command = $conn->prepare("UPDATE lobby SET lobby_player=:lobby_player, lobby_song=:lobby_song WHERE lobby_id=1");
        $command->execute(array("lobby_player"=>$queueUser1, "lobby_song"=>$songLink));
        $command = $conn->prepare("UPDATE queue SET queue_user_1='' WHERE queue_id=1");
        $command->execute();
    }


?>

<!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
<div id="player"></div>

<?php
    // Get Lobby Player
    $command = $conn->query("SELECT lobby_player FROM lobby WHERE lobby_id=1");
    $command->execute();

    while($row = $command->fetch()) {
        $lobbyPlayer = $row['lobby_player'];
    }
?>
<!-- Song Name -->
<h3 id="curPlayingAdd"></h3>

<!-- Echo lobby player -->
<p>Currently Playing: <?php echo $lobbyPlayer;?>
<p>Queue</p>

<ol id="queueOrderList">
    <!--
    <li id="queue_user_1"></li>
    <li id="queue_user_2"></li>
    <li id="queue_user_3"></li>
    <li id="queue_user_4"></li>
    <li id="queue_user_5"></li>
    -->
</ol>

<!-- Update Lobby -->
<form method="post" action="updateLobby.php">
    <input type="url" id="song_link" name="song_link" placeholder="Insert YouTube Video Link" />
    <input type="hidden" id="user_jqueue" name="user_jqueue" value="<?php
        $command = $conn->prepare("SELECT user_name FROM user WHERE user_name=:user_name");
        $command->execute(array("user_name"=>print_r($_SESSION["logged"]->user_name)));
    ?>"/>
    <input type="submit" value="Join Queue"/>
</form>

<p>Logged in as:
    <?php echo $_SESSION["logged"]->user_name; ?>
</p>

<a href="logout.php">Log out</a>

<p class="time"></p>

<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- My JS
<script src="script.js"></script>
-->

<script>
    var songLink = "<?php echo $songLink;?>";
    var checkInt;
    var lobbyPlayer = "<?php echo $lobbyPlayer;?>";
    var currentSongTime = <?php echo $currentSongTime;?>;
    var totalSongTime = <?php echo $totalSongTime;?>;
    var songEnded = "false";

    var queueUser1 = "<?php echo $queueUser1;?>";
    var queueUser2 = "<?php echo $queueUser2;?>";
    var queueUser3 = "<?php echo $queueUser3;?>";
    var queueUser4 = "<?php echo $queueUser4;?>";
    var queueUser5 = "<?php echo $queueUser5;?>";


    console.log(songLink);
    $(document).ready(function() {
        // Get video duration
        $.get(
            "https://www.googleapis.com/youtube/v3/videos", {
                part: 'contentDetails',
                id: songLink,
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
                    id: songLink,
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
            videoId: songLink,
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
        // If joined in mid song then seek to time which is written in DB
        event.target.seekTo(currentSongTime, false);
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
            $.get("updateCurrentSongTime.php?totalsongtime=" + player.getDuration());

        }
    }

    // Checks every 100ms to see if the video has reached total video duration
    var videoCurrentTime = player.getCurrentTime();

    function startInterval() {
        checkInt = setInterval(function() {
        /*
            var updateQueue = $.get("updateQueue.php", function() {
              console.log("success");
            });
        */
            // Update queue if song ended
            if (player.getCurrentTime() == player.getDuration()) {
                songEnded = "true";
                $.get("updateLobby.php?songended=true", function(){
                    console.log(songEnded);
                });
                clearInterval(checkInt);
            };

            say();

            // Only lobby player can update current song time
            if(lobbyPlayer === lobbyPlayer) {
                $.get("updateCurrentSongTime.php?currentsongtime=" + player.getCurrentTime(), "&totalsongtime=" + player.getDuration() ,
                function(data){
                    $(".time").html(data);
                });
            };

            // https://stackoverflow.com/questions/18938180/how-to-get-the-html-of-a-div-on-another-page-with-jquery-ajax
            // http://api.jquery.com/load/

            $("#queueOrderList").load("updateQueueList.php #queue_user_1, #queue_user_2, #queue_user_3, #queue_user_4, #queue_user_5", function() {
                console.log("Load was performed");
            });

        }, 100)
    }


    // Write current and total song time in console
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
