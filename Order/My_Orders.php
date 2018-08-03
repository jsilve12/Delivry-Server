<?php
  require_once("Functions.php");
  $user = start($pdo);
  echo(implode($user[0], ' '));
  $response = array();

  //Yes I know theres a lot of repitiion, and this file will be re-written (JS 7-2018)
  //Orders Placed
  try {
    $stmt = $pdo->prepare("SELECT p.order_id, p.address, p.addr_description, p.store, i.name, ip.description, ip.price FROM Order_Placed AS p JOIN Items_Placed AS ip ON p.order_id = ip.order_id JOIN Items AS i ON i.item_id = ip.item_id WHERE p.placed_by = :pb");
    $stmt->execute(array(
      ":pb" => $user[0]['people_id']
    ));
    $response['Placed Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders placed";
    done($response);
  }

  //Orders Accepted (Either the placer or deliverer)
  try {
    $stmt = $pdo->prepare("SELECT p.order_id, p.address, p.addr_description, p.store, i.name, ip.description, p.accepted_by, ip.price FROM Order_Accepted AS p JOIN Items_Accepted AS ip ON p.order_id = ip.order_id JOIN Items AS i on i.item_id = ip.item_id WHERE p.placed_by = :pb OR p.accepted_by = :pb");
    $stmt->execute(array(
      ":pb" =>$user[0]['people_id']
    ));
    $response['Accepted Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders accepted";
  }

  //Orders Finished (Either the placer or deliverer)
  try {
    $stmt = $pdo->prepare("SELECT p.order_id, p.address, p.addr_description, p.store, i.name, ip.description, p.accepted_by, p.price, ip.price FROM Order_Finished AS p JOIN Items_Finished AS ip ON p.order_id = ip.order_id JOIN Items AS i on i.item_id = ip.item_id WHERE p.placed_by = :pb OR p.accepted_by = :pb");
    $stmt->execute(array(
      ":pb" =>$user[0]['people_id']
    ));
    $response['Finished Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders finished";
  }

  //Conflicts (Either the placer or deliverer)
  try {
    $stmt = $pdo->prepare("SELECT p.order_id, p.address, p.addr_description, p.store, i.name, ip.description, p.accepted_by,p.price,p.comments, ip.price FROM Order_Conflict AS p JOIN Items_Conflict AS ip ON p.order_id = ip.order_id JOIN Items AS i on i.item_id = ip.item_id WHERE p.placed_by = :pb OR p.accepted_by = :pb");
    $stmt->execute(array(
      ":pb" =>$user[0]['people_id']
    ));
    $response['Conflict Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders conflicts";
  }
  done($response);

  //Verified (Either the placer or deliverer)
  try {
    $stmt = $pdo->prepare("SELECT p.order_id, p.address, p.addr_description, p.store, i.name, ip.description, p.accepted_by,p.price, ip.price FROM Order_Verified AS p JOIN Items_Verified AS ip ON p.order_id = ip.order_id JOIN Items AS i on i.item_id = ip.item_id WHERE p.placed_by = :pb OR p.accepted_by = :pb");
    $stmt->execute(array(
      ":pb" =>$user[0]['people_id']
    ));
    $response['Conflict Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders Verified";
  }
  done($response);
?>
