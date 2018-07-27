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

  //Adds the entry into the new table
  try {
    $stmt = $pdo->prepare("INSERT INTO Order_Finished(placed_by, accepted_by, receipt, price, address, addr_description, longitude, latitude, store) VALUES(:pb,:ab,:re,:pr,:ad,:ad_de,:lo,:la,:st)");
    $stmt->execute(array(
      ":pb" => $result[0]["placed_by"],
      ":ab" => $result[0]["accepted_by"],
      ":re" => $_POST["receipt_name"],
      ":pr" => strtolower(trim($_POST['price'])),
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
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
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
    foreach($result as $value)
    {
      try {
        //Enters each item back into the database
        $stmt = $pdo->prepare("INSERT INTO Items_Finished(order_id, description, item_id) VALUES(:oi, :de, :ii)");
        $stmt->execute(array(
          ":oi" => $id,
          ":de" => $value['description'],
          ":ii" => $value['item_id']
        ));
      } catch (\Exception $e) {
        $response['error'] = "SQL error";
        done($response);
      }
    }
    //Deletes everything
    $stmt = $pdo->prepare("DELETE FROM Order_Accepted WHERE order_id= ".$_POST['order_id']);
    $stmt->execute();
  $response['success'] = "success";
  done($response);
?>
