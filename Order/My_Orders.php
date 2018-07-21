<?php
  require_once("Functions.php");
  $user = start();

  $stmt = $pdo->prepare("SELECT p.address, p.addr_description, p.store, i.name, ip.description FROM Order_Placed AS p JOIN Items_Placed AS ip ON p.order_id = ip.order_id JOIN Items AS i ON i.item_id = ip.item_id WHERE p.placed_by = :pb");
  $stmt->execute(array(
    ":pb" => $user['people_id'];
  ))
?>
