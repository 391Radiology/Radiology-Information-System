<?php
    include("medical_info.php");

    function search_keyword($pfname, $plname,$pid,$rid, $did, $keyWord){
	 
        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
	
		//sql command
		$sql = 'SELECT * FROM radiology_record WHERE doctor_id = '.$did;
		if($pid){
			$sql = ''.$sql.'AND patient_id = '.$pid;
		}
	
		//if we have last name only	
		if($plname and !$pfname){
			$sql = 'SELECT p.first_name, p.last_name,p.person_id FROM family_doctor f, persons p WHERE f.doctor_id = '.$did.'AND p.person_id = 		f.patient_id AND p.last_name = '.$plname;
		}
	
		//if we have only first name
		if($pfname and !$plname){
			$sql = 'SELECT p.first_name, p.last_name,p.person_id FROM family_doctor f, persons p WHERE f.doctor_id = '.$did.'AND p.person_id = 		f.patient_id AND p.first_name = '.$pfname;
			}

		//if we have first and last name
		if($plname and $plname){
			$sql = 'SELECT p.first_name, p.last_name, p.person_id FROM family_doctor f, persons p WHERE f.doctor_id = '.$did.'AND p.person_id = 		f.patient_id AND p.first_name = '.$pfname.'AND p.last_name ='.$plname;
		}

 	//Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        //Execute aÂ statement returned fromÂ oci_parse()
        $res = oci_execute($stid);

        //if error, retrieve the error using the oci_error() function & output an error message
        if (!$res) {
            $err = oci_error($stid);
            echo htmlentities($err['message']);
        } else {
            while ($record = oci_fetch_array($stid)) {
                echo 'Doctor: '.$record[2].' '.$types[$colid].' '.$record[$index[$colid]].' Test Type: '.$record[4].' Prescribing Date: '.$record[5].'
                        Test Date: '.$record[6].' Diagnosis: '.$record[7].' Description: '.$record[8].' <br/>';
            }
        }

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }
?>