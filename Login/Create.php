<?php
	require_once("../Order/Functions.php");
	$respone = array();
	//Makes sure that all the enteries are entered to create the new account
	if(!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['salt']) || !isset($_POST['password']) || strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['salt']) < 1 || strlen($_POST['password']) < 1)
	{
		$response['error'] = "A Field was Missing";
		done($response);
	}
	//Checks to make sure there's an @
	if(!strpos($_POST['email'], "@"))
	{
		$response['error'] = "Email needs to contain an @ sign";
		done($response);
	}

	//Creates the account with stripe
	$customer = \Stripe\Customer::create(array(
		"email" => $_POST['email'],
  	"description" => "Customer for ".$_POST['email']
	));

	//Inserts into the database
	try
	{
		$stmt = $pdo->prepare("INSERT INTO People(name, email, salt, password, charge) VALUES(:na, :em, :sa, :pa, :ch) ");
		$stmt->execute(array(
			":na" => $_POST['name'],
			":em" => $_POST['email'],
			":sa" => $_POST['salt'],
			":pa" => hash("md5",$_POST['salt'].$_POST['password']),
			":ch" => $customer['id']
		));
		$response['success'] = "Account created";
		done($response);
		//TODO: Send email to User
	}
	catch(\Exception $e)
	{
		$response['error'] = "Unknown error submitting to database";
		done($response);
	}
?>
