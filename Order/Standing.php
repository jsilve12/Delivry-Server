<?php
	require_once("Functions.php");
	start();
	
	if(!isset($_POST['long']) || !isset($_POST['lat']))
	{
		$response['error'] = "Longitude or Latitude are missing";
		done();
	}
	$arr = miles2degrees($_POST['lat'], $_POST['long'], $_POST['diff']);

	//When a store is specified
	if(isset($_POST['store']))
	{
		$stmt = $pdo->prepare("SELECT o.placed_by, o.address, o.addr_description, o.longitude, o.latitude, s.name, i.item_id, it.description, it.item_id, it.name, FROM (Order_Placed AS o INNER JOIN Stores AS s ON o.store = s.store_id INNER JOIN Items_Placed AS i ON o.order_id = i.order_id INNER JOIN Items AS it i.item_id = it.item_id WHERE o.longitude >= :lonl, o.longitude <= :lonh, o.latitude >= :latl, o.latitude <= :lath, s.name = :sn");
		$stmt->execute(array(
			":lonl" => $_POST['long'] - $arr[0],
			":lonh" => $_POST['long'] + $arr[0],
			":latl" => $_POST['lat'] - $arr[1],
			":lath" => $_POST['lat'] + arr[1],
			":sn" => $_POST['store']
		));
		$result['orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
		done();
	}
	//If there is no store
	else
	{
		$stmt = $pdo->prepare("SELECT o.placed_by, o.address, o.addr_description, o.longitude, o.latitude, s.name, i.item_id, it.description, it.item_id, it.name FROM (Order_Placed AS o INNER JOIN Stores AS s ON o.store = s.store_id INNER JOIN Items_Placed AS i ON o.order_id = i.order_id INNER JOIN Items AS it i.item_id = it.item_id WHERE o.longitude >= :lonl, o.longitude <= :lonh, o.latitude >= :latl, o.latitude <= :lath");
		$stmt->execute(array(
			":lonl" => $_POST['long'] - $arr[0],
			":lonh" => $_POST['long'] + $arr[0],
			":latl" => $_POST['lat'] - $arr[1],
			":lath" => $_POST['lat'] + arr[1]
		));
		$result['orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
		done();
	}
?>