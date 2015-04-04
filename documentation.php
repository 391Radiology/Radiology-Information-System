<html>
	<body>

	<H1>How to use</H1>
	<p>
		Exract compressed file RIS to compsci/webdocs/ccid/web_docs/desired_file_path <br>
		To access files launch consort.cs.ualberta.ca/~ccid/web_docs/file_path/login.php <br>
		You will be greeted with a login screen and the rest will be explained further below <br>
	</p>

		<H1>Login Module</H1>
		
		<p>This is the main module of the application.  This module allows a user to log in as a patient, doctor/radiologist or system administrator to RIS.  It allows proper user access to the system itself and lets the other modules know the user permissions when performing actions.
Upon arriving at the site, the user will be presented with a simple layout of a form with 2 fields; a username field and a password field.  There will also be a “LOGIN” button to confirm the inputs and check the credentials entered in the form.
The SQL Query used to obtain this information is the following:</p>
<div>
SELECT Username, person_id, class,<br>
FROM users <br>
WHERE LOWER(user_name) = username_input and Password = “password_input”; <br>
Where “username_input” and “password_input”;are the inputs provided by the user. <br>
</div>


		<H1>User Management Module</H1>
		
		<p>This module is what the system administrator for RIS uses to add/update or delete users from the system, effectively granting or modifying access to any user currently using the system.  This will modify information stored in the DB tables named USERS, PERSONS, FAMILY_DOCTOR.
The SQL Query used to insert users into the system database is: </p>
<div>
INSERT INTO persons VALUES (id, first_name, last_name, address, email, phone#); <br>
INSERT INTO users VALUES (username, password, class, id, date_registered.) <br>
Where “id” represents the person’s identification number, which is unique to the person and “class” is the class of user. (i.e. doctor, admin, radiologist or patient)<br>
The UPDATE query for the users is very similar, but instead of insert, we used the UPDATE method.<br>
UPDATE persons SET (id, first_name, last_name, address, email, phone#);  <br>
UPDATE users SET (username, password, class, id, date_registered.) <br>
</div>
<p>
Where 1 or more of the values between the brackets can be changed at any given time during an operation, with the exception of ID, which is the identifying characteristic of the entry and must remain constant and unique.
Report Generating Module
This module is used for obtaining data useful for statistical analisys.  It provides an administrator access to all records of patients that have been diagnosed with a specific disease in a specific period of time.  It will only return the name of each patient matching the description once, and sort them based on the test date.
The SQL Query for this module is:</p>

<div>
SELECT p.first_name, p.last_name, p.address, p.phone, MIN(r.test_date) <br>
FROM persons p, radiology_record r <br>
WHERE p.person_id = r.patient_id AND LOWER(r.diagnosis) = “diagnosis” <br>
AND r.test_date >= “test_date_start” AND r.test_date <= “test_date_end” <br>
GROUP BY(p.first_name, p.last_name, p.address, p.phone) <br>
ORDER BY MIN(r.test_date). <br>
</div>
<p>
“diagnosis”, “test_date_start” and “test_date_end” are user inputs defined by the form provided on the site.  After entering these values and clicking search, the user will be provided with the execution result of the query <br>
</p>
	
	
	<H1>Report Generating Module</H1>
	
	<p>This module is used by the admin to obtain a list of all patients with a specific diagnosis for a given time.</p>
	<div>
	The SQL Query used to insert users into the system database is: <br>
	SELECT p.first_name, p.last_name, p.address, p.phone, MIN(r.test_date) <br>
	FROM persons p, radiology_record r <br>
	WHERE p.person_id = r.patient_id AND LOWER(r.diagnosis) = \''.strtolower($diagnosis).'\' <br>
			AND r.test_date >= \''.$sdate.'\' AND r.test_date <= \''.$edate.'\' <br>
	GROUP BY p.first_name, p.last_name, p.address, p.phone <br>
	ORDER BY MIN(r.test_date)'</div>
	
	<H1>Uploading Module</H1>

	<p>This module is used to upload radiology test information from the radiologist’s clink to the database.  It allows us to add images, tests and results of each patient passing through a clinic. 
To add a picture, we do:</p>

<div>INSERT INTO pacs_images <br> 
VALUES(“record_id”,”image_id”, EMPTY_BLOB(), EMPTY_BLOB(), EMPTY_BLOB())'</div>

<p>This creates the image id and creates a DB entry for the spot, so when we upload an actual image, we can simply replace the empty blob with it. As usual, values between “ ” are either user imputs or generated values that allow the database to do what it needs to to create the entry.
</p>

	<H1>Search Module</H1>

	<p>This module allows any user, whether doctor or patient, to search the database for a specific keyword, returning all the records, ranked in order from most-likely to match to least likely to match.  The user types in a word or sentence he wishes to find in the database, and all matching results in the Name, Diagnosis or Description fields with be displayed, along with some other relevant test information such as test date and the type of test administered.
The query for handling such a request is the following:</p>
<div>
SELECT 6*(score(1)+score(2))+3*score(3)+score(4) AS rank, p.first_name, p.last_name, test_date, test_type, r.record_id AS rid <br>
FROM radiology_record r, persons p <br>
WHERE p.person_id = r.patient_id <br>
AND (contains(first_name, \'keyword', 1)>0 <br>
OR contains(last_name, \'keyword\', 2) > 0 <br>
OR contains(diagnosis, \'keyword', 3) > 0 <br>
OR contains(description, \'keyword ', 4) > 0 )' <br>
AND test_date >= user_Start_date <br>
AND test_date <= user_End_date <br>
ORDER BY (search parameter). <br>
</div>
<p>
Keyword is the word the user searches for, be it a sentence or a single word.  User_Start_date is the start date the user specifies for the search results to match.  It is not required.  User_end _date is the end date of the search results.   No results will be displayed before the start date or after the end date.
Search parameter is the parameter the user wants the results ordered by.  Whether by relevance or simply test_date, as well as if they want it is ascending or descending order.
</p>

	<H1>Data Analysis Module</H1>
	
	<p>This module allows for OLAP report generation. (OLAP = on-line analytical processing) of the data available in the database to provide with administrators (users named “admin”) access to specific data quickly and efficiently.

The SQL for this module changes depending on what the user searches by, so it cannot be generalized like the other modules can. A table will be created contains the information needed, and OLAP will directly get data from this table, which would   
highly increase the efficiency.  This is the best generalization available for the SQL Query:</p>

<div>
SELECT “parameter” <br>
FROM table_created_for_OLAP <br>
GROUP BY “parameter” <br>
ORDER BY “parameter” <br>
</div>

<p>
Parameter is the query search which depends on what the user requests. It can be name, date and/or test type.
Parameter value is the value given by the user in the appropriate check box</p>
	</body>
</html>