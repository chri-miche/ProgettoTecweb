<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel=" stylesheet" href="../stile.css"/>
</head>
<body>

<?php
    require_once "UserData.php";

    $usrData = new User();

    session_start();

    if(isset($_SESSION['User'])) {
        session_destroy();
    }

    if(isset($_POST['username'])) {

        $email = stripslashes($_REQUEST['username']);
        $password = stripslashes($_REQUEST['password']);

        $res = $usrData->userCredentialsCorrect($email,$password);

        if ($res) {
            /** Salviamo l'id dell'utente connesso in modo da sapere chi è.*/
            $usrData->loadUser($res['ID']);
            $_SESSION['User'] = $usrData;

            if($_SESSION['User']->getAdmin())
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
