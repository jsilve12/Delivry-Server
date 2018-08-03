<?php
require_once("../Order/Functions.php");
start($pdo);
try {
  //Gets the store id
  $stmt = $pdo->prepare("SELECT store_id From Stores WHERE name = :na");
  $stmt->execute(array(
    ":na" => $_POST['store']
  ));
  $store_id = $stmt->FetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  $response['error'] = "SQL Error";
  done($response);
}

try {
  //Gets the item id
  $stmt = $pdo->prepare("SELECT item_id FROM Items WHERE name = :na");
  $stmt->execute(array(
    ":na" => $_POST['item']
  ));
  $item_id = $stmt->FetchAll(PDO::FETCH_ASSOC);
  if(empty($item_id))
  {
    $response['error'] = "Item doesn't exist";
    done($response);
  }
} catch (\Exception $e) {
  $response['error'] = "SQL Error";
  done($response);
}

try {
  if(empty($store_id))
  {
    $stmt1 = $pdo->prepare("SELECT avg(price) FROM stores_item WHERE item_id = :ii");
    $stmt1->execute(array(
      ":ii" => $item_id[0]['item_id']
    ));
  }
  else {
    $stmt1 = $pdo->prepare("SELECT avg(price) FROM stores_item WHERE item_id = :ii AND store_id = :si");
    $stmt1->execute(array(
      ":ii" => $item_id[0]['item_id'],
      ":si" => $store_id[0]['store_id']
    ));
  }
  $response['results'] = $stmt1->FetchAll(PDO::FETCH_ASSOC);
  done($response);
} catch (\Exception $e) {
  $response['error'] = "SQL error";
  done($response);
}
?>
