<?php
	require_once("Functions.php");
	
	//Gets and verifies the user
	$result = get_user_email();
	if(empty($result))
	{
		$response['error'] = "User doesn't exist";
		done();
	}
	
	//Gets and verifies the temp password
	$stmt = $pdo->prepare("SELECT * FROM Reset_Password WHERE people_id = :pi");
	$stmt->execute(array(
		":pi" => $result[0]["people_id"];
	));
	$result1 = $stmt->FetchAll(PDO::FETCH_ASSOC);
	if(empty($result1))
	{
		$response['error'] = "User doesn't need a password reset";
		done();
	}
	if($result1[0]['temp_pass'] != $_POST['temp_pass'])
	{
		$response['error'] = "Thats the incorrect temporary passowrd";
		done();
	}

	//Enters the temp password into the database
	stmt1 = $pdo->prepare("UPDATE People SET password = :pa WHERE people_id = :pi");
	stmt1->execute(array(
		":pa" => hash("md5", $result[0]["salt"].$_POST["password"]),
		":pi" => $result[0]['people_id']
	));
?>