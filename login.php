<?php include_once 'config.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        <?php echo $appName;?>
    </title>
</head>

<body>

    <form method="post" action="authorize.php">
        <label for="login_username">Username</label>
        <input type="text" name="login_username" id="login_username" placeholder="Enter your username" />

        <br />
        <br />


        <label for="login_pw">Password</label>
        <input type="password" name="login_pw" id="login_pw" placeholder="Enter your password" />

        <br />
        <br />


        <input type="submit" value="Log in" />
    </form>

</body>

</html>
