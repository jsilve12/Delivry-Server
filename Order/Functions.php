<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Delivry','Delivry', 'GPQKGLjb0tvNWv1A');
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$_POST = json_decode(trim(file_get_contents("php://input")), true);
	class locat
	{
		var $name;
		var $addr;
		var $descript;
		var $long;
		var $lat;

		function __construct0($d)
		{
			$this->name = $d;
		}
		function __construct($a, $b, $c, $d, $e)
		{
			$this->addr = $a;
			$this->long = $b;
			$this->lat = $c;
			$this->name = $d;
			$this->descript = $e;
		}
	}
	class item
	{
		var $name;
		var $description;

		function __construct0($a, $b)
		{
			$this->name=$a;
			$this->description=$b;
		}
	}
	class order
	{
		var $placed_by;
		var $accepted_by;
		var $store;
		var $destination;
		var $items = array();

		function __construct($a, $b, $c, $d)
		{
			$this->placed_by = $a;
			$this->store = $b;
			$this->destination = $c;
			$this->items = $d;
		}
		function __construct1($a, $b, $c)
		{
			$this->placed_by = $a;
			$this->store = $b;
			$this->destination = $c;
		}
		function add_item($item)
		{
			$this->items[] = $item;
		}
	}

	function done($response)
	{
		echo json_encode($response);
		exit;
	}
	function verify_user()
	{
		//Ensures that the user exists
		if(isset($_POST['email']) && isset($_POST['password']))
		{
			$stmt = $pdo->prepare("SELECT * FROM People WHERE email = :em AND password = :pa");
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
		if(isset($_POST['email']) && isset($_POST['password']))
		{
			$stmt = $pdo->prepare("SELECT * FROM People WHERE email = :em AND password = :pa");
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
		//Ensures that the user exists
		if(isset($_POST['email']))
		{
			$stmt = $pdo->prepare("SELECT * FROM People WHERE email = :em");
			$stmt->execute(array(
			    ":em" => $_POST['email']
			));

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($result))
			{
				return $result;
			}
		}
		return false;
	}
	function start()
	{
		if(!verify_user())
		{
			$response['error'] = "User not found";
			done($response);
		}
		return get_user();
	}
	//Don't forget to implement
	function email($content, $recipient)
	{

	}

	function degrees2miles($lat, $long, $diff)
	{

	}
?>
