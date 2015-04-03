<?php
	//$date has three options:"Weekly", "Monthly" or "Weekly"
 	function data_analysis($fname,$test_type,$timeperiod){
	 	$conn=connect();
			if (!$conn) {
  			$e = oci_error();
  			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
   		}


			
			$sql = 'SELECT ';
			
			if (!empty($fname)){
				$sql.=' FIRST_NAME,LAST_NAME, ';
				}
			if (!empty($test_type)){
				$sql.=' TEST_TYPE, ';
				}
			if ($timeperiod == 'a'){
				$sql.='TEST_DATE, ';
				}
			if ($timeperiod == 'w'){
				$sql.=' to_char(test_date, \'IW\'),';
				}
			if ($timeperiod == 'm'){
				$sql.='to_char(test_date, \'MON\'), ';
				}
			if ($timeperiod == 'y'){
				$sql.=' EXTRACT(YEAR from test_date),';
				}
			

			
			$sql.='COUNT(IMAGE_ID) From Information_for_data_analysis ';
			
			if (!empty($fname)||!empty($test_type)||!empty($timeperiod)){
				$sql.="GROUP BY ( ";
				if (!empty($fname)){
					$sql.=' FIRST_NAME,LAST_NAME, ';
				}
				if (!empty($test_type)){
					$sql.=' TEST_TYPE,';
				}
				if ($timeperiod == 'a'){
					$sql.='TEST_DATE,';
				}
				if ($timeperiod == 'w'){
					$sql.=' to_char(test_date, \'IW\'),';
				}
				if ($timeperiod == 'm'){
					$sql.='to_char(test_date, \'MON\'),';
				}
				if ($timeperiod == 'y'){
					$sql.=' EXTRACT(YEAR from test_date),';
				}
			
					
				$sql = rtrim($sql, ",");
				$sql.=" ) ";
				}
			    
			//echo $sql;	
		
        
        
        
        $stid = oci_parse($conn, $sql);
 
        $res = oci_execute($stid);
        echo '<th>'. $FIRST_NAME .'</th>';
        
	 	  if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        } else {
        	// No error
        	// Fetch and output info
       // 	echo 'Results for:<br>Diagnosis: '.$diagnosis.'<br>Start Date: '.$sdate.' End Date: '.$edate.'<br>';
       	if ($info = oci_fetch_array($stid)) {
        	?>
        	<div style="height:500px; width:intrinsic; overflow:auto;">
	      <table border="1">
        	<?php
        		
				echo '<th width="100" align="center" valign="middle">Patient NAME</th>';
				
				
				echo '<th width="100" align="center" valign="middle">TEST TYPE</th>';
				
				if ($timeperiod == 'a'){
				echo '<th width="100" align="center" valign="middle">TIME PERIOD(ALL)</th>';
				}
				if ($timeperiod == 'w'){
				echo '<th width="100" align="center" valign="middle">TIME PERIOD(WEEKLY)</th>';
				}
				if ($timeperiod == 'm'){
				echo '<th width="100" align="center" valign="middle">TIME PERIOD(MONTHLY)</th>';
				}
				if ($timeperiod == 'y'){
				echo '<th width="100" align="center" valign="middle">TIME PERIOD(YEARLY)</th>';
				}

			
				echo '<th width="100" align="center" valign="middle">IMAGE COUNT</th>';

				        		
        		

					while ($info) {
					?>
					 	<tr onMouseover="this.bgColor='#ADD8E6'"onMouseout="this.bgColor='#FFFFFF'">
					 	<?php
					 		
					 		echo '<td>'.$info["FIRST_NAME"].' '.$info["LAST_NAME"].'</td>'	;
					 		echo '<td>'.$info["TEST_TYPE"].'</td>'	;
					 	if ($timeperiod != 'n'){
					 		
							if (!empty($fname)&&!empty($test_type)){
								echo '<td>' .$info[3].'</td>'	;
								}
							if (!empty($fname)&&empty($test_type)){
								echo '<td>' .$info[2].'</td>'	;
								}
							if (empty($fname)&&!empty($test_type)){
								echo '<td>' .$info[1].'</td>'	;
								}
							if (empty($fname)&&empty($test_type)){
								echo '<td>' .$info[0].'</td>'	;
								}	 		
					 		}
					 		echo '<td>'.$info["COUNT(IMAGE_ID)"].'</td>'	;
							
					 	?>
						


						</tr>
		 			<?php
		 				$info = oci_fetch_array($stid);
	        		}
	        	?>
					</table>
				</div>
			<?php
        	} else {
        		// Error message for having no matching results
			?>
				<div style="color:red;">
					No matching results
				</div>		
			<?php
        	}


		}
		

		oci_free_statement($stid);
		oci_close($conn);
	
		
	 	}
 	
?>
