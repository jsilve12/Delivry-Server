<?php
	require_once("Functions.php");
	//Tracks what goes back to the app
	$response = array();
	
	//Finds the appropriate salt based on the email
	if(!isset($_POST['email']) || strlen($_POST['email']) < 1 || !strpos($_POST['email'])))
	{
		$response['error'] = "No email entered email";
		done();
	}
	
	else if(!isset($_POST['password']) || strlen($_POST['password']) < 1)
	{
		$response['error'] = "No entered password";
		done();
	}
	
	else
	{
		$stmt = $pdo->prepare("SELECT salt, email, password FROM People WHERE email = :em");
		$stmt->execute(array(
			":em" => $_POST['email']
		));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC)
	
		//Processes the login based on whether there is an associated user
		if(empty($result))
		{
			$response['error'] = "There is no user associated with that email account";
			done();
		}
		//Sees if the passwords match
		$entered = hash("md5", $result[0]['salt'].$_POST['password']);
		
		if($entered == $result['password'])
		{
			$response['user'] = $_POST['email'];
			$response['pass'] = $result['password'];
			done();
		}
		else
		{
			$response['error'] = "Incorrect Password";
			done();
		}
	}
?>