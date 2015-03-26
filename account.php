<?php
	session_start();
	include_once("forms.php");
?>

<html>
	<body>
		<?php
				if (isset($_SESSION["usr"])) {
					// Logged in
					// Establish modes array
					$modes = array('account', 'search');

					if (isset($_GET["mode"]) and in_array($_GET["mode"], $modes)) {
						// Valid mode
						// Create switch form
						switchForm();
						
						// Create forms based on mode
						if ($_GET["mode"] == "account") userForm($_SESSION["usr"]);
						else if ($_GET["mode"] == "search") searchForm();

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