<?php
    session_start();
    include_once("PHPconnectionDB.php");

    // Validates login
    function loginAttempt() {
        ini_set('display_errors', 1);
                error_reporting(E_ALL);

        // Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'SELECT * FROM users WHERE LOWER(user_name) = \''.strtolower($_POST["usr"]).'\' AND password = \''.$_POST["pwd"].'\'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql );

        // Execute a statement returned from oci_parse()
        $res=oci_execute($stid);

        if (!$res) {
            // Error, retrieve the error using the oci_error() function & output an error message
            $err = oci_error($stid);
            echo htmlentities($err['message']);
        } else {
            // No error
            // Fetch accounts matching usr and pwd (should be unique)
            $account = oci_fetch_array($stid);

            if (!$account) {
                // Account was not found, set error message
                $_SESSION["error"] = "Invalid username/password";
            } else {
                // Account was found, save account id and type in session
                $_SESSION["type"] = $account[2];
                $_SESSION["pid"] = $account[3];
            }
        }

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);

        // If login is successful, move to account page
        if (isset($_SESSION["pid"])) header("location:account.php?mode=account");
    }

    // Creates form for logging in
    function loginForm() {
    ?>
        <h1 style="margin-top: 200px; text-align:center;">RIS Login</h1>

        <form name="login" method="post" style="margin-top:10px; text-align:center;">
            <input type="text" placeholder="Username" maxlength="24" name="usr" style="height:25px; width:180px;"/><br/>
            <input type="password" placeholder="Password" maxlength="24" name="pwd" style="margin-top:1px; height:25px; width:180px;"/><br/>
            <div style="color:red;">
        <?php
            if (isset($_SESSION["error"])) {
                echo '' . $_SESSION["error"] . ' <br/>';
                session_unset("error");
            }
        ?>
            </div>

            <input type="submit" name="login" value="Login" style="margin-top:10px; height:25px; width:180px;"/>
        </form>
    <?php
    }
?>

<html>
    <TITLE>RIS Login</TITLE>
    <!-- Not logged in will show background -->
    <body style=<?php echo (!isset($_SESSION["pid"]) ? "\"background-image:url(bg1.jpg); -background-color:#cccccc; background-size:100% 100%\"": "\"\"" ); ?>>
            <?php
                if (!isset($_SESSION["pid"])) {
                    // Not logged in
                    // Try to login if previous request was made
                    if (isset($_POST["login"])) loginAttempt();

                    // Create login form (No previous login request or login request failed)
                    loginForm();



                    
                } else {
                    // Already logged in, redirect to account page
                    echo "Already logged in, redirecting...";
                    header("refresh:3; url=account.php?mode=account");
                }
            ?>
    </body>
</html>
