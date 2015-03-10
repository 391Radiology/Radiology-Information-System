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
                    session_unset("Error");
                }
                ?>
                <input type="submit" name="login" value="Login"/>
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
