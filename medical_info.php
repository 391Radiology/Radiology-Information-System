<?php
    include("PHPconnectionDB.php");

    function medical_info($pid, $colid, $sdate, $edate, $datetype) {

        //establish types array
        $colname = array('p' => 'patient_id',
                    'r' => 'radiologist_id');
        $types = array('p' => 'Radiologist: ',
                    'r' => 'Patient: ');
        $index = array('p' => 3, 'r' => 1);
        $datetypes = array(0 => "prescribing_date", 1 => "test_date");

        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        //sql command
        $sql = 'SELECT * FROM radiology_record WHERE '.$colname[$colid].' = '.$pid.'';
        if (DateTime::createFromFormat('Y-m-j', $sdate)) {
            $sql = ''.$sql.' AND '.$datetypes[$datetype].' >= \''.date_format(DateTime::createFromFormat('Y-m-j', $sdate),"j-M-Y").'\'';
        }
        if (DateTime::createFromFormat('Y-m-j', $edate)) {
            $sql = ''.$sql.' AND '.$datetypes[$datetype].' <= \''.date_format(DateTime::createFromFormat('Y-m-j', $edate),"j-M-Y").'\'';
        }

        //Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        //Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        //if error, retrieve the error using the oci_error() function & output an error message
        if (!$res) {
            $err = oci_error($stid);
            echo htmlentities($err['message']);
        } else {
            while ($record = oci_fetch_array($stid)) {
                echo 'Doctor: '.$record[2].' '.$types[$colid].' '.$record[$index[$colid]].' Test Type: '.$record[4].' Prescribing Date: '.$record[5].'
                        Test Date: '.$record[6].' Diagnosis: '.$record[7].' Description: '.$record[8].' <br/>';
            }
        }

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }
?>
