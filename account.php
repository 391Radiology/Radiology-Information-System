<?php
    include("PHPconnectionDB.php");
    session_start();
?>
<html>
    <body>
        <?php
            if (isset($_SESSION["usr"])) {

                //establish types array
                $types = array('a' => 'Admin',
                    'd' => 'Doctor',
                    'r' => 'Radiologist',
                    'p' => 'Patient');

                //establish connection
                $conn = connect();
                if (!$conn) {
                    $e = oci_error();
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }

                echo "Welcome ";

                //sql command
                $sql = 'SELECT * FROM persons WHERE person_id = ' . $_SESSION["pid"] . '';

                //Prepare sql using conn and returns the statement identifier
                $stid = oci_parse($conn, $sql);

                //Execute a statement returned from oci_parse()
                $res = oci_execute($stid);

                //if error, retrieve the error using the oci_error() function & output an error message
                if (!$res) {
                    $err = oci_error($stid);
                    echo htmlentities($err['message']);
                } else {
                    $person = oci_fetch_array($stid);
                    echo '' . $person[1] . ' ' . $person[2] . ' <br/>';
                    echo '' . $types[$_SESSION["type"]] . ' <br/>';
                }
            }

            if (isset($_SESSION["usr"])) {
        ?>
            <form name="login" method="post" action="logout.php">
                <input type="submit" name="logout" value="Logout"/>
            </form>
            <?php
            }
            else {
                echo "Session not found, please login again";
            }
        ?>
    </body>
</html>
