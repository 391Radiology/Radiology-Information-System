<?php
    session_start();
?>

<html>
    <TITLE>RIS Login</TITLE>
    <body>
        <?php
            if (!isset($_SESSION["usr"])) {
        ?>
            <h1>RIS Login</h1>
            <form name="login" method="post" action="logon.php">
                Username : <input type="text" name="usr"/> <br/>
                Password : <input type="password" name="pwd"/><br/>
                <?php
                if (isset($_SESSION["Error"])) {
                    echo '' . $_SESSION["Error"] . ' <br/>';
                    $_SESSION["Error"] = '';
                }
                ?>
                <input type="submit" name="login" value="Login"/>
            </form>
            <form name="register" method="post" action="register.php">
                <input type="submit" name="register" value="Register"/>
            </form>
        <?php
            }
            else {
                echo "Already logged in, redirecting...";
                header("refresh:3; url=account.php");
            }
        ?>
    </body>
</html>
