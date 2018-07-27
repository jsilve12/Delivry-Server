<?php
	require_once("../Order/Functions.php");
	//Tracks what goes back to the app
	$response = array();

	//Finds the appropriate salt based on the email
	if(!isset($_POST['email']) || strlen($_POST['email']) < 1 || !strpos($_POST['email'], "@"))
	{
		$response['error'] = "No email entered email";
		done($response);
	}

	else if(!isset($_POST['password']) || strlen($_POST['password']) < 1)
	{
		$response['error'] = "No entered password";
		done($response);
	}

	else
	{
		try {
			$stmt = $pdo->prepare("SELECT salt, email, password FROM People WHERE email = :em");
			$stmt->execute(array(
				":em" => $_POST['email']
			));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//Processes the login based on whether there is an associated user
			if(empty($result))
			{
				$response['error'] = "There is no user associated with that email account";
				done($response);
			}
			//Sees if the passwords match
			$entered = hash("md5", $result[0]['salt'].$_POST['password']);
			if($entered == $result[0]['password'])
			{
				$user = array(
					'user' => $_POST['email'],
					'pass' => $result[0]['password']
				);
				$response['success'] = $user;
				done($response);
			}
			else
			{
				$response['error'] = "Incorrect Password";
				done($response);
			}
		} catch (\Exception $e) {
			$response['error'] = "SQL error";
			done($response);
		}
	}
?>
