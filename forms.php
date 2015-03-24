<?php
    function user_info($pid) {
        //establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        
        //sql command
        $sql = 'SELECT * FROM persons WHERE person_id = '.$_SESSION["pid"].'';

        //Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        //Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        //if error, retrieve the error using the oci_error() function & output an error message
        if (!$res) {
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        } else {
	        	$person = oci_fetch_array($stid);
   	     	echo 'Welcome  '. $person[1] . ' ' . $person[2] . ' <br/>';
				echo ''.$types[$_SESSION["type"]].' <br/>';
				echo 'Address: '.$person[3] .' Phone: '.$person[5] .' Email: '.$person[4] .' <br/>';
        }

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
 
    }
    
    function search_form() {
    ?>
    	<form name="search" method="get" action="account.php">
			Start Date : <input type="date" placeholder="yyyy-mm-dd" maxlength=10 
			<?php
       		if (isset($_POST['sdate']) and DateTime::createFromFormat('Y-m-j', $_POST['sdate'])) {
					echo 'value=', $_POST['sdate'];
				}
			?> name="sdate"/>
			End Date : <input type="date" placeholder="yyyy-mm-dd" maxlength=10 max="9999-12-31"
			<?php
				if (isset($_POST['edate']) and DateTime::createFromFormat('Y-m-j', $_POST['edate'])) {
					echo 'value=', $_POST['edate'];
				}
			?> name="edate"/>
			<input type="submit" name="search" value="Search"/> <br>
			<div id="keyword_list">
				<?php
					$ni = "hi";
					for($n=0; $n < max(1, count($_GET["key"])); $n++) {
						?>
							<input type="text" name="key[]" <?php echo 'value=\''.$_GET["key"][$n].'\''; ?>/><br>
						<?php
					}
				?>
			</div>
			<input type="button" name="add" onclick="add_keyword()" value="Add Keyword"/>
                        
		</form>
    <?php
    }
?>

<script>
	function add_keyword() {
		var newdiv = document.createElement('div');
		newdiv.innerHTML = '<input type="text" name="key[]" value=""/>';
		document.getElementById('keyword_list').appendChild(newdiv);
	}			
</script>