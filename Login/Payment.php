<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	start($pdo);

	//Adds the address to the users SQL entry
	try
	{
		$stmt = $pdo->prepare("SELECT Charge FROM People WHERE email = :em");
		$stmt->execute(array(
			":em" => $_POST['email']
		));
	}
	catch(\Exception $e)
	{
		$response['error'] = "SQL entered improperly";
		done($response);
	}

	try {
    $key = \Stripe\EphemeralKey::create(
      array("customer" => $stmt['charge']),
      array("stripe_version" => $_POST['api_version'])
    );
    header('Content-Type: application/json');
    exit(json_encode($key));
	} catch (Exception $e) {
		$response['error'] = "Error Getting key from Stripe";
		done($response);
	}
?>
