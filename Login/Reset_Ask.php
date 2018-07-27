<?php
	require_once("../Order/Functions.php");
	$response = array();

	//Verifies that its a real user
	$result = get_user_email($pdo);
	if(empty($result))
	{
		$response['error'] = "User not found";
		done($response);
	}
	try {
		//Creates a new, temporary, password (thanks @https://www.thecodedeveloper.com/generate-random-alphanumeric-string-with-php/)
		$temp_pass = substr(base_convert(sha1(mt_rand()), 16, 36),0, 8);
		$stmt = $pdo->prepare("INSERT INTO Reset_Password (people_id,temp_pass) VALUES( :pi, :tp)");
		$stmt->execute(array(
			":pi" => $result[0]['people_id'],
			":tp" => $temp_pass
		));
		//TODO: Send an email to the person with the temp password

		$response['success'] = "Sent a reset email";
		done($response);
	} catch (\Exception $e) {
		$response['error'] = "SQL error";
		done($response);
	}
?>
