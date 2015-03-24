<?php
  
    function search_keyword($keyWord, $sdate, $edate){
    	//get patient info
	 	  /*if ($sid != null){
				medical_info($sid, 'p', $sdate, $edate, $datetype); 	
				die();
        	}*/
        	
        	
        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
				
			if ($sdate) $sql = ''.$sql.' AND '.$datetypes[$datetype].' >= \''.date_format($sdate,"j-M-Y").'\'';	
			if ($edate) $sql = ''.$sql.' AND '.$datetypes[$datetype].' <= \''.date_format($edate,"j-M-Y").'\'';
			
			//implement KEYWORD /compsci/webdocs/zioueche/web_docs

			$sql = 'SELECT 6*(score(1)+score(2))+3*score(3)+score(4) as rank
			FROM radiology_record r, persons p 
			WHERE p.person_id = r.patient_id AND contains(first_name,  \''$keyword.'\', 1)>0 OR contains(last_name,  \''.$keyword.'\', 2)>0 OR 			   contains(diagnosis, \''.$keyword.'\' 3) > 0 OR contains(description,  '\''.$keyword.'\', 4) > 0 
			ORDER BY (6*(score(1)+score(2))+3*score(3)+score(4))';
			echo $sql
    }
?>


SELECT 6*(score(1)+score(2))+3*score(3)+score(4) as rank
FROM radiology_record r, persons p 
WHERE p.person_id = r.patient_id AND contains(first_name, 'dead', 1)>0 OR contains(last_name, 'dead', 2)>0 OR contains(diagnosis,'dead', 3) > 0 OR contains(description, 'dead', 4) > 0 
ORDER BY (6*(score(1)+score(2))+3*score(3)+score(4));



