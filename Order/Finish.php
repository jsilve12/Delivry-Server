<?php
  //TODO: Add a portal for the receipt to be submitted
  require_once("Functions.php");
  start($pdo);

  //Deletes the entry from Accepted
  $result = array();
  try {
    $stmt = $pdo->prepare("SELECT * FROM Order_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
    if(empty($result))
    {
      $response['error'] = "Order not found";
      done($response);
    }
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
  }
  $id;

  //Computes the price that the driver gets paid
  $driver_pay = 0;
  if((double)$_POST['price'] < 10)
  {
    $driver_pay = ((double) $_POST['price'])*1.25 + ((double) $_POST['distance'])*0.8;
  }
  else {
    $driver_pay = (((double) $_POST['price'])-10)*1.20+2.5+((double) $_POST['distance'])*0.8;
  }
  //Adds the entry into the new table
  try {
    $stmt = $pdo->prepare("INSERT INTO Order_Finished(placed_by, accepted_by, price, distance, paid, charged, address, addr_description, longitude, latitude, store) VALUES(:pb,:ab,:pr,:di,:pa,:ch,:ad,:ad_de,:lo,:la,:st)");
    $stmt->execute(array(
      ":pb" => $result[0]["placed_by"],
      ":ab" => $result[0]["accepted_by"],
      ":pr" => strtolower(trim($_POST['price'])),
      ":di" => strtolower(trim($_POST['distance'])),
      ":pa" => 1.1*$driver_pay,
      ":ch" => $driver_pay,
      ":ad" => $result[0]["address"],
      ":ad_de" => $result[0]["addr_description"],
      ":lo" => $result[0]["longitude"],
      ":la" => $result[0]["latitude"],
      ":st" => $result[0]["store"]
    ));
    $id = $pdo->LastInsertId();
  } catch (\Exception $e) {
    $response['error'] = "SQL error ";
    done($response);
  }
  //Finds the items in the database
  try{
    $stmt = $pdo->prepare("SELECT * FROM Items_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
    $result1 = $stmt->FetchAll(PDO::FETCH_ASSOC);
    if(empty($result))
    {
      $response['error'] = "SQL error";
      done($response);
    }
  }
  catch(\Exception $e)
  {
    $response['error'] = "SQL error";
    done($response);
  }
    foreach($result1 as $value)
    {
      try {
        //Enters each item back into the database
        $stmt = $pdo->prepare("INSERT INTO Items_Finished(order_id, description, item_id, price) VALUES(:oi, :de, :ii, :p)");
        $stmt->execute(array(
          ":oi" => $id,
          ":de" => $value['description'],
          ":ii" => $value['item_id'],
          ":p" => $value['price']
        ));
      } catch (\Exception $e) {
        $response['error'] = "SQL error";
        done($response);
      }
    }
    //Deletes everything
    $stmt = $pdo->prepare("DELETE FROM Order_Accepted WHERE order_id= ".$_POST['order_id']);
    $stmt->execute();

    //Processes the transaction

    //Collects the Money
    $paying = $pdo->prepare("SELECT payment, charge FROM People WHERE people_id =".$result[0]["placed_by"]);
    $paying->execute();
    $result1 = $paying->FetchAll(PDO::FETCH_ASSOC);

    //Pings the payment servers
  	$charge = \Stripe\Charge::create(array(
  		"amount" => ceil(110*$driver_pay),
  		"currency" => "usd",
  		"customer" => $result1[0]['charge'],
  		"transfer_group" => $id
  	));

    //Pays out the money
    $paid = $pdo->prepare("SELECT payment, charge FROM People WHERE people_id =".$result[0]["accepted_by"]);
    $paid->execute();
    $result1 = $paid->FetchAll(PDO::FETCH_ASSOC);

    $transfer = \Stripe\Transfer::create(array(
  		"amount" => floor(100*$driver_pay),
  		"currency" => "usd",
  		"destination" => $result1[0]['payment'],
  		"transfer_group" => $id
  	));

  $response['success'] = "success";
  $response['charge'] = $charge;
  $response['payment'] = $transfer;
  done($response);
?>
