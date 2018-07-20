<?php
	require_once("Functions.php");
	
	//Makes sure the user actually exists
	$result = array();
	if(!verify_user())
	{
		$result['error'] = "User doesn't exist";
		done();
	}
		//Adds the address to the users SQL entry
		try(
		{
		$stmt = $pdo->prepare("UPDATE People SET address = :adr, longitude = :long, latitude = :lat WHERE email = :em, password = :pa");
		$stmt->execute(array(
			":adr" => $_POST['address'],
			":long" => $_POST['longitude'],
			":lat" => $_POST['latitude'],
			":em" => $_POST['email'],
			":pa" => $_POST['password']
		));
		$result['success'] = "Success";
		done();
	}
	catch(e)
	{
		$result['error'] = "SQL entered improperly";
		done();
	}
?>