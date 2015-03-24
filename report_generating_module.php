<?php
 	function report_generating($date,$diagnosis){
	 	$conn=connect();
			if (!$conn) {
  			$e = oci_error();
  			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
   		} 	
	 	//sql 
		$date = DateTime::createFromFormat('Y-M-J', $date);
		$sql = 'SELECT persons.first_name, persons.last_name, persons.address, persons.phone, radiology_record.test_date, radiology_record.diagnosis
			    FROM persons, radiology_record
			    WHERE persons.person_id = radiology_record.patient_id AND radiology_record.diagnosis = \''.$diagnosis.'\' AND radiology_record.test_date > '.date_format($date,"J-M-Y");
	
		$sql.= 'GROUP BY first_name, last_name, address, phone, diagnosis';
	 	$stid = oci_parse($conn, $sql );
	 	$res=oci_execute($stid);
	 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
			foreach ($row as $item) {

				echo $item;

			}
			//echo 'Name: '.$row[1].$row[2].'Address'.$row[3].'Phone:'.$row[4]."test date:".row[5].
			}
		echo '<br/>';
		oci_free_statement($stid);
		oci_close($conn);
	 	}
 	}
?>































