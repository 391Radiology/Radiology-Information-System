<?php
 	function report_generating($date,$diagnosis){
	 	$conn=connect();
			if (!$conn) {
  			$e = oci_error();
  			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
   		} 	
	 	//sql 
	 	$date = DateTime::createFromFormat('Y-m-j', $date);
		$sql = 'SELECT first_name, last_name, address, phone, test_date,diagnosis 
			FROM persons p, radiology_record r 
			WHERE p.person_id = r.patient_id AND r.diagnosis = '.$diagnosis.' AND r.test_date >'.$date.' GROUP BY first_name, last_name, address, phone, diagnosis';
	 	$stid = oci_parse($conn, $sql );
	 	$res=oci_execute($stid);
	 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
			foreach ($row as $item) {

				echo $item;

			}
			//echo 'Name: '.$row[1].$row[2].'Address'.$row[3].'Phone:'.$row[4]."test date:".row[5].
			}
		echo '<br/>';
	 	}
 	}
?>































