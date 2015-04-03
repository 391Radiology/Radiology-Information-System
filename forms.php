<?php
	session_start();
	include_once("PHPconnectionDB.php");
	include_once("key_search.php");
	include_once("report_generating_module.php");
	include_once("dates_manager.php");
	include_once("data_analysis_module.php");
	include_once("updates.php");	
	
	// Establish global types array
	$types = array(
			'a' => 'Admin',
			'd' => 'Doctor',
			'r' => 'Radiologist',
			'p' => 'Patient');	
	
	// Creates forms for the account and person info of specified username which can be editted (called when mode == account)
    function userForm($usr, $pid, $type) {	
        // Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

   		// Try to update info if previous request was made
    		if (isset($_POST["saveInfo"])) updateInfo($usr, $pid);
			else if (isset($_POST["savePwd"])) updatePwd($usr);
			else if (isset($_POST["deleteAcc"])) deleteAcc($usr);
		
        // Sql command
        $sql = 'SELECT u.user_name, p.person_id, u.class, p.first_name, p.last_name, p.address, p.phone, p.email  
        		FROM users u, persons p 
        		WHERE u.user_name = \''.$usr.'\' AND p.person_id = '.$pid.'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        } else {
        	// No error
        	// Fetch info matching usr (should be unique)
        	$info = oci_fetch_array($stid);

        	// Retrieve types
        	global $types;

        	if ($info) {
			?>
				<div style="float:left;">
				
				<h1>
	         	   Account Info
	        	</h1>

				<form name="info" method="post" style="text-align:right;">
					<fieldset>
					<legend>Update Info:</legend>
					<!-- Create a selection for account types -->
					Account Type : <select name = "class">
	    								<?php
											foreach ($types as $key => $class) {
												// Add every type from types into the selection
											?>
												<option <?php echo 'value='.$key.' '.($key == $info['CLASS'] ? 'selected': '').''; ?>><?php echo ''.$class.''; ?></option>
											<?php
											}
										?>
	  									</select><br>

					<!-- Basic personal information -->
					First Name : <input type="text" name="fname" placeholder="First Name" maxlength="24"
										<?php if (isset($info['FIRST_NAME'])) echo 'value="'.$info['FIRST_NAME'].'"'; ?>
										style="margin-top:10px; height:25px; width:180px;" required <?php if ($type != 'a') echo "readonly"; ?>><br>
					Last Name : <input type="text" name="lname" placeholder="Last Name" maxlength="24"
										<?php if (isset($info['LAST_NAME'])) echo 'value="'.$info['LAST_NAME'].'"'; ?>
										style="margin-top:10px; height:25px; width:180px;" required <?php if ($type != 'a') echo "readonly"; ?>><br>
					Address : <input type="text" name="address" placeholder="Address" maxlength="128"
										<?php if (isset($info['ADDRESS'])) echo 'value="'.$info['ADDRESS'].'"'; ?>
										style="margin-top:10px; height:25px; width:180px;"><br>
					Phone Number : <input type="text" name="phone" placeholder="10 digit phone number" pattern="[0-9]{10}" 
											<?php if (isset($info['PHONE'])) echo 'value="'.$info['PHONE'].'"'; ?>
											style="margin-top:10px; height:25px; width:180px;"><br>
					Email : <input type="email" name="email" placeholder="Email" maxlength="128"
										<?php if (isset($info['EMAIL'])) echo 'value="'.$info['EMAIL'].'"'; ?>
										style="margin-top:10px; height:25px; width:180px;"><br>

					<div <?php echo 'style='.(isset($_SESSION["infoErr"]) ? "color:red;" : "color:black;").''; ?>>
		            <?php
		                if (isset($_SESSION["infoErr"])) {
		                    echo '' . $_SESSION["infoErr"] . '<br>';
		                    unset($_SESSION["infoErr"]);
		                } else if (isset($_SESSION["infoMsg"])) {
		                	echo '' . $_SESSION["infoMsg"] . '<br>';
		                    unset($_SESSION["infoMsg"]);
		                }
		            ?>
            		</div>

					<input type="submit" name="saveInfo" value="Update" style="margin-top:10px; height:25px; width:180px;">
					</fieldset>
	 			</form>

	 			<form name="info" method="post" style="text-align:right;">
	 				<fieldset>
	 				<legend>Change Password:</legend>
					<!-- Ask for old pwd and new pwd twice -->
					Old Password : <input type="password" name="opwd" maxlength="24"
											style="margin-top:10px; height:25px; width:180px;" required><br>
					New Password : <input type="password" name="npwd" maxlength="24"
											style="margin-top:10px; height:25px; width:180px;" required><br>
					Confirm New Password : <input type="password" name="cpwd" maxlength="24"
													style="margin-top:10px; height:25px; width:180px;" required><br>
					            
					<div <?php echo 'style='.(isset($_SESSION["pwdErr"]) ? "color:red;" : "color:black;").''; ?>>
		            <?php
		                if (isset($_SESSION["pwdErr"])) {
		                    echo '' . $_SESSION["pwdErr"] . '<br>';
		                    unset($_SESSION["pwdErr"]);
		                } else if (isset($_SESSION["pwdMsg"])) {
		                	echo '' . $_SESSION["pwdMsg"] . '<br>';
		                    unset($_SESSION["pwdMsg"]);
		                }
		            ?>
            		</div>

					<input type="submit" name="savePwd" value="Change" style="margin-top:10px; height:25px; width:180px;">
					</fieldset>
	 			</form>
	 			
	 			<form name="info" method="post" style="text-align:right;">
	 				<fieldset>
	 				<?php
	 					if ($usr != $_SESSION["usr"]) {
	 				?>
					<input type="submit" name="deleteAcc" value="Delete Account" style="margin-top:10px; height:25px; width:180px;">
					<?php
						}
					?>
					<input type="submit" name="cancel" value="Cancel" style="margin-top:10px; height:25px; width:180px;">
					</fieldset>
	 			</form>
	 			</div>
			<?php
			} else {
				echo "Account doesn't exist anymore";
			}
        }

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }
    
    // Creates form for searching (called when mode == search)
    function searchForm() {
    ?>
    	<h1>
	  		Search
	   </h1>
	   
	   <div>
    	<form name="search" method="get">
    		<!-- Hidden values -->
    		<input type="hidden" name="mode" value="search">
    		<input type="hidden" name="rid" id="rid">
    		
			<div style="float:left; margin-right:10px;">
			<!-- Dynamic list of keywords -->
			<div id="keywordsList">
			<?php
				for($n=0; $n < (isset($_GET["key"]) ? count($_GET["key"]) : 1); $n++) {
					// If there is a list of keywords, iterate through it, else just go through loop once
					if (!isset($_GET["key"]) or $_GET["key"][$n] or ($n == 0 and count(array_filter($_GET["key"])) == 0)) {
					?>
						<!-- If there was no list of keys, there is a valid key at position n, or n == 0 with no valid values in the list of keys
							then make a new text input. If there is a valid key at position n then set the value to the key -->
						<input type="text" name="key[]" <?php echo 'value=\''.(isset($_GET["key"][$n]) ? $_GET["key"][$n] : '').'\''; ?> 
								style="margin-bottom:1px; height:25px; width:180px;"><br>
					<?php
					}
				}
			?>
			</div>
			<!-- Button calls javascript function add_keyword() to add another text input into the dynamic list of keywords -->
			<input type="button" name="add" value="Add Keyword" onclick="addKeyword()" style="margin-top:10px; height:25px; width:180px;">
			</div>    		
    		
    		<!-- Start of date range for test date -->
			Start Date : <input type="date" name="sdate" placeholder="yyyy-mm-dd" pattern="[0-9]{4}+\-[0-9]{1,2}+\-[0-9]{1,2}" 
							<?php
					       		if (isset($_GET['sdate']) and DateTime::createFromFormat('Y-m-j', $_GET['sdate'])) {
					       			// If there's a valid start date then set value of input to the submitted date
					       			echo 'value=', $_GET['sdate'];
								}
							?> 
								style="margin-bottom:10px; height:25px; width:180px;">

			<!-- End of date range for test date -->
			End Date : <input type="date" name="edate" placeholder="yyyy-mm-dd" pattern="[0-9]{4}+\-[0-9]{1,2}+\-[0-9]{1,2}"
							<?php
								if (isset($_GET['edate']) and DateTime::createFromFormat('Y-m-j', $_GET['edate'])) {
									// If there's a valid end date then set value of input to the submitted date
									echo 'value=', $_GET['edate'];	
								}
							?> 
								style="margin-bottom:10px; height:25px; width:180px;">


			Sort by :
			<select id="sortBy" name="sortBy">
	   		<option value="0">Test Date</option>
    	    	<option value="1">Relevance</option>
    		</select>
    		
			Order by :
			<select id="orderBy" name="orderBy">
	      	<option value="0">Ascending</option>
    	    	<option value="1">Descending</option>
    		</select>
    		
			<input type="submit" name="search" value="Search" style="margin-left:10px; margin-bottom:10px; height:25px; width:180px;">
                        
		</form>
		</div>
    <?php
    	if (isset($_GET['search'])) {
    		// Obtain search parameters (concatenate keys list into single string and convert dates to date objects)
			$search = (isset($_GET["key"]) ? implode(" ", $_GET["key"]) : null);
			$sdate = stringToDate($_GET["sdate"]);
			$edate = stringToDate($_GET["edate"]);

			if (rtrim($search) or $sdate or $edate) { 
				// Valid search string or date objects
				echo 'Search results for:<br>', ($search ? 'Keyword(s): '.$search.'<br>' : ''), 
						($sdate ? 'Start Date: '.dateToString($sdate).' '.($edate ? '' : '<br>').'' : ''), 
						($edate ? 'End Date: '.dateToString($edate).'<br>': '');

				// Call search_keyword with search string and formatted date strings (null if not it was not a valid date to begin with)
				//search_keyword($search, ($sdate ? dateToString($sdate) : null), ($edate ? dateToString($edate) : null), $_GET["sortBy"], $_GET["orderBy"]);
			} else {
				// Error message for having no valid search parameters
			?>
				<div style="color:red;">
					Please input some search information
				</div>		
			<?php
			}
		}
    }

	// Creates form for managing users
    function manageForm($type) {
    ?>    	
    	<h1>
	  		User Manager
	  	</h1>
    
    	<form name="manage" method="get">
    		<!-- Hidden mode value -->
    		<input type="hidden" name="mode" value="manage">
    		<input type="hidden" name="account" id="account">
    		<input type="hidden" name="pid" id="pid">
    		
    		<!-- Search parameters -->
    		Username : <input type="text" name="usr" placeholder="Username" 
    		<?php if (isset($_GET['usr']) and $_GET['usr']) echo 'value='.$_GET['usr'].''; ?>
    		style="margin-bottom:10px; height:25px; width:180px;"> 
			First Name : <input type="text" name="fname" placeholder="First Name" 
			<?php if (isset($_GET['fname']) and $_GET['fname']) echo 'value='.$_GET['fname'].''; ?>
			style="margin-bottom:10px; height:25px; width:180px;"> 
			Last Name : <input type="text" name="lname" placeholder="Last Name" 
			<?php if (isset($_GET['lname']) and $_GET['lname']) echo 'value='.$_GET['lname'].''; ?>
			style="margin-bottom:10px; height:25px; width:180px;"> 
			
			<input type="submit" name="search" value="Search" style="margin-left:10px; margin-bottom:10px; height:25px; width:180px;"><br>
    	</form>
    <?php
    	if ($_GET["account"] and $_GET["pid"]) {
    		userForm($_GET['account'], $_GET['pid'], $type);
    	} else if (isset($_GET['search'])) {
			obtainUsers($_GET['usr'], $_GET['fname'], $_GET['lname']);
    	}
    }

		// Creates form for managing family doctors
    function familyDoctorForm($type) {
    ?>    	
    	<h1>
			Family Doctor Info
	  	</h1>
    
    	<form name="manage" method="get">
    		<!-- Hidden mode value -->
    		<input type="hidden" name="mode" value="doctor">
    		<input type="hidden" name="account" id="account">
    		<input type="hidden" name="pid" id="pid">
    		
    		<!-- Search parameters -->
			First Name : <input type="text" name="fname" placeholder="First Name" 
			<?php if (isset($_GET['fname']) and $_GET['fname']) echo 'value='.$_GET['fname'].''; ?>
			style="margin-bottom:10px; height:25px; width:180px;"> 
			Last Name : <input type="text" name="lname" placeholder="Last Name" 
			<?php if (isset($_GET['lname']) and $_GET['lname']) echo 'value='.$_GET['lname'].''; ?>
			style="margin-bottom:10px; height:25px; width:180px;"> 
			
			<input type="submit" name="search" value="Search" style="margin-left:10px; margin-bottom:10px; height:25px; width:180px;"><br>
    	</form>
    	
    <?php
    }

		// Creates form for uploading
    function uploadForm($type) {
    ?>    	
    	<h1>
			New Record
	  	</h1>   
    <?php
    }

   	// Creates form for generating a report
    function generateForm($type) {
    ?>
		<h1>
	  		Report Generator
	   </h1>    
    
    	<form name="search" method="get">
    		<!-- Hidden mode value -->
    		<input type="hidden" name="mode" value="generate">

    		<!-- Diagnosis list -->
	    	Diagnosis : <input type="text" list="diagnosisList" id="diagnosis" name="diagnosis" placeholder="Diagnosis" maxlength="128"
		<?php
	   		if (isset($_GET['diagnosis']) and $_GET['diagnosis']) {
	   			// If there's a valid start date then set value of input to the submitted date
	   			echo 'value=', $_GET['diagnosis'];
			}
		?> 
			onkeyup="updateDiagnosisList(event)" autocomplete="off" required>
	    	<datalist id="diagnosisList"></datalist>
	    	

			<!-- Start of date range for test date -->
			Start Date : <input type="date" name="sdate" placeholder="yyyy-mm-dd" pattern="[0-9]{4}+\-[0-9]{1,2}+\-[0-9]{1,2}" 
							<?php
						   		if (isset($_GET['sdate']) and DateTime::createFromFormat('Y-m-j', $_GET['sdate'])) {
						   			// If there's a valid start date then set value of input to the submitted date
						   			echo 'value=', $_GET['sdate'];
								}
							?> 
								style="margin-bottom:10px; height:25px; width:180px;" required>

			<!-- End of date range for test date -->
			End Date : <input type="date" name="edate" placeholder="yyyy-mm-dd" pattern="[0-9]{4}+\-[0-9]{1,2}+\-[0-9]{1,2}"
							<?php
								if (isset($_GET['edate']) and DateTime::createFromFormat('Y-m-j', $_GET['edate'])) {
									// If there's a valid end date then set value of input to the submitted date
									echo 'value=', $_GET['edate'];	
								}
							?> 
								style="margin-bottom:10px; height:25px; width:180px;" required>

			<input type="submit" name="generate" value="Generate" style="margin-left:10px; margin-bottom:10px; height:25px; width:180px;"><br>
			
		</form>
		
		
    <?php
		if (isset($_GET['generate'])) {
			$sdate = stringToDate($_GET["sdate"]);
			$edate = stringToDate($_GET["edate"]);
			
			report_generating($_GET['diagnosis'], ($sdate ? dateToString($sdate) : null), ($edate ? dateToString($edate) : null));
    	}
    }

   	// Creates form for data analysis
   	// Creates form for data analysis
   	
    function analysisForm() {
    ?>
    
     	<h1>
	  		Data Analysis
	   </h1>
	   
    	<form name="analysis" method="get">
    		<!-- Hidden mode value -->
    		<input type="hidden" name="mode" value="analysis">
    		
    		<!-- Search parameters --> 
			Include Patient Name ?  <input type="text" name="fname" placeholder="" 
			<?php if (isset($_GET['fname']) and $_GET['fname']) echo 'value='.$_GET['fname'].''; ?>
			style="margin-bottom:10px; height:25px; width:180px;"> <br>

			Include Test Type ?     <input type="text" name="test_type" placeholder="" 
			<?php if (isset($_GET['test_type']) and $_GET['test_type']) echo 'value='.$_GET['test_type'].''; ?>
			style="margin-bottom:10px; height:25px; width:180px;"> <br>
			
    		<!-- Start of date range for test date -->
    		

								
						<label id="timeperiodlabel" for="timeperiod:">Time Period: </label><select name="timeperiod" id="timeperiod">
	        					<option value="a">All</option>
    	    						<option value="w">Weekly</option>
    	    						<option value="m">Monthly</option>
    	    						<option value="y">Yearly</option>
    	    						<option value="n">None</option>
    						</select></br></br>
			
			
			<input type="submit" name="analysis" value="Analysis" style="margin-left:10px; margin-bottom:10px; height:25px; width:180px;"><br>
    	</form>
    <?php
    	if (isset($_GET['analysis'])) {
    		$sdate = stringToDate($_GET["sdate"]);
			$edate = stringToDate($_GET["edate"]);
			//echo 'value!!!!!!!!!!!!!!!=', $_GET['timeperiod'];	
			data_analysis($_GET['fname'], $_GET['lname'], $_GET['test_type'],($sdate ? dateToString($sdate) : null), ($edate ? dateToString($edate) : null),$_GET['timeperiod']);
			//echo 'information : ',$_GET['fname'];
			//echo 'information : ',$_GET['sdate'];
    	}
    	}
    // Creates form for logging out
    function logoutForm() {
    ?>

    	<form name="logout" action="logout.php" style="margin-bottom:0px; border-bottom-width:0px;">
    		<input type="submit" name="logout" value="Logout" style="height:25px; width:180px;">	
		</form>
    <?php
    }
	
	 function obtainUsers($usr, $fname, $lname) {
	 	// Establish connection
    	$conn = connect();
		if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'SELECT u.user_name, u.class, u.person_id, p.first_name, p.last_name
        				FROM users u, persons p 
        				WHERE u.person_id = p.person_id';

			if ($usr) $sql = ''.$sql.' AND LOWER(u.user_name) LIKE \'%'.strtolower($usr).'%\'';
			if ($fname) $sql = ''.$sql.' AND LOWER(p.first_name) LIKE \'%'.strtolower($fname).'%\'';
			if ($lname) $sql = ''.$sql.' AND LOWER(p.last_name) LIKE \'%'.strtolower($lname).'%\'';
		
			$sql = ''.$sql.' ORDER BY u.user_name'; 

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);
        
        if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        } else {
        	// No error
        	// Fetch and output info
        	global $types; 
        	if ($info = oci_fetch_array($stid)) {
        	?>
        		<div style="display:inline-block; height:600px; overflow:auto;">
	        		<table border="1">
	        			<th width="100" align="center" valign="middle">Username</th>
	        			<th width="100" align="center" valign="middle">Type</th>
	        			<th width="100" align="center" valign="middle">First Name</th>
	        			<th width="100" align="center" valign="middle">Last Name</th>
	        	<?php
	        		while ($info) {
	        		?>
	        			<tr <?php echo 'onclick="selectUser(\''.$info["USER_NAME"].'\', \''.$info["PERSON_ID"].'\')"'; ?>
	        				onMouseover="this.bgColor='#ADD8E6'" onMouseout="this.bgColor='#FFFFFF'">
							<td><?php echo $info["USER_NAME"]; ?></td>	
							<td align="center" valign="middle"><?php echo $types[$info["CLASS"]]; ?></td>
							<td><?php echo $info["FIRST_NAME"]; ?></td>
							<td><?php echo $info["LAST_NAME"]; ?></td>	
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
        	
        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
	 }

    // Returns an array of all the types of diagnosis
    function diagnosisArray() {
    	// Establish connection
    	$conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'SELECT DISTINCT UPPER(diagnosis) FROM radiology_record
        		ORDER BY UPPER(diagnosis)';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        $diagnosisArray = array();
        if ($res) {
	        while ($row = oci_fetch_array($stid)) {
	        	array_push($diagnosisArray, $row["UPPER(DIAGNOSIS)"]);
	        }
		}	

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);

        return $diagnosisArray;
    }
?>

<script>
	// Set up a global variable for diagnosisArray
	var diagnosisArray = new array(); 

	// Adds another text input into the dynamic list of keywords
	function addKeyword() {
		var newdiv = document.createElement('div');
		newdiv.innerHTML = '<input type="text" name="key[]" style="margin-bottom:1px; height:25px; width:180px;"><br>';
		document.getElementById('keywordsList').appendChild(newdiv);
	}			

	function selectUser(user_name, person_id) {
		document.getElementById('account').value = user_name;
		document.getElementById('pid').value = person_id;
		document.forms['manage'].submit()
	}

	function updateDiagnosisList(event) {
		// Grab keycode
		var x = event.which || event.keyCode;

		if ((x < 37 || x > 40)) {
			// Don't do anything on arrow key events
			// Retrieve diagnosisList element
			var list = document.getElementById('diagnosisList');

			// Empty the list
			list.innerHTML = '';

			// Retrieve list of diagnosis
			if (!diagnosisArray) {
				// Assign diagnosisArray for the first time
				diagnosisArray = 
					<?php 	
						echo json_encode(diagnosisArray()); 
					?>;
			}

			// Filter diagnosisArray into newArray
			var newArray = diagnosisArray.filter(isLike);

			// Sort newArray
			newArray.sort(isFirst);

			// Show only up to the first 10 results
			for(var i=0;i<newArray.length && i < 10;i++){
				list.innerHTML += '<option>'+newArray[i]+'<option>';
	    	}
	    }
	}

	function isLike(element) {
		// Obtain current diagnosis value
		var diagnosis = document.getElementById('diagnosis');

		if (diagnosis.value) {
			// There is a value to filter by
			// Show elements that contain the value
	  		return element.toLowerCase().indexOf(diagnosis.value.toLowerCase()) > -1;
	  	} else {
	  		// There isn't a value to filter by, don't show any elements
	  		return false;
	  	}
	}

	function isFirst(a, b) {
		// Obtain current diagnosis value
		var diagnosis = document.getElementById('diagnosis');

		if (diagnosis.value) {
			// There is a value to sort by
	  		return a.toLowerCase().indexOf(diagnosis.value.toLowerCase()) - b.toLowerCase().indexOf(diagnosis.value.toLowerCase());
	  	} else {
	  		// There isn't a value to sort by, don't change order
	  		return -1;
	  	}
	}
</script>
