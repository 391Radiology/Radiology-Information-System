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
		
		

		
		//r.test_date >= \''.$sdate.'\' AND r.test_date <= \''.$edate.'\'
		$sql = 'SELECT i.FIRST_NAME,i.LAST_NAME,i.TEST_TYPE,i.TEST_DATE,i.image_count
			    FROM Information_for_data_analysis i
			     ';
			     

		
	
		
		
		
		if(!empty($fname)&&!empty($lname)&&empty($test_type)&&$_GET['sdate']==''&&$_GET['edate']==''){
			echo'<th> name</th>';
			$sql.='WHERE LOWER(i.FIRST_NAME) = \''.strtolower($fname).'\' AND LOWER(i.LAST_NAME) = \''.strtolower($lname).'\'';
			}
		if(empty($fname)&&empty($lname)&&!empty($test_type)&&$_GET['sdate']==''&&$_GET['edate']==''){
			echo'<th> type</th>';
			$sql.='WHERE i.TEST_TYPE = \''.($test_type).'\' ';
			}
		if(empty($fname)&&empty($lname)&&empty($test_type)&&$_GET['sdate']!=''&&$_GET['edate']!=''){
			echo'<th> time</th>';
			$sql.='WHERE i.TEST_DATE >= \''.$sdate.'\' AND i.TEST_DATE <= \''.$edate.'\' ';
			}
		if(!empty($fname)&&!empty($lname)&&!empty($test_type)&&$_GET['sdate']==''&&$_GET['edate']==''){
			echo'<th> name type!!!!!!</th>';
			$sql.='WHERE  LOWER(i.FIRST_NAME) = \''.strtolower($fname).'\' AND LOWER(i.LAST_NAME) = \''.strtolower($lname).'\' AND i.TEST_TYPE = \''.($test_type).'\'';
			}
		if(empty($fname)&&empty($lname)&&!empty($test_type)&&$_GET['sdate']!=''&&$_GET['edate']!=''){
			echo'<th> time type</th>';
			$sql.='WHERE i.TEST_TYPE = \''.($test_type).'\' AND i.TEST_DATE >= \''.$sdate.'\' AND i.TEST_DATE <= \''.$edate.'\'  ';
			}
		if(!empty($fname)&&!empty($lname)&&empty($test_type)&&$_GET['sdate']!=''&&$_GET['edate']!=''){
			echo'<th> name time</th>';
			$sql.='WHERE  i.TEST_DATE >= \''.$sdate.'\' AND i.TEST_DATE <= \''.$edate.'\' AND LOWER(i.FIRST_NAME) = \''.strtolower($fname).'\' AND LOWER(i.LAST_NAME) = \''.strtolower($lname).'\'';
			}
		if(!empty($fname)&&!empty($lname)&&!empty($test_type)&&$_GET['sdate']!=''&&$_GET['edate']!=''){
			echo'<th> name time type</th>';
			$sql.='WHERE i.TEST_TYPE = \''.($test_type).'\' AND i.TEST_DATE >= \''.$sdate.'\' AND i.TEST_DATE <= \''.$edate.'\' AND LOWER(i.FIRST_NAME) = \''.strtolower($fname).'\' AND LOWER(i.LAST_NAME) = \''.strtolower($lname).'\'';
			}
		if(empty($fname)&&empty($lname)&&empty($test_type)&&$_GET['sdate']==''&&$_GET['edate']==''){
			echo'<th> nothing</th>';
			}
		

					
			
			//echo'<th> one not empty</th>';

		

		



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
*/
	//Create sql part by part
	
	 	
		echo '<br/>';
		oci_free_statement($stid);
		oci_close($conn);
	 	}
 	
?>
































