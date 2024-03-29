<?php
	require_once("Functions.php");
	$user = start($pdo);
	$response;

	//The current user is considered to be the one placing the order

	if(!isset($_POST['order_id']))
	{
		$response['error'] = "Order id not found";
		done($response);
	}

	//Makes sure the user has an associated card
	try{
		$stmt = $pdo->prepare("SELECT payment FROM People WHERE people_id=".$user[0]['people_id']." IS NOT NULL");
		$stmt->execute();
		if(empty($stmt))
		{
			$response['error'] = "Person doesn't have payment information";
			done($response);
		}
	} catch (\Exception $e) {
		$response['error'] = "SQL error";
		done($response);
	}

	try
	{
		//Pulls the order from the database
		$stmt = $pdo->prepare("SELECT p.order_id, p.placed_by, p.address, p.addr_description, p.longitude, p.latitude, p.store, i.description, i.order_id, i.item_id, i.price FROM Order_Placed AS p INNER JOIN Items_Placed AS i ON p.order_id=i.order_id WHERE p.order_id = :ID");
		$stmt->execute(array(
			":ID" => $_POST['order_id']
		));
		$result = $stmt->FetchAll(PDO::FETCH_ASSOC);
	}
	catch(\Exception $e)
	{
		$response['error'] = "SQL error";
		done($response);
	}
	if(empty($result))
	{
		$response['error'] = "Result set from database is empty";
		done($response);
	}

	//Enters the order into the new database
	try {
		//Enters the order into the accepted database
		$stmt = $pdo->prepare("INSERT INTO Order_Accepted(placed_by, accepted_by, address, addr_description, longitude, latitude, store) VALUES(:pb, :ab, :addr, :ad_de, :lo, :la, :st)");
		$stmt->execute(array(
			":pb" => $result[0]["placed_by"],
			":ab" => $user[0]["people_id"],
			":addr" => $result[0]["address"],
			":ad_de" => $result[0]["addr_description"],
			":lo" => $result[0]["longitude"],
			":la" => $result[0]["latitude"],
			":st" => $result[0]["store"]
		));
		$ID = $pdo->LastInsertId();

		//Deletes the object in the items placed database
		$stmt = $pdo->prepare("DELETE FROM Order_Placed WHERE order_id = :oi");
		$stmt->execute(array(
			":oi" => $_POST['order_id']
		));

		//Adds the items to the order
		foreach($result as $value)
		{
			//Ensures it corresponds to the correct order
			if($value["order_id"] != $_POST['order_id']) continue;

			//Enters it into the Items Accepted database
			$stmt = $pdo->prepare("INSERT INTO Items_Accepted(order_id, description, item_id, price) VALUES(:oi, :de, :ii, :p)");
			$stmt->execute(array(
				":oi" => $ID,
				":de" => $value['description'],
				":ii" => $value['item_id'],
				":p" => $value['price']
			));
		}

	} catch (\Exception $e) {
		$response['error'] = "SQL error";
		done($response);
	}
	$response['success'] = "Success";
	done($response);

?>
