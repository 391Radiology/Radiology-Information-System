<?php
	//$date has three options:"Weekly", "Monthly" or "Weekly"
 	function data_analysis($fname,$lname,$test_type,$sdate,$edate){
	 	$conn=connect();
			if (!$conn) {
  			$e = oci_error();
  			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
   		}

		//Create a Table to get all the information that useful to data_analysis
		
		$sql = 'CREATE OR REPLACE VIEW Information_for_data_analysis ( FIRST_NAME, LAST_NAME, TEST_TYPE, TEST_DATE ,image_count) AS 
                SELECT p.FIRST_NAME, p.LAST_NAME, r.TEST_TYPE, r.TEST_DATE ,count(i.IMAGE_ID)
					 FROM PERSONS p, RADIOLOGY_RECORD r, PACS_IMAGES i
                WHERE p.PERSON_ID = r.PATIENT_ID AND i.RECORD_ID = r.RECORD_ID 
                GROUP BY p.FIRST_NAME, p.LAST_NAME, r.TEST_TYPE, r.TEST_DATE';
                
		$stid = oci_parse($conn, $sql);

		$res = oci_execute($stid);
		
		
		


      // Sql command
		$sql = 'SELECT i.FIRST_NAME,i.LAST_NAME,i.TEST_TYPE,i.TEST_DATE,i.image_count
			    FROM Information_for_data_analysis i
			    WHERE LOWER(i.TEST_TYPE) = \''.strtolower($test_type).'\' ';

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
        		<table border="1">
        		<th width="100" align="center" valign="middle">First Name</th>
        		<th width="100" align="center" valign="middle">Last Name</th>
        		<th width="100" align="center" valign="middle">Test Type</th>
        		<th width="100" align="center" valign="middle">Test Date</th>
        		<th width="100" align="center" valign="middle">Image Number</th>
        	<?php
				while ($info) {
				?>
				 	<tr>
						<td><?php echo $info["FIRST_NAME"]; ?></td>	
						<td><?php echo $info["LAST_NAME"]; ?></td>
						<td><?php echo $info["TEST_TYPE"]; ?></td>
						<td><?php echo $info["TEST_DATE"]; ?></td>	
						<td><?php echo $info["IMAGE_COUNT"]; ?></td>
					</tr>
	 			<?php
	 				$info = oci_fetch_array($stid);
        		}
        	?>
				</table>
			<?php
        	} else {
        		// Error message for having no matching results
			?>
				<div style="color:red;">
					No matching results
				</div>		
			<?php
        	}

	 	}
	 	/*
		
		
		$sql = 'SELECT * FROM Information_for_data_analysis I WHERE LOWER(I.FIRST_NAME) = \''.strtolower($fname).'\'  ';
		
	   $stid = oci_parse($conn, $sql);

		$res = oci_execute($stid);
	 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
			foreach ($row as $item) {
				echo '<td>';
				echo $item;
				echo '</td>';
			}
				}

	//Create sql part by part
	
	 	/*
		$sql = 'SELECT';
		//set up which factor to choose
		if(!empty($patient)){
			$sql = $sql.'i.FIRST_NAME, i.LAST_NAME';
			echo '<th> First Name </th>';
            		echo '<th> Last Name </th>';
		}
		if (!empty($date)){
			if ($date == "Yearly"){
				$sql = $sql.'TRUNC(TEST_DATE,\'Y\'),)';
				}
			if ($date == "Monthly"){
				$sql = $sql.'TRUNC(TEST_DATE,\'M\'),)';
				}
			if ($date == "Weekly"){
				$sql = $sql.'TRUNC(TEST_DATE,\'IW\'),)';
				}

			echo '<th> Date </th>';

		}
		if (!empty($test_type)){
			$sql = $sql.'TEST_TYPE';
			echo '<th> Test Type </th>'
		}
		$sql.='COUNT(image_id)';
		$sql.='FROM Information_for_data_analysis i';
		$sql.='GROUP BY';
		if(!empty($patient)){
			$sql = $sql.'FIRST_NAME, LAST_NAME';

		}
		if (!empty($date)){
			if ($date == "Yearly"){
				$sql = $sql.'TRUNC(TEST_DATE,\'Y\'),)';
				}
			if ($date == "Monthly"){
				$sql = $sql.'TRUNC(TEST_DATE,\'M\'),)';
				}
			if ($date == "Weekly"){
				$sql = $sql.'TRUNC(TEST_DATE,\'IW\'),)';
				}

			

			//$sql = $sql.'TEST_DATE';

		}
		if (!empty($test_type)){
			$sql = $sql.'TEST_TYPE';
		}
		

		


		$stid = oci_parse($conn, $sql );
	 	$res=oci_execute($stid);
	 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
			foreach ($row as $item) {
				echo '<td>';
				echo $item;
				echo '</td>';

			
			
			}
			*/
		echo '<br/>';
		oci_free_statement($stid);
		oci_close($conn);
	 	}
 	
?>
































