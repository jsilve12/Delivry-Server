<?php
  require_once("Functions.php");
  start();

  //You can only do this if the order hasn't been accepted, this makes sure they haven't
  $stmt = $pdo->prepare("SELECT * FROM Order_Placed WHERE order_id = :oi");
  $stmt->execute(array(
    ":oi" => $_POST['order_id']
  ));
  $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
  if(empty($result))
  {
    $response['error'] = "That entry doesn't exist";
    done();
  }

  //Actually deletes the entries
  $stmt = $pdo->prepare("DELETE FROM Order_Placed WHERE order_id = :oi");
  $stmt->execute(array(
    ":oi" => $_POST['order_id']
  ));
  $stmt = $pdo->prepare("DELETE FROM Items_Placed WHERE order_id = :oi");
  $stmt->execute(array(
    ":oi" => $_POST['order_id']
  ));
  $response['succes'] = "Order Canceled";
  done();
?>
