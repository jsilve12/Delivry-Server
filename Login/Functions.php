<?php
	function done()
	{
		echo json_encode($response);
		exit;
	}
	function verify_user()
	{
		//Ensures that the user exists
		if(isset($_POST['email']) && isset($_POST['password'])
		{
			$stmt = $pdo->prepare("SELECT * FROM People WHERE email = :em, password = :pa");
			$stmt->execute(array(
			    ":em" => $_POST['email'],
				":pa" => $_POST['password']
			));
			
			if(!empty($stmt->fetchAll(PDO::FETCH_ASSOC)))
			{
				return true;
			}
		}
		return false;
	}
	
	function get_user()
	{
		//Ensures that the user exists
		if(isset($_POST['email']) && isset($_POST['password'])
		{
			$stmt = $pdo->prepare("SELECT * FROM People WHERE email = :em, password = :pa");
			$stmt->execute(array(
			    ":em" => $_POST['email'],
				":pa" => $_POST['password']
			));
			
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($result))
			{
				return $result;
			}
		}
		return false;
	}
	
	function get_user_email()
	{
		$stmt = $pdo->prepare("SELECT * FROM People WHERE email = :em");
		$stmt->execute(array(
			":em" => $_POST['email']
		));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
?>