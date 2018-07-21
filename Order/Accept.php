<?php
	require_once("Functions.php");
	start();
	
	//The current user is considered to be the one placing the order
	
	if(!isset($_POST['order_id']))
	{
		$response['error'] = "Order id not found";
		done();
	}
	
	try
	{
		//Pulls the order from the database
		$stmt = $pdo->prepare("SELECT p.order_id, p.placed_by, p.address, p.addr_description, p.longitude, p.latitude, p.store, i.description, i.order_id FROM Order_Placed AS p INNER JOIN Items_Placed AS i ON p.order_id=i.order.id WHERE p.order_id = :ID");
		$stmt->execute(array(
			":ID" = $_POST['order_id']
		));
		$result = $stmt->FetchAll(PDO::FETCH_ASSOC);
	}
	catch(e)
	{
		
	}
	if(!empty($result))
	{
		
	}
	
?>