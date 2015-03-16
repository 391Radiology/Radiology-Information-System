<?php
    include("PHPconnectionDB.php");
    session_start();
?>

<html>
    <body>
        <?php
            if (isset ($_POST['login'])){
                //get the input
                $_SESSION["usr"] = $_POST['usr'];
                $_SESSION["pwd"] = $_POST['pwd'];

                ini_set('display_errors', 1);
                error_reporting(E_ALL);

                //establish connection
                $conn = connect();
                if (!$conn) {
                    $e = oci_error();
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }


                //sql command
                $sql = 'SELECT * FROM users WHERE LOWER(user_name) = \''.strtolower($_SESSION["usr"]).'\' AND password = \''.$_SESSION["pwd"].'\'';

                //Prepare sql using conn and returns the statement identifier
                $stid = oci_parse($conn, $sql );

                //Execute a statement returned from oci_parse()
                $res=oci_execute($stid);


                //if error, retrieve the error using the oci_error() function & output an error message
                if (!$res) {
                    $err = oci_error($stid);
                    echo htmlentities($err['message']);
                }
                else {
                    $account = oci_fetch_array($stid);

                    if (!$account) {
                    		session_unset("usr");
                    		session_unset("pwd");
                        $_SESSION["Error"] = "Invalid username/password, please try again :(";
                        header("location:login.php");
                    } else {
                        $_SESSION["type"] = $account[2];
                        $_SESSION["pid"] = $account[3];
                        header("location:account.php");
                    }
                }

                // Free the statement identifier when closing the connection
                oci_free_statement($stid);
                oci_close($conn);
            }
        ?>
    </body>
</html>
