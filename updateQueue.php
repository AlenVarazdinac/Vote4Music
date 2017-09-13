<?php include_once 'config.php';

$queue_user = $_SESSION["logged"]->user_name;
$queue_song = substr($_POST["song_link"], -11);

if ($queue_song!=null) {
    $command = $conn->query("INSERT INTO queue(queue_user, queue_song) VALUES ('$queue_user', '$queue_song')");
    
    header("location: lobby.php");
} else {    
    header("location: index.php");
}



/*

FROM LOBBY

<p>In queue:</p>

<ul>
    <?php 
    $command = $conn->query("SELECT queue_user, queue_song FROM queue;");
    $command->execute();
    $results = $command->fetchAll(PDO::FETCH_OBJ);
    foreach($results as $queueList): ?>
    <li>
        <?php 
            echo $queueList->queue_user;
        ?>
    </li>
    <li>
        <?php 
            echo $queueList->queue_song;
        ?>
    </li>
    <?php endforeach;?>
</ul>



*/