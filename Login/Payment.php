<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	start($pdo);

	$source = \Stripe\Source::create(array(
  "type" => "ach_credit_transfer",
  "currency" => "usd",
  "owner" => array(
    "email" => "jenny.rosen@example.com"
  ),
	"token" => $_POST['payment']
));

	$customer = \Stripe\Customer::create(array(
  	"description" => "Customer for ".$_POST['email'],
  	"source" => $source['id'] // obtained with Stripe.js
	));

		//Adds the address to the users SQL entry
		try
		{
		$stmt = $pdo->prepare("UPDATE People SET payment = :pay WHERE email = :em");
		$stmt->execute(array(
			":pay" => $customer['id'],
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
