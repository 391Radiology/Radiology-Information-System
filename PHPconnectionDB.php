<?php
function connect(){
	$conn = oci_connect('rlieu', 'CMPUT391');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	return $conn;
}
?>
