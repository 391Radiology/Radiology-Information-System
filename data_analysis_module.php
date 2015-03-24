<?php
 	function data_analysis($patient,$date,$test_type){
	 	$conn=connect();
			if (!$conn) {
  			$e = oci_error();
  			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
   		}

		//First create a table contains the information for data analysis
		
		$sql = 'CREATE Information_for_data_analysis AS 
                SELECT p.FIRST_NAME, p.LAST_NAME, r.TEST_TYPE, r.TEST_DATE, i.IMAGE_ID 
		FROM PERSONS p, RADIOLOGY_RECORD r, PACS_IMAGES i
                WHERE P.PERSON_ID = R.PATIENT_ID AND I.RECORD_ID = R.RECORD_ID';
                
		$stid = oci_parse($conn, $sql);

		$res = oci_execute($stid);

		//$date = DateTime::createFromFormat('Y-M-J', $date);
	
	 	//sql and meanwhile create table to show
		$sql = ’SELECT’;
		//set up which factor to choose
		if(!empty($patient)){
			$sql = $sql.’FIRST_NAME, LAST_NAME’;
			echo '<th> First Name </th>';
            		echo '<th> Last Name </th>';
		}
		if (!empty(date)){
			$sql = $sql.’TEST_DATE’;
			echo ‘<th> Date </th>’;

		}
		if (!empty(test_type)){
			$sql = $sql.’TEST_TYPE’;
			echo ‘<th> Test Type </th>’
		}
		$sql.’COUNT(image_id)’;
		$sql.’FROM Information_for_data_analysis’;
		$sql.’GROUP BY’;
		if(!empty($patient)){
			$sql = $sql.’FIRST_NAME, LAST_NAME’;

		}
		if (!empty(date)){
			$sql = $sql.’TEST_DATE’;

		}
		if (!empty(test_type)){
			$sql = $sql.’TEST_TYPE’;
		}
		

		


		$stid = oci_parse($conn, $sql );
	 	$res=oci_execute($stid);
	 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
			foreach ($row as $item) {
				echo ‘<td>’;
				echo $item;
				echo ‘</td>’;

			
			
			}
		echo '<tr/>';
	 	}
 	}
?>































