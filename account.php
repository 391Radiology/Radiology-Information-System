<?php
	include('PHPconnectionDB.php');
	include("key_search.php");
	include("forms.php");
	session_start();
?>

<html>
	<body>
		<?php
				if (isset($_SESSION["usr"])) {
					user_info($_SESSION['pid']);
					search_form();
				}
				else {
					echo "Session not found, please login";
					header("refresh:3; url=login.php");
				}
		?>
	</body>
</html>
