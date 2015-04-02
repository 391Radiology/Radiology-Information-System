<?php
	include_once("forms.php");
	include_once("switcher.php");	
	
	$modes = array(
			'account' => 'Account Info',
			'search' => 'Search',
			'manage' => 'Manage Users',
			'generate' => 'Generate Report',
			'analysis' => 'Data analysis');
?>

<html>
	<body>
		<?php
				if (isset($_SESSION["usr"])) {
					// Logged in
					// Establish modes array
					global $modes;
								
					if (isset($_GET["mode"]) and array_key_exists($_GET["mode"], $modes)) {
						// Valid mode
						// Create switch form
						switchForm($modes);
						
						// Create forms based on mode
						if ($_GET["mode"] == "account") userForm($_SESSION["usr"], $_SESSION["pid"]);
						else if ($_GET["mode"] == "search") searchForm();
						else if ($_GET["mode"] == "manage") manageForm();
						else if ($_GET["mode"] == "generate") generateForm();
						else if ($_GET["mode"] == "analysis") analysisForm();

						// Create logout form
						logoutForm();
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