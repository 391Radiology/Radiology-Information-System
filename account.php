<?php
	include_once("forms.php");
	include_once("switcher.php");	
	
	$modes = array(
			'account' => 'Account Info',
			'search' => 'Search',
			'manage' => 'Manage Users',
			'generate' => 'Generate Report',
			'analysis' => 'Data analysis');
			
	// Obtains account type
	function obtainType($usr) {
		// Establish connection
    	$conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'SELECT u.class FROM users u WHERE u.user_name = \''.$usr.'\'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

			// Default patient
        $type = 'p';
        if ($res) {
	        if ($info = oci_fetch_array($stid)) {
	        		$type = $info["CLASS"];
				}
			}

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);

        return $type;
	}
?>

<html>
	<body>
		<?php
				if (isset($_SESSION["usr"])) {
					// Logged in
					// Establish modes array and get account type
					global $modes;
					$type = obtainType($_SESSION["usr"]);

					if (isset($_GET["mode"]) and array_key_exists($_GET["mode"], $modes)) {
						// Valid mode
					?>
						<div style="background-color:rgba(173, 216, 230, 0.75); text-align:right;">
						<?php
							// Create logout form
							logoutForm();
						?>
						</div>
						
						<div style="background-color:rgba(173, 216, 230, 0.75); float:left; height:100%;">
					<?php	
						// Create switch form
						switchForm($modes, $type);
					?>
						</div>
						
						<div style="float:left; margin-left:20px;">
					<?php
						// Create forms based on mode
						if ($_GET["mode"] == "account") userForm($_SESSION["usr"], $_SESSION["pid"]);
						else if ($_GET["mode"] == "search") searchForm();
						else if ($_GET["mode"] == "manage") manageForm();
						else if ($_GET["mode"] == "generate") generateForm();
						else if ($_GET["mode"] == "analysis") analysisForm();
					?>
						<div>
					<?php
					} else {
						// Nonvalid mode
						echo "Nonvalid mode specified, redirecting...";
						header("refresh:3; url=account.php?mode=account");
					}
				} else {
					// Not logged in
					echo "Session not found, please login";
					header("refresh:3; url=login.php");
				}
		?>
	</body>
</html>