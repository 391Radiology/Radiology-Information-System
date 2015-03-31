<?php
  
    function search_keyword($keyWord, $sdate, $edate){        	
        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //echo $sdate;
			//$sdate = DateTime::createFromFormat('Y-m-j', $sdate);
       	//$edate = DateTime::createFromFormat('Y-m-j', $edate);
					
			//implement KEYWORD /compsci/webdocs/zioueche/web_doc
			$sql = 'SELECT 6*(score(1)+score(2))+3*score(3)+score(4) as rank, p.first_name, p.last_name, test_date, test_type
						FROM radiology_record r, persons p 
						WHERE p.person_id = r.patient_id 
						AND (contains(first_name, \'%s\', 1)>0 
						OR contains(last_name, \'%s\', 2) > 0 
						OR contains(diagnosis, \'%s\', 3) > 0 
						OR contains(description, \'%s\', 4) > 0
						)';
			
			if ($sdate) $sql =' '.$sql.' AND test_date >= \''.$sdate.'\'';		
			if ($edate) $sql = ' '.$sql.' AND test_date <= \''.$edate.'\'';
			
			$rest_of_query = ' ORDER BY (6*(score(1)+score(2))+3*score(3)+score(4))';
			$sql2 = sprintf($sql, $keyWord,$keyWord,$keyWord,$keyWord);
			$sql = $sql2.	$rest_of_query;		
			//$sql2 = sprintf($sql, $keyWord);	
			//echo $sql;
			?>
			
			<html>
			<table border="1" class="query_results" >
				<th align='center' valign='middle' width='100'>Result</th>
				<th align='center' valign='middle' width='100'>DEBUG ONLY</th>
				<th align='center' valign='middle' width='100'>First Name</th>
				<th align='center' valign='middle' width='100'>Last Name</th>
				<th align='center' valign='middle' width='100'>Test Date</th>
				<th align='center' valign='middle' width='100'>Test Type</th>

			<?php
			//prep connection
			$stid = oci_parse($conn, $sql);

        //Execute a statement returned from oci_parse()
        $res = oci_execute($stid);
        //if error, retrieve the error using the oci_error() function & output an error message
        if (!$res) {
            $err = oci_error($stid);
            echo htmlentities($err['message']);
        } else {
        		$pos = 1;
            while ($record = oci_fetch_array($stid)) {
					echo "<tr class= query_results>";
					echo "<td align='center' valign='middle'>".$pos."</td>";
               echo "<td>".$record[0]."</td>";
               echo "<td>".$record[1]."</td>";
               echo "<td>".$record[2]."</td>";
               echo "<td align='center' valign='middle'>".$record[3]."</td>";
               echo "<td>".$record[4]."</td>";
               echo "</tr>";
               $pos += 1;
                //echo $record[0].'  |   ', $record[1].'   |  ', $record[2].'   |  ', $record[3].' | ',$record[4].' | ' , '<br/>';
            }
            }
        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }
?>
			</table>
</html>
