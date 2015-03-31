<?php
  	 include_once("PHPconnectionDB.php");
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
			$sql = 'SELECT 6*(score(1)+score(2))+3*score(3)+score(4) as rank, p.first_name, p.last_name, test_date, test_type, r.record_id as rid 
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
			$sql = $sql2.$rest_of_query;		
			?>
			
			<html>
			<table border="1" class="clickable-row" >
				<th align='center' valign='middle' width='100'>Result</th>
				<th align='center' valign='middle' width='100'>DEBUG ONLY</th>
				<th align='center' valign='middle' width='100'>First Name</th>
				<th align='center' valign='middle' width='100'>Last Name</th>
				<th align='center' valign='middle' width='100'>Test Date</th>
				<th align='center' valign='middle' width='100'>Test Type</th>
				<th align='center' valign='middle' width='100'>Record</th>
				<th align='center' valign='middle' width='100'>Images</th>

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
        		//$part1 = $_SERVER['REQUEST_URI'];
        		//$part2 = $_SERVER['QUERY_STRING'];
        		//echo $_SERVER['REQUEST_URI'];
            while ($record = oci_fetch_array($stid)) {	
					echo "<tr>";
					echo "<td align='center' valign='middle'>".$pos."</td>";
               echo "<td >".$record["RANK"]."</td>";
               echo "<td>".$record["FIRST_NAME"]."</td>";
               echo "<td>".$record["LAST_NAME"]."</td>";
               echo "<td align='center' valign='middle'>".$record["TEST_DATE"]."</td>";
               echo "<td>".$record["TEST_TYPE"]."</td>";
               echo "<td>".$record["RID"]."<td>";
               $sql = 'SELECT thumbnail as tb, image_id as id 
               			FROM pacs_images pc
               			WHERE pc.record_id = '.$record["RID"];
               //echo $sql; 
               $test = oci_parse($conn, $sql);
               $result = oci_execute($test);
               if (!$res) {
            		$err = oci_error($stid);
            		echo htmlentities($err['message']);
            		
       			 } else{
       			 			for ($i = 0; $i < count($try = oci_fetch_array($test)); $i++){
									  echo $i;
								}	 		
       			 		}

       			 	
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