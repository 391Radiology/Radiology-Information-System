<?php
	include_once("PHPconnectionDB.php");
	
 	function report_generating($diagnosis, $sdate, $edate){
 		// Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

      // Sql command
		$sql = 'SELECT p.first_name, p.last_name, p.address, p.phone, MIN(r.test_date)
			    FROM persons p, radiology_record r
			    WHERE p.person_id = r.patient_id AND LOWER(r.diagnosis) = \''.strtolower($diagnosis).'\' AND r.test_date >= \''.$sdate.'\'
			    GROUP BY p.first_name, p.last_name, p.address, p.phone
			    ORDER BY MIN(r.test_date)';

		// Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);
	 	
	 	  if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        } else {
        	// No error
        	// Fetch and output info
        	echo 'Results for:<br>Diagnosis: '.$diagnosis.'<br>Start Date: '.$sdate.' End Date: '.$edate.'<br>';
			while ($info = oci_fetch_array($stid)) {
	 		echo ''.$info["FIRST_NAME"].' '.$info["LAST_NAME"].' '.$info["ADDRESS"].' '.$info["PHONE"].' '.$info["MIN(R.TEST_DATE)"].'<br>';
        }

		oci_free_statement($stid);
		oci_close($conn);
	 	}
}
?>