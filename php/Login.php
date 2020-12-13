<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel=" stylesheet" href="../stile.css"/>
</head>
<body>

<?php
    require_once "DBConnector.php";
    require_once "UserData.php";

    $dbConnector = new DBAccess();
    $usrData = new UserData();

    session_start();

    if(isset($_POST['username'])) {

        $email = stripslashes($_REQUEST['username']);
        $password = stripslashes($_REQUEST['password']);

        $res = $usrData->userCredentialsCorrect($email,$password);

        if ($res) {
            $_SESSION['UserID'] = $res['ID'];

            $_SESSION['Admin'] = $usrData->isAdmin($res['ID']);
            if($_SESSION['Admin'])
                echo "Sei un amministratore";

        } else {
            echo "<div class='form'>
                    <h3>Username/password is incorrect.</h3><br/>
                    Click here to 
                    <a href='login.php'>Login</a>
                </div>";
        }
    } else {

?>
    <div class="form">
        <h1>Log In</h1>
        <form action="" method="post" name="login">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <input name="submit" type="submit" value="Login" />
        </form>
        <p>Not registered yet? <a href='registration.php'>Register Here</a></p>
    </div>
    <?php } ?>
</body>

</html>
