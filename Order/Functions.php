<?php
	require_once __DIR__ . "/../vendor/autoload.php";
	\Stripe\Stripe::setApiKey('sk_test_2CM2jTpTPsbdran63JwKoPkN');
	$charge = \Stripe\Token::create(array(
  "card" => array(
    "number" => "4000 0000 0000 0077",
    "exp_month" => 8,
    "exp_year" => 2019,
    "cvc" => "314"
  )));

	//Pings the payment servers
	$charge = \Stripe\Charge::create(array(
		"amount" => ceil(100000),
		"currency" => "usd",
		"customer" => "cus_DS5ya1uJ1Qt8o9",
		"transfer_group" => "10"
	));

	$transfer = \Stripe\Transfer::create(array(
		"amount" => floor(2104),
		"currency" => "usd",
		"destination" => "acct_1D1BesBhVnweyBvR",
		"transfer_group" => "10"
	));

	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Delivry','Delivry', 'GPQKGLjb0tvNWv1A');
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$_POST = json_decode(trim(strtolower(file_get_contents("php://input"))), true);
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
	function verify_user($pdo)
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
	function get_user($pdo)
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
	function get_user_email($pdo)
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
	function start($pdo)
	{
		if(!verify_user($pdo))
		{
			$response['error'] = "User not found";
			done($response);
		}
		return get_user($pdo);
	}

	function string2arr($arr, $is_items)
	{
		$output = array();
		$array = explode(",",$arr);
		foreach($array as $it)
		{
			//Makes sure the value has a size
			if(strlen(substr($it,strpos($it, ">")))>1)
			{
				list($key, $value) = explode("=>", $it);
				if(!$is_items)
				{
					$output[trim($key)] = trim($value);
				}
				if($is_items)
				{
					$output[trim($key)] = explode(".", $value);
				}
			}
		}
		return $output;
	}
	//Don't forget to implement
	function email($content, $recipient)
	{

	}

	function miles2degrees($lat, $long, $diff)
	{
		//Here's where I got these formulas: https://www.movable-type.co.uk/scripts/latlong.html
		//The angle being traversed as a fraction of the earth's surface
		$c = $diff/3963.17;

		//Check the website formula. This appears to turn the area traversed into radians
		$a = pow(tan((1/2)*$c),2)/(1-pow(tan((1/2)*$c),2));

		//This turns the the area traversed into delta lat and delta long
		//Please note that I'm approximating lat2 (destination) as lat1, because the difference is so small
		$long = 2*asin(sqrt($a/(pow(cos(deg2rad($lat)),2))));
		$lat = 2*asin(sqrt($a));
		return array($long, $lat);
	}
?>
