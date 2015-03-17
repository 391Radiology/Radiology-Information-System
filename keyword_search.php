<?php
  
    function search_keyword($pid,$type,$pfname, $plname,$sid, $keyWord,$sdate, $edate, $datetype){
    	//get patient info
	 	  if ($sid != null){
				medical_info($sid, 'p', $sdate, $edate, $datetype); 	
				die();
        	}
        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
			$datetypes = array(0 => "prescribing_date", 1 => "test_date");
        	$sql = 'SELECT * FROM radiology_record r, persons p WHERE r.patient_id = p.person_id';
    		$sdate = DateTime::createFromFormat('Y-m-j', $sdate);
        	$edate = DateTime::createFromFormat('Y-m-j', $edate);
        	if ($type == 'd') {
        		$sql = ''.$sql.' AND doctor_id = '.$pid.'';        	
			}        		   
			if ($pfname){
				$sql = ''.$sql.' AND p.first_name  = \''.$pfname.'\'';
				}
			if ($plname){
				$sql = ''.$sql.' AND p.last_name  = \''.$plname.'\'';
				}
			if ($sdate) $sql = ''.$sql.' AND '.$datetypes[$datetype].' >= \''.date_format($sdate,"j-M-Y").'\'';	
			if ($edate) $sql = ''.$sql.' AND '.$datetypes[$datetype].' <= \''.date_format($edate,"j-M-Y").'\'';
			
			//implement KEYWORD /compsci/webdocs/zioueche/web_docs
    }
?>