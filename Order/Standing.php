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
	if(isset($_POST['store']) && !empty(trim($_POST['store'])))
	{
		try {
			$stmt = $pdo->prepare("SELECT o.placed_by, o.address, o.addr_description, o.longitude, o.latitude, s.name, i.item_id, i.description, it.item_id, it.name FROM Order_Placed AS o JOIN Stores AS s ON o.store = s.store_id JOIN Items_Placed AS i ON o.order_id = i.order_id JOIN Items AS it ON i.item_id = it.item_id WHERE o.longitude >= :lonl AND o.longitude <= :lonh AND o.latitude >= :latl AND o.latitude <= :lath AND s.name = :sn GROUP BY o.order_id, s.store_id, i.item_id, i.description");
			$stmt->execute(array(
				":lonl" => $_POST['long'] - $arr[0],
				":lonh" => $_POST['long'] + $arr[0],
				":latl" => $_POST['lat'] - $arr[1],
				":lath" => $_POST['lat'] + $arr[1],
				":sn" => $_POST['store']
			));
			$response['orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
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
			$stmt = $pdo->prepare("SELECT o.placed_by, o.address, o.addr_description, o.longitude, o.latitude, s.name, i.item_id, i.description, it.item_id, it.name FROM Order_Placed AS o  JOIN Stores AS s ON o.store = s.store_id JOIN Items_Placed AS i ON o.order_id = i.order_id JOIN Items AS it on i.item_id = it.item_id WHERE o.longitude >= :lonl AND o.longitude <= :lonh AND o.latitude >= :latl AND o.latitude <= :lath GROUP BY o.order_id, s.store_id, i.item_id, i.description");
			$stmt->execute(array(
				":lonl" => $_POST['long'] - $arr[0],
				":lonh" => $_POST['long'] + $arr[0],
				":latl" => $_POST['lat'] - $arr[1],
				":lath" => $_POST['lat'] + $arr[1]
			));
			$response['orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
			done($response);
		} catch (\Exception $e) {
			$response['error'] = "SQL error";
			done($response);
		}
	}
?>
