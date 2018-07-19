<?php
	require_once("Functions.php");
	$response = array();
	
	//Verifies that its a real user
	$result = $get_user_email();
	if(empty($result))
	{
		$response['error'] = "User not found";
		done();
	}

	//Creates a new, temporary, password (thanks @https://www.thecodedeveloper.com/generate-random-alphanumeric-string-with-php/)
	$temp_pass = substr(base_convert(sha1(uniquid(mt_rand())), 16, 36),0, 8);
	$stmt = $pdo->prepare("INSERT INTO Reset_Password people_id = :pi, temp_pass = :tp");
	$stmt->execute(array(
		":pi" => $result[0]['people_id'],
		":tp" => $temp_pass
	));
	//TODO: Send an email to the person with the temp password
	
	$response['success'] = "Sent a reset email";
	done();
?>