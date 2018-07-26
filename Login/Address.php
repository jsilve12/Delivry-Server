<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	$result = array();
	start();
	
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
		done($response);
	}
	catch(e)
	{
		$result['error'] = "SQL entered improperly";
		done($response);
	}
?>
