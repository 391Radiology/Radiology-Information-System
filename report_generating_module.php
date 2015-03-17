<?php
 	function search_keyword($date,$diagnosis){
	 	$conn=connect();
	 	if(isset($_POST['validate'])){        	
			$date=$_POST['Enter the date'];            		
			$diagnosis=$_POST['Enter the diagnosis'];
		           	       
		}	
	 	//sql 
		$sql = ‘SELECT first_name, last_name, address, phone, test_date FROM persons p, radiology_record r WHERE p.person_id = r.patient_id AND r.diagnosis = $diagnosis AND r.date >$date;
	 	$stid = oci_parse($conn, $sql );
	 	$res=oci_execute($stid);
	 	while (($row = oci_fetch_array($stid, OCI_ASSOC))) {
			echo 'Name: '.$row[1].$row[2].'Address'.$row[3].'Phone:'.$row[4]."test date:".row[5].
			}
		echo '<br/>';
	 	}
 	}
?>

