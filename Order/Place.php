<?php
	require_once("Functions.php");
	$user = get_user();
	if(empty($user))
	{
		$response['error'] = "User doesn't exist";
		done();
	}
	
	//Verifies the required input is available
	if(!(isset($_POST['items']) && isset($_POST['address']) && isset($_POST['addr_desc']) && isset($_POST['long']) && isset($_POST['lat']) && isset($_POST['store'])))
	{
		$response['error'] = "Missing a variable";
		done();
	}
	
	//Creates the Order Placed entry
	try
	{
		//Retrieves the store
		$stmt = $pdo->preare("SELECT * FROM Stores WHERE name = :na");
		$stmt->execute(array(
			":na" => strtolower(trim($_POST['store']))
		));
		$result = $stmt->FetchAll(PDO::FETCH_ASSOC);
		
		$store_id;
		//Adds the store if it doesn't exis
		if(empty($result))
		{
			$stmt = $pdo->prepare("INSERT INTO Stores(name, num_called) VALUES(:na, 1)");
			$stmt->execute(array(
				":na" => $_POST['store']
			));
			$store_id = $pdo->lastInsertId();
		}
		//Otherwise the store id is retrived, and the number of times
		//that store has been called is updated
		else
		{
			$store_id = $result[0]['store_id'];
			
			$stmt = $pdo->prepare("UPDATE Stores SET num_called = ".++$result[0]['num_called']." WHERE store_id = ".$result[0]['store_id']);
			$stmt->execute();
		}
		
		//Enters into the order_placed table
		$stmt = $pdo->prepare("INSERT INTO Order_Placed(placed_by, address, addr_description, longitude, latitude, store) VALUES(:pb, :addr, :ad_de, :long, :lat, :stor)");
		$stmt->execute(array(
			":pb" => $user[0]['people_id'],
			":addr" => $_POST['address'],
			":ad_de" => $_POST['addr_desc'],
			":long" => $_POST['long'],
			":lat" => $_POST['lat'],
			":stor" => $store_id;
		));
		$order_id = $pdo->lastInsertId();
		
		//Enters in each item
		foreach($_POST['items'] as $key => $value)
		{
			//Retrieves the item name from the database
			$stmt = $pdo->prepare("SELECT * FROM Items WHERE name = :na");
			$stmt->execute(array(
				":na" => $key
			));
			$result1 = $stmt->FetchAll(PDO::FETCH_ASSOC);
			$item_id;
			
			//Adds the item if it doesn't exist
			if(empty($result))
			{
				$stmt = $pdo->prepare("INSERT INTO Items(name, num_called) VALUES(:na, 1)");
				$stmt->execute(array(
					":na" => $key;
				));
				$item_id = $pdo->lastInsertId();
			}
			//Otherwise gets the item id and increments the number of times the item has been called
			else
			{
				$item_id = $result[0]['item_id'];
				$stmt = $pdo->prepare("UPDATE * Items SET num_called = ".++($result1[0]['num_called']));
			}
			
			//Deals with the table that tracks how many times an item is purchased at a store
			$stmt = $pdo->prepare("SELECT * FROM stores_item WHERE store_id = ".$store_id." item_id = ".$item_id);
			$stmt->execute();
			$result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			//Adds a Row if the combination doesn't exist
			if(empty($result2))
			{
				$stmt = $pdo->prepare("INSERT INTO stores_item(store_id, item_id, count) VALUES(".$store_id.", ".$item_id.",1"));
				$stmt->execute();
			}
			//Otherwise the row is updated
			else
			{
				$stmt = $pdo->prepare("UPDATE stores_item SET count ".++$result[0]['count']." WHERE store_id = ".$store_id." item_id = ".$item_id);
				$stmt->execute();
			}
			
			//Connects the item to the order
			$stmt = $pdo->prepare("INSERT INTO Items_Placed(order_id, description, item_id) VALUES(".$order_id", :desc, ".$item_id.")");
			$stmt->execute(array(
				":desc" => $value
			));
		}
	}
	catch
	{
		$response['error'] = "Error creating the entry in the table";
	}
?>