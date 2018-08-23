<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	start($pdo);

	$customer = \Stripe\Customer::create(array(
		"email" => $_POST['email'],
  	"description" => "Customer for ".$_POST['email'],
  	"source" => $_POST['payment'] // obtained with Stripe.js
	));

	// $pay = \Stripe\Account::create(array(
  // 	"type" => "express",
  // 	"country" => "US",
  // 	"email" => $_POST['email'],
	// 	"payout_schedule" => array(
  //   	"delay_days" => 8,
  //   	"interval" => "daily"
  // 	)));
	// echo($pay);

		//Adds the address to the users SQL entry
		try
		{
		$stmt = $pdo->prepare("UPDATE People SET charge = :ch, payment = :pay WHERE email = :em");
		$stmt->execute(array(
			":pay" => $_POST['account'],
			":ch" => $customer['id'],
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
