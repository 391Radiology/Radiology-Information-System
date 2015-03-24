<?php
    function user_info($pid) {
			//establish types array
			$types = array('a' => 'Admin',
								'd' => 'Doctor',
								'r' => 'Radiologist',
								'p' => 'Patient');
								
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
       		if (isset($_GET['sdate']) and DateTime::createFromFormat('Y-m-j', $_GET['sdate'])) {
					echo 'value=', $_GET['sdate'];
				}
			?> name="sdate"/>
			End Date : <input type="date" placeholder="yyyy-mm-dd" maxlength=10 max="9999-12-31"
			<?php
				if (isset($_GET['edate']) and DateTime::createFromFormat('Y-m-j', $_GET['edate'])) {
					echo 'value=', $_GET['edate'];
				}
			?> name="edate"/>
			<input type="submit" name="search" value="Search"/> <br>
			<div id="keyword_list">
				<?php
					for($n=0; $n < max(1, count($_GET["key"])); $n++) {
						if(!isset($_GET["key"]) or $_GET["key"][$n] or ($n == 0 and count(array_filter($_GET["key"])) == 0)) {
							?>
								<input type="text" name="key[]" <?php echo 'value=\''.$_GET["key"][$n].'\''; ?>/><br>
							<?php
						}
					}
				?>
			</div>
			<input type="button" name="add" onclick="add_keyword()" value="Add Keyword"/>
                        
		</form>
    <?php
    	if (isset($_GET['search'])) {
			$search = implode(" ", $_GET["key"]);
			if ($search) { 
				echo 'Search results for: '.$search.' <br>';
				search_keyword($search, $_GET["sdate"],$_GET["edate"]);
			} else {
				?>
					<div style="color:red;">
						Please input some search information <br>
					</div>	
				<?php
			}
		}
    }
?>

<script>
	function add_keyword() {
		var newdiv = document.createElement('div');
		newdiv.innerHTML = '<input type="text" name="key[]" value=""/>';
		document.getElementById('keyword_list').appendChild(newdiv);
	}			
</script>