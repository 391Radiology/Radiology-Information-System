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
			    WHERE p.person_id = r.patient_id AND LOWER(r.diagnosis) = \''.strtolower($diagnosis).'\' 
			    		AND r.test_date >= \''.$sdate.'\' AND r.test_date <= \''.$edate.'\'
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
        	if ($info = oci_fetch_array($stid)) {
        	?>
        		<div style="height:600px; width:intrinsic; overflow:auto;">
	        		<table border="1">
	        		<th width="100" align="center" valign="middle">First Name</th>
	        		<th width="100" align="center" valign="middle">Last Name</th>
	        		<th width="100" align="center" valign="middle">Address</th>
	        		<th width="100" align="center" valign="middle">Phone</th>
	        		<th width="100" align="center" valign="middle">Test Date</th>
	        	<?php
					while ($info) {
					?>
					 	<tr onMouseover="this.bgColor='#ADD8E6'"onMouseout="this.bgColor='#FFFFFF'">
							<td><?php echo $info["FIRST_NAME"]; ?></td>	
							<td><?php echo $info["LAST_NAME"]; ?></td>
							<td><?php echo $info["ADDRESS"]; ?></td>
							<td><?php echo $info["PHONE"]; ?></td>	
							<td><?php echo $info["MIN(R.TEST_DATE)"]; ?></td>	
						</tr>
		 			<?php
		 				$info = oci_fetch_array($stid);
	        		}
	        	?>
					</table>
				</div>
			<?php
        	} else {
        		// Error message for having no matching results
			?>
				<div style="color:red;">
					No matching results
				</div>		
			<?php
        	}

		oci_free_statement($stid);
		oci_close($conn);
	 	}
}
?>