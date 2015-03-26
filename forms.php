<?php
	include_once("PHPconnectionDB.php");
	include_once("key_search.php");

	// Creates form for switching the mode 
	function switchForm() {
	?>
		<form name="switch">
			<input type="hidden" name="mode" id="mode"/>
    		<input type="submit" value="Account Info" onclick="switchMode('account')"/>	
    		<input type="submit" value="Search" onclick="switchMode('search')"/>	
		</form>
	<?php
	}

	// Creates form for the account info of specified user which can be editted (called when mode == user_info)
    function userForm($pid) {
		// Establish types array
		$types = array('a' => 'Admin',
				'd' => 'Doctor',
				'r' => 'Radiologist',
				'p' => 'Patient');
								
        // Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        // Sql command
        $sql = 'SELECT * FROM persons WHERE person_id = '.$_SESSION["pid"].'';

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
        	$person = oci_fetch_array($stid);
   	     	echo 'Welcome  '. $person[1] . ' ' . $person[2] . ' <br/>';
			echo ''.$types[$_SESSION["type"]].' <br/>';
			echo 'Address: '.$person[3] .' Phone: '.$person[5] .' Email: '.$person[4] .' <br/>';
        }

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);

  	?>
  		<form>
  			<input type="hidden" value="account" name="mode"/>
  		</form>
  	<?php
 
    }
    
    // Creates form for searching (called when mode == search)
    function searchForm() {
    ?>
    	<form name="search" method="get">
    		<!-- Hidden mode value -->
    		<input type="hidden" name="mode" value="search"/>

    		<!-- Start of date range for test date -->
			Start Date : <input type="date" name="sdate" placeholder="yyyy-mm-dd" pattern="[0-9]{4}+\-[0-9]{1,2}+\-[0-9]{1,2}" 
		<?php
       		if (isset($_GET['sdate']) and DateTime::createFromFormat('Y-m-j', $_GET['sdate'])) {
       			// If there's a valid start date then set value of input to the submitted date
       			echo 'value=', $_GET['sdate'];
			}
		?> 
			style="margin-bottom:10px; height:25px; width:180px;"/>

			<!-- End of date range for test date -->
			End Date : <input type="date" name="edate" placeholder="yyyy-mm-dd" pattern="[0-9]{4}+\-[0-9]{1,2}+\-[0-9]{1,2}"
		<?php
			if (isset($_GET['edate']) and DateTime::createFromFormat('Y-m-j', $_GET['edate'])) {
				// If there's a valid end date then set value of input to the submitted date
				echo 'value=', $_GET['edate'];	
			}
		?> 
			style="margin-bottom:10px; height:25px; width:180px;"/>

			<input type="submit" name="search" value="Search" style="margin-left:10px; margin-bottom:10px; height:25px; width:180px;"/> <br>

			<!-- Dynamic list of keywords -->
			<div id="keywordsList">
			<?php
				for($n=0; $n < (isset($_GET["key"]) ? count($_GET["key"]) : 1); $n++) {
					// If there is a list of keywords, iterate through it, else just go through loop once
					if(!isset($_GET["key"]) or $_GET["key"][$n] or ($n == 0 and count(array_filter($_GET["key"])) == 0)) {
					?>
						<!-- If there was no list of keys, there is a valid key at position n, or n == 0 with no valid values in the list of keys
							then make a new text input. If there is a valid key at position n then set the value to the key -->
						<input type="text" name="key[]" <?php echo 'value=\''.(isset($_GET["key"][$n]) ? $_GET["key"][$n] : '').'\''; ?> 
								style="margin-bottom:1px; height:25px; width:180px;"/><br>
					<?php
					}
				}
			?>
			</div>

			<!-- Button calls javascript function add_keyword() to add another text input into the dynamic list of keywords -->
			<input type="button" name="add" value="Add Keyword" onclick="addKeyword()" style="margin-top:10px; height:25px; width:180px;"/>
                        
		</form>
    <?php
    	if (isset($_GET['search'])) {
    		// Obtain search parameters (concatenate keys list into single string and convert dates to date objects)
			$search = implode(" ", $_GET["key"]);
			$sdate = stringToDate($_GET["sdate"]);
			$edate = stringToDate($_GET["edate"]);

			if ($search or $sdate or $edate) { 
				// Valid search string or date objects
				echo 'Search results for: <br>', ($search ? 'Keyword(s): '.$search.' <br>' : ''), 
						($sdate ? 'Start Date: '.dateToString($sdate).' '.($edate ? '' : '<br>').'' : ''), 
						($edate ? 'End Date: '.dateToString($edate).' <br>': '');

				// Call search_keyword with search string and formatted date strings (null if not it was not a valid date to begin with)
				search_keyword($search, ($sdate ? dateToString($sdate) : null), ($edate ? dateToString($edate) : null));
			} else {
				// Error message for having no valid search parameters
			?>
				<div style="color:red;">
					Please input some search information <br>
				</div>		
			<?php
			}
		}
    }

    // Creates form for logging out
    function logoutForm() {
    ?>
    	<form name="logout" action="logout.php">
    		<input type="submit" name="logout" value="Logout"/>	
		</form>
    <?php
    }

    // Convert a formatted string to date object
    function stringToDate($date) {
    	return DateTime::createFromFormat('Y-m-j', $date);
    }

    // Convert date object to formatted string
    function dateToString($date) {
    	return date_format($date,"j-M-Y");
    }
?>

<script>
	// Adds another text input into the dynamic list of keywords
	function addKeyword() {
		var newdiv = document.createElement('div');
		newdiv.innerHTML = '<input type="text" name="key[]" style="margin-bottom:1px; height:25px; width:180px;"/><br>';
		document.getElementById('keywordsList').appendChild(newdiv);
	}			

	// Switches mode value
	function switchMode(mode) {
		document.getElementById('mode').value = mode;
	}
</script>