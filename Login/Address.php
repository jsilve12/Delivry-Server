<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	$result = array();
	start($pdo);

		//Adds the address to the users SQL entry
		try
		{
		$stmt = $pdo->prepare("UPDATE People SET address = :adr, longitude = :long, latitude = :lat WHERE email = :em");
		$stmt->execute(array(
			":adr" => $_POST['address'],
			":long" => $_POST['longitude'],
			":lat" => $_POST['latitude'],
			":em" => $_POST['email']
		));
		$response['success'] = "Success";
		done($response);
	}
	catch(\Exception $e)
	{
		$response['error'] = "SQL entered improperly";
		done($response);
	}
?>
