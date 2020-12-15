<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel=" stylesheet" href="../stile.css"/>
</head>
<body>

<?php

    define('__ROOT__', dirname(__FILE__));
    require_once __ROOT__."/model/UserData.php";

    $usrData = new UserElement();

    session_start();

    if(isset($_POST['username'])) {

        $email = stripslashes($_REQUEST['username']);
        $password = stripslashes($_REQUEST['password']);

        $res = $usrData->checkCredentials($email,$password);

        if ($res) {

            /** Salviamo l'id dell'utente connesso in modo da sapere chi è.*/
            $usrData->loadElement($res['ID']);
            $_SESSION['User'] = serialize($usrData);


            header('Location: Profile.php');

        } else {

            echo "<div class='form'>
                    <h3>Username/password is incorrect.</h3><br/>
                    Click here to 
                    <a href='login.php'>Login</a>
                </div>";

        }
    } else if(!isset($_SESSION['User'])) {

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
    <?php } else {


        ?>
        <div class="form">

            <form action="Logout.php">
                <input type="submit" value="Logout" />
            </form>

        </div>

    <?php } ?>

</body>

</html>
