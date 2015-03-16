<?php
    include("keyword_search.php");
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

                //sql command
                $sql = 'SELECT * FROM persons WHERE person_id = '.$_SESSION["pid"].'';

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

                    echo 'Welcome  '. $person[1] . ' ' . $person[2] . ' <br/>';
                    echo ''.$types[$_SESSION["type"]].' <br/>';
                    echo 'Address: '.$person[3] .' Phone: '.$person[5] .' Email: '.$person[4] .' <br/>';
                ?>
                    <form name="search" method="post" action="account.php">
                        <?php
                        if ($_SESSION["type"] == 'a' or $_SESSION["type"] == 'd') {
                            ?>
                            <input type="number" placeholder="Patient ID"
                                <?php
                                    if (isset($_POST['pid']) and is_numeric($_POST['pid'])) {
                                        echo 'value=', $_POST['pid'];
                                    }
                                ?> name="pid"/>
                            <input type="number" placeholder="Radiologist ID"
                                <?php
                                    if (isset($_POST['rid']) and is_numeric($_POST['rid'])) {
                                        echo 'value=', $_POST['rid'];
                                    }
                                ?> name="rid"/>
                        <?php
                            }
                        ?>
                        Start Date : <input type="date" placeholder="yyyy-mm-dd" maxlength=10
                            <?php
                            if (isset($_POST['sdate']) and DateTime::createFromFormat('Y-m-j', $_POST['sdate'])) {
                                echo 'value=', $_POST['sdate'];
                            }
                            ?> name="sdate"/>
                        End Date : <input type="date" placeholder="yyyy-mm-dd" maxlength=10 max="9999-12-31"
                            <?php
                                if (isset($_POST['edate']) and DateTime::createFromFormat('Y-m-j', $_POST['edate'])) {
                                    echo 'value=', $_POST['edate'];
                                }
                            ?> name="edate"/><br/>
                        <input type="radio" name="datetype" value=0
                            <?php if(!isset($_POST['datetype']) or $_POST['datetype'] == 0) echo 'checked'; ?>/> Prescription Date
                        <input type="radio" name="datetype" value=1
                            <?php if(isset($_POST['datetype']) and $_POST['datetype'] == 1) echo 'checked'; ?>/> Test Date
                        <input type="submit" name="search" value="Search"/>
                    </form>
                <?php
                    if (isset($_POST['search'])) {
                        if ($_SESSION["type"] == 'p' or $_SESSION["type"] == 'r') {
                            medical_info($_SESSION["pid"], $_SESSION["type"], $_POST['sdate'], $_POST['edate'], $_POST['datetype']);
                        } else {
                            echo "Work on dis desu :3";
                        }
                    }
                ?>
                    <form name="logout" method="post" action="logout.php">
                        <input type="submit" name="logout" value="Logout"/>
                    </form>
                <?php
                }

                // Free the statement identifier when closing the connection
                oci_free_statement($stid);
                oci_close($conn);
            }
            else {
                echo "Session not found, please login";
                header("refresh:3; url=login.php");
            }
        ?>
    </body>
</html>
