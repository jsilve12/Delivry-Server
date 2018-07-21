<?php
  require_once("Functions.php");
  $user = start();
  $response = array();

  //Orders Placed
  try {
    $stmt = $pdo->prepare("SELECT p.address, p.addr_description, p.store, i.name, ip.description FROM Order_Placed AS p JOIN Items_Placed AS ip ON p.order_id = ip.order_id JOIN Items AS i ON i.item_id = ip.item_id WHERE p.placed_by = :pb");
    $stmt->execute(array(
      ":pb" => $user['people_id'];
    ))
    $response['My Placed Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders placed";
    done();
  }

  //Orders Accepted (Either the placer or deliverer)
  try {
    $stmt = $pdo->prepare("SELECT p.address, p.addr_description, p.store, i.name, ip.description, p.accepted_by FROM Order_Acccepted AS p JOIN Items_Accepted AS ip ON p.order_id = ip.order_id JOIN Items AS i on i.item_id = ip.item_id WHERE p.placed_by = :pb OR p.accepted_by = :pb")
    $stmt->execute(array(
      ":pb" =>$user['people_id']
    ));
    $response['Accepted Orders'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "Error with fetching the orders accepted";
  }

  //Orders Finished (Either the placer or deliverer)

  //Conflicts (Either the placer or deliverer)
?>
