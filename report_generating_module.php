<?php
 	include ("PHPconnectionDB.php");
 	$conn=connect();
 	//sql 
	 $sql = ‘SELECT first_name, last_name, address, phone, test_date FROM persons p, radiology_record r WHERE p.person_id = r.patient_id AND r.diagnosis = “breast cancer” AND r.date > 2014;
 	$stid = oci_parse($conn, $sql );
 	$res=oci_execute($stid);
 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
		foreach ($row as $item) {
			echo $item.' &nbsp;';
		}
	echo '<br/>';
 	}
?>

