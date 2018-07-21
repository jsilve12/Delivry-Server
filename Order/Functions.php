<?php
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
		
		function __construct($a, $b, $c, $d);
		{
			$this->placed_by = $a;
			$this->store = $b;
			$this->destination = $c;
			$this->items = $d;
		}
		function __construct1($a, $b, $c);
		{
			$this->placed_by = $a;
			$this->store = $b;
			$this->destination = $c;
		}
		function add_item($item)
		{
			this->items[] = $item;
		}
	}
	
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
	function start()
	{
		if(!verify_user())
		{
			response['error'] = "User not found";
			done();
		}
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
	//Don't forget to implement
	function email($content, $recipient)
	{
		
	}
	
	function degrees2miles($lat, $long, $diff)
	{
		
	}
?>