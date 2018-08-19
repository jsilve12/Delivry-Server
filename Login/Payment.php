<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	start($pdo);

		//Adds the address to the users SQL entry
		try
		{
		$stmt = $pdo->prepare("UPDATE People SET payment = :pay WHERE email = :em");
		$stmt->execute(array(
			":pay" => $_POST['payment'],
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
