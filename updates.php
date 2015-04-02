<?php
	session_start();

	// Updates an account
   function updateInfo($usr, $pid) {
   	// Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'UPDATE users 
        		SET class = \''.$_POST["class"].'\' 
        		WHERE user_name = \''.$usr.'\'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res1 = oci_execute($stid);

		if (!$res1) {
	        	// Error, retrieve the error using the oci_error() function & output an error message
	     	   	$err = oci_error($stid);
	     	   	echo htmlentities($err['message']);
	        }

		// Sql command
        $sql = 'UPDATE persons
        		SET first_name = \''.$_POST["fname"].'\', last_name =  \''.$_POST["lname"].'\',
        			address = \''.$_POST["address"].'\', phone =  \''.$_POST["phone"].'\',
        			email = \''.$_POST["email"].'\'
        		WHERE person_id = \''.$pid.'\'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res2 = oci_execute($stid);

		if (!$res2) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        }

        if ($res1 and $res2) $_SESSION["infoMsg"] = "Update successful";
        else $_SESSION["infoErr"] = "An error occured";

        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }

    function updatePwd($usr) {
    	if ($_POST["npwd"] == $_POST["cpwd"]) {
    		// New password confirmed
	    	// Establish connection
	        $conn = connect();
	        if (!$conn) {
	            $e = oci_error();
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }

	        // Sql command
	        $sql = 'SELECT * FROM users WHERE LOWER(user_name) = \''.strtolower($usr).'\'';

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
        		// Fetch account matching usr (should be unique)
        		$account = oci_fetch_array($stid);
	        	
	        	if ($account and $_POST["opwd"] == $account["PASSWORD"]) {
	        		// Account still exists and old password matches account password
	        		// Sql command
	       	 		$sql = 'UPDATE users 
        					SET password = \''.$_POST["cpwd"].'\' 
        					WHERE user_name = \''.$usr.'\'';

			        // Prepare sql using conn and returns the statement identifier
			        $stid = oci_parse($conn, $sql);

			        // Execute a statement returned from oci_parse()
			        $res = oci_execute($stid);

			        if (!$res) {
	        			// Error, retrieve the error using the oci_error() function & output an error message
	     	   			$err = oci_error($stid);
	     	   			echo htmlentities($err['message']);
	        		} else {
	        			$_SESSION["pwdMsg"] = "Password changed";
	        		}
	        	} else {
	        		// Old password doesn't match account password
	        		$_SESSION["pwdErr"] = "Incorrect password";
	        	}
	        }
	        
	        // Free the statement identifier when closing the connection
	        oci_free_statement($stid);
	        oci_close($conn);
	    } else {
	    	// New password not confirmed
	    	$_SESSION["pwdErr"] = "Passwords do not match";
	    }
    }
?>