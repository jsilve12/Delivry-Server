<?php
  require_once("Functions.php");
  start();
  try {
    //Makes sure the order hasn't been completed
    $stmt = $pdo->prepare("SELECT * FROM Order_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
    if(empty($result))
    {
      $response['error'] = 'Order not found';
      done();
    }
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
  }

  //Moves the order back into order placed
  try {
    $stmt = $pdo->prepare("INSERT INTO Order_Placed(placed_by, address, addr_description, longitude, latitude, store) VALUES(:pb, :ad, :ad_de, :lo, :la, :st)");
    $stmt->execute(array(
      ":pb" => $result[0]['placed_by'],
      ":ad" => $result[0]['address'],
      ":ad_de" => $result[0]['addr_description'],
      ":lo" => $result[0]['longitude'],
      ":la" => $result[0]['latitude'],
      ":st" => $result[0]['store']
    ));
    $id = $pdo->LastInsertId();

    //Gets the items;
    $stmt = $pdo->prepare("SELECT * FROM Items_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
    foreach($result as $key => $value)
    {
      $stmt = $pdo->prepare("INSERT INTO Items_Placed WHERE order_id = ".$id);
      $stmt->execute();
    }
    //Deletes the order from Order_Accepted
    $stmt = $pdo->prepare("DELETE FROM Order_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
    done();
  }

?>
