<?php
	require_once("../Order/Functions.php");

	//Makes sure the user actually exists
	start($pdo);

	// $customer = \Stripe\Customer::create(array(
	// 	"email" => $_POST['email'],
  // 	"description" => "Customer for ".$_POST['email'],
  // 	"source" => $charge['id'] // obtained with Stripe.js
	// ));

	$pay = \Stripe\Account::create(array(
  	"type" => "custom",
  	"country" => "US",
  	"email" => $_POST['email'],
		"payout_schedule" => array(
    	"delay_days" => 8,
    	"interval" => "daily"
  	)
	// 	'default_currency' => 'usd',
	// 	'tos_acceptance' => array(
	// 		'date' => $_POST['date'],
	// 		'ip' => $_POST['ip'],
	// 		'user_agent' => $_POST['user_agent']
	// 	),
	// 	"legal_entity" => array(
	// 		'first_name' => $_POST['first_name'],
	// 		'last_name' => $_POST['last_name'],
	// 		'type' => 'individual',
	// 		"dob" => array(
	// 			'day' => (int) ($_POST['day']),
	// 			'month' => (int) ($_POST['month']),
	// 			'year' => (int) ($_POST['year'])
	// 		),
	// 		'address' => array(
	// 			'line1' => $_POST['line1'],
	// 			'city' => $_POST['city'],
	// 			'postal_code' => $_POST['postal'],
	// 			'state' => $_POST['state'],
	// 			'country' => "US"
	// 		)
	// 	)
		));

		//Adds the address to the users SQL entry
		try
		{
		$stmt = $pdo->prepare("UPDATE People SET charge = :ch, payment = :pay WHERE email = :em");
		$stmt->execute(array(
			":pay" => $pay['id'],
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
