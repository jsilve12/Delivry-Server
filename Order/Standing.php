<?php
	require_once("Functions.php");
	start($pdo);

	if(!isset($_POST['long']) || !isset($_POST['lat']))
	{
		$response['error'] = "Longitude or Latitude are missing";
		done($response);
	}
	$arr = miles2degrees($_POST['lat'], $_POST['long'], $_POST['diff']);

	//When a store is specified
	if(isset($_POST['store']))
	{
		try {
			$stmt = $pdo->prepare("SELECT o.placed_by, o.address, o.addr_description, o.longitude, o.latitude, s.name, i.item_id, it.description, it.item_id, it.name, FROM (Order_Placed AS o INNER JOIN Stores AS s ON o.store = s.store_id INNER JOIN Items_Placed AS i ON o.order_id = i.order_id INNER JOIN Items AS it i.item_id = it.item_id WHERE o.longitude >= :lonl AND o.longitude <= :lonh AND o.latitude >= :latl AND o.latitude <= :lath AND s.name = :sn");
			$stmt->execute(array(
				":lonl" => $_POST['long'] - $arr[0],
				":lonh" => $_POST['long'] + $arr[0],
				":latl" => $_POST['lat'] - $arr[1],
				":lath" => $_POST['lat'] + $arr[1],
				":sn" => $_POST['store']
			));
			$result['orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
			done($response);
		} catch (\Exception $e) {
			$response['error'] = "SQL error";
			done($response);
		}
	}
	//If there is no store
	else
	{
		try {
			$stmt = $pdo->prepare("SELECT o.placed_by, o.address, o.addr_description, o.longitude, o.latitude, s.name, i.item_id, it.description, it.item_id, it.name FROM (Order_Placed AS o INNER JOIN Stores AS s ON o.store = s.store_id INNER JOIN Items_Placed AS i ON o.order_id = i.order_id INNER JOIN Items AS it i.item_id = it.item_id WHERE o.longitude >= :lonl AND o.longitude <= :lonh AND o.latitude >= :latl AND o.latitude <= :lath");
			$stmt->execute(array(
				":lonl" => $_POST['long'] - $arr[0],
				":lonh" => $_POST['long'] + $arr[0],
				":latl" => $_POST['lat'] - $arr[1],
				":lath" => $_POST['lat'] + $arr[1]
			));
			$result['orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
			done($response);
		} catch (\Exception $e) {
			$response['error'] = "SQL error";
			done($response);
		}
	}
?>
