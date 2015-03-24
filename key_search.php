<?php
  
    function search_keyword($keyWord, $sdate, $edate){        	
        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        echo $sdate;
			$sdate = DateTime::createFromFormat('Y-m-j', $sdate);
        	$edate = DateTime::createFromFormat('Y-m-j', $edate);
					
			//implement KEYWORD /compsci/webdocs/zioueche/web_doc
			$sql = 'SELECT 6*(score(1)+score(2))+3*score(3)+score(4) as rank
						FROM radiology_record r, persons p 
						WHERE p.person_id = r.patient_id 
						AND contains(first_name, %s, 1)>0 
						OR contains(last_name, %s, 2)>0 
						OR contains(diagnosis, %s, 3) > 0 
						OR contains(description, %s, 4) > 0
						';
			
			if ($sdate) $sql =' '.$sql.' AND  >= \''.date_format($sdate,"j-M-Y").'\'';		
			
			if ($edate) $sql = ' '.$sql.' AND <= \''.date_format($edate,"j-M-Y").'\'';
			
			$rest_of_query = ' ORDER BY (6*(score(1)+score(2))+3*score(3)+score(4))';
			$sql2 = sprintf($sql, $keyWord,$keyWord,$keyWord,$keyWord);
			$sql2 = $sql2.	$rest_of_query;		
			//$sql2 = sprintf($sql, $keyWord);	
			echo $sql2;
    }
?>