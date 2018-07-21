<?php
  //You can only do this if your order hasn't been accepted yet
  //TODO: Trim and tolower all user data that goes into a database
  //TODO: Change the numbers in how often stores are hit and how items correlate to stores
  require_once("Functions.php");
  start();

  $stmt = $pdo->prepare("SELECT * FROM Order_Placed WHERE order_id = ".$_POST['order_id']);
  $stmt->execute();
  $result = $stmt->FetchAll(PDO::FETCH_ASSOC);

  if(empty($result))
  {
    $response['error'] = "Order can not be Amended";
    done();
  }
  $changes = array();
  $items = array();
  $sql_stmt = "UPDATE Order_Placed SET ";
  foreach($_POST as $key => $value)
  {
    //So you don't accidentally change extraneous values
    if($key == "email" || $key == "password" || $key == "order_id") continue;

    if ($key == "store") {
      //Retrieves the store
  		$stmt = $pdo->preare("SELECT * FROM Stores WHERE name = :na");
  		$stmt->execute(array(
  			":na" => strtolower(trim($value))
  		));
  		$result = $stmt->FetchAll(PDO::FETCH_ASSOC);

  		$store_id
  		//Adds the store if it doesn't exist
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
      }
      $value = $store_id;
    }
    //If you changing an item send in a key => value array with the name => description, and send in all items that are remaining
    if($key == "items")
    {
      $items = $value;
    }
    else {
      //This part adds the change to the array that tracks changes
      $changes[":".$key] => $value;
      $sql_stmt .= $key."= :".$key;
    }

  }
  //Adds the WHERE clause
  $sql_stmt .= " WHERE order_id = ".$_POST['order_id'];
  $stmt = $pdo->prepare($sql_stmt);
  $stmt->execute($changes);

  //Makes the necessary changes to the items
  //Starts by deleting all the existing Items
  $stmt = $pdo->prepare("DELETE FROM Items_Placed WHERE order_id = ".$_POST['order_id']);
  foreach($items as $key => $value)
  {
    //Gets the id of the items (TODO:This could be a function, because this code is from Place)
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
    }

    //Enters the ones that remain back in
    $stmt = $pdo->prepare("INSERT INTO Items_Placed(order_id, description, item_id) VALUES(:oi, :de, :ii)");
    $stmt->execute(array(
      ":oi" => $_POST['order_id'],
      ":de" => $value,
      ":ii" => $item_id
    ));
  }
?>
