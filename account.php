<?php
	include('PHPconnectionDB.php');
	include('medical_info.php');
	include("key_search.php");
	include("forms.php");
	session_start();
?>

<html>
	<body>
		<?php
				if (isset($_SESSION["usr"])) {
					//establish types array
					$types = array('a' => 'Admin',
								'd' => 'Doctor',
								'r' => 'Radiologist',
								'p' => 'Patient');

					user_info($_SESSION['pid']);
					search_form();

					if (isset($_GET['search'])) {
						$search = implode(" ", $_GET["key"]);
						echo 'Search results for: '.$search.' <br>';
						search_keyword("hi", null, null);
					}
				}
				else {
					echo "Session not found, please login";
					header("refresh:3; url=login.php");
				}
		?>
	</body>
</html>
