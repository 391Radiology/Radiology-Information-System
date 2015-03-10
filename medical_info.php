<?php
include("PHPconnectionDB.php");

function medical_info($pid, $colname) {

    //establish types array
    $types = array('patient_id' => 'Radiologist: ',
            'radiologist_id' => 'Patient: ');
    $index = array('patient_id' => 3, 'radiologist_id' => 1);

    //establish connection
    $conn = connect();
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    //sql command
    $sql = 'SELECT * FROM radiology_record WHERE '.$colname.' = '.$pid.'';

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

            echo 'Doctor: '.$record[2].' '.$types[$colname].' '.$record[$index[$colname]].' Test Type: '.$record[4].' Prescribing Date: '.$record[5].'
                Test Date: '.$record[6].' Diagnosis: '.$record[7].' Description: '.$record[8].' <br/>';
        }
    }

    // Free the statement identifier when closing the connection
    oci_free_statement($stid);
    oci_close($conn);
}
?>