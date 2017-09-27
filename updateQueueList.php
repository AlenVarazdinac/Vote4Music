<?php

include_once 'config.php';

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

?>

<li id="queue_user_1"><?php echo $queueUser1; ?></li>
<li id="queue_user_2"><?php echo $queueUser2; ?></li>
<li id="queue_user_3"><?php echo $queueUser3; ?></li>
<li id="queue_user_4"><?php echo $queueUser4; ?></li>
<li id="queue_user_5"><?php echo $queueUser5; ?></li>
