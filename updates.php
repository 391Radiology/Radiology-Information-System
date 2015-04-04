<?php
	if (session_status() == PHP_SESSION_NONE) session_start();

	// Checks username unique constraint
	function checkUsedUsername() {
		// Establish connection
	        $conn = connect();
	        if (!$conn) {
	            $e = oci_error();
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }

	        // Sql command
	        $sql = 'SELECT * FROM users u WHERE u.user_name = \''.$_POST["usr"].'\'';

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
	        	// See if there was any results
	        	$row = oci_fetch_array($stid);
	        }

	        // Free the statement identifier when closing the connection
	        oci_free_statement($stid);
	        oci_close($conn);
	        return ((isset($row) and $row) ? true : false);
	}

	// Checks email unique constraint
	function checkUsedEmail($pid) {
		// Establish connection
	        $conn = connect();
	        if (!$conn) {
	            $e = oci_error();
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }

	        // Sql command
	        $sql = 'SELECT * FROM persons p, users u 
	        		WHERE p.person_id = u.person_id AND p.email = \''.$_POST["email"].'\'';
	        if ($pid) $sql = ''.$sql.' AND p.person_id != '.$pid.'';

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
	        	// See if there was any results
	        	$row = oci_fetch_array($stid);
	        	echo $row[0];
	        }

	        // Free the statement identifier when closing the connection
	        oci_free_statement($stid);
	        oci_close($conn);
	        return ((isset($row) and $row) ? true : false);
	}

	// Inserts a person entry
	function createPerson() {

	    	// Establish connection
	        $conn = connect();
	        if (!$conn) {
	            $e = oci_error();
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }

	        // Sql command
	        $sql = 'SELECT person_id.NEXTVAL FROM DUAL';

	        // Prepare sql using conn and returns the statement identifier
	        $stid = oci_parse($conn, $sql);

	        // Execute a statement returned from oci_parse()
	        $res = oci_execute($stid, OCI_DEFAULT);

	        if (!$res) {
	        	// Error, retrieve the error using the oci_error() function & output an error message
	     	   	$err = oci_error($stid);
	     	   	echo htmlentities($err['message']);
	        } else {
	        	// No error
	        	if ($id = oci_fetch_array($stid)) {
	        		// Obtained unique person id
	        		// Sql command
	        		$sql = 'INSERT INTO persons VALUES ('.$id[0].', \''.$_POST["fname"].'\', \''.$_POST["lname"].'\', 
	        				\''.$_POST["address"].'\', \''.$_POST["email"].'\', \''.$_POST["phone"].'\')';

					// Prepare sql using conn and returns the statement identifier
			        $stid = oci_parse($conn, $sql);

			        // Execute a statement returned from oci_parse()
	        		$res = oci_execute($stid, OCI_DEFAULT);

	        		if (!$res) {
	        			// Error, retrieve the error using the oci_error() function & output an error message
	     	   			$err = oci_error($stid);
	     	   			echo htmlentities($err['message']);
	     	   			oci_rollback($conn);
	        		} else {
	        			oci_commit($conn);
	        		}
	        	}
        	}

	        // Free the statement identifier when closing the connection
	        oci_free_statement($stid);
	        oci_close($conn);
	        return $id[0];
	}

	function createUser($pid) {
	    	// Establish connection
	        $conn = connect();
	        if (!$conn) {
	            $e = oci_error();
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }
			
			$sql = 'INSERT INTO users VALUES (\''.$_POST["usr"].'\', \''.$_POST["cpwd"].'\', \''.$_POST["class"].'\', 
	        		'.$pid.', null)';

			// Prepare sql using conn and returns the statement identifier
			$stid = oci_parse($conn, $sql);
			
	        // Execute a statement returned from oci_parse()
    		$res = oci_execute($stid);

    		if (!$res) {
    			// Error, retrieve the error using the oci_error() function & output an error message
 	   			$err = oci_error($stid);
 	   			echo htmlentities($err['message']);
    		}
			
	        // Free the statement identifier when closing the connection
	        oci_free_statement($stid);
	        oci_close($conn);

	        return $res;
	}

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
        		if ($account = oci_fetch_array($stid)) {
	        		// Fetch account matching usr (should be unique)
		        	if (!isset($_POST["opwd"]) or $_POST["opwd"] == $account["PASSWORD"]) {
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
		        } else {
		        	// Acount doesn't exist anymore
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
    
    function deleteAcc($usr) {
    		// Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'DELETE FROM users WHERE LOWER(user_name) = \''.strtolower($usr).'\'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        }
        
        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }


   	function addPatient($doctor, $patient) {
    		// Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'INSERT INTO family_doctor VALUES ('.$doctor.', '.$patient.')';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        }
        
        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }

   	function deletePatient($doctor, $patient) {
    		// Establish connection
        $conn = connect();
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Sql command
        $sql = 'DELETE FROM family_doctor 
        		WHERE doctor_id = '.$doctor.' AND patient_id = '.$patient.'';

        // Prepare sql using conn and returns the statement identifier
        $stid = oci_parse($conn, $sql);

        // Execute a statement returned from oci_parse()
        $res = oci_execute($stid);

        if (!$res) {
        	// Error, retrieve the error using the oci_error() function & output an error message
     	   	$err = oci_error($stid);
     	   	echo htmlentities($err['message']);
        }
        
        // Free the statement identifier when closing the connection
        oci_free_statement($stid);
        oci_close($conn);
    }

	// Uploads a pic to a specific record
	function uploadPic($rid, $tmpFile, $fileType) {
		if ($tmpFile) {
			// File given
			if ($fileType == "jpg") {
				// Supported file format
				// Establish connection
			  	$conn = connect();
			  	if (!$conn) {
			      	$e = oci_error();
		            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		        }

		        // Sql command	
		        $sql = 'SELECT MAX(image_id) FROM pacs_images WHERE record_id = '.$rid.'';

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
		        	if ($record = oci_fetch_array($stid)) {
		        		// Obtain image id
						$iid = intval($record["MAX(IMAGE_ID)"]) + 1;
						
						// Sql command	
		        		$sql = 'INSERT INTO pacs_images 
		        				VALUES ('.$rid.', '.$iid.', EMPTY_BLOB(), EMPTY_BLOB(), EMPTY_BLOB())';

		        		// Prepare sql using conn and returns the statement identifier
		        		$stid = oci_parse($conn, $sql);

						// Execute a statement returned from oci_parse()
						$res = oci_execute($stid, OCI_DEFAULT);

						if (!$res) {
				        	// Error, retrieve the error using the oci_error() function & output an error message
				     	   	$err = oci_error($stid);
				     	   	echo htmlentities($err['message']);
				        } else {
				        	// No error
				        	// New image entry was added to record
							// Sql command	
						    $sql = 'SELECT thumbnail, regular_size, full_size
									FROM pacs_images 
	   								WHERE record_id = '.$rid.' AND image_id = '.$iid.'
	    							FOR UPDATE';

	    					// Prepare sql using conn and returns the statement identifier
		        			$stid = oci_parse($conn, $sql);

		        			// Execute a statement returned from oci_parse()
							oci_execute($stid, OCI_DEFAULT);

							if ($row = oci_fetch_array($stid)) {
								// Obtained entry
								if (!$row["THUMBNAIL"]->save(file_get_contents($tmpFile)) or 
									!$row["REGULAR_SIZE"]->save(file_get_contents($tmpFile)) or 
									!$row["FULL_SIZE"]->save(file_get_contents($tmpFile))) {
									// Attempt to save to blobs
								    // On error, rollback the transaction
								    oci_rollback($conn);
								    
								} else {
									// No errors saving blobs
								    // On success, commit the transaction
								    oci_commit($conn);
								    
								}
							} else {
								oci_rollback($conn);
							}
						}
					}
		        }
		        
		        // Free the statement identifier when closing the connection
		        oci_free_statement($stid);
		        oci_close($conn);
		    } else {
		    	// Not supported file format
		    }
		} else {
			// Not an image file
		}
	}
?>