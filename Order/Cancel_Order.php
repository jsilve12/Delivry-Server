<?php
  require_once("Functions.php");
  start();

  try {
    //You can only do this if the order hasn't been accepted, this makes sure they haven't
    $stmt = $pdo->prepare("SELECT * FROM Order_Placed WHERE order_id = :oi");
    $stmt->execute(array(
      ":oi" => $_POST['order_id']
    ));
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
  }
  if(empty($result))
  {
    $response['error'] = "That entry doesn't exist";
    done($response);
  }
  try {
    //Actually deletes the entries
    $stmt = $pdo->prepare("DELETE FROM Order_Placed WHERE order_id = :oi");
    $stmt->execute(array(
      ":oi" => $_POST['order_id']
    ));
    //Technically this shouldn't be necessary but for good measure
    $stmt = $pdo->prepare("DELETE FROM Items_Placed WHERE order_id = :oi");
    $stmt->execute(array(
      ":oi" => $_POST['order_id']
    ));
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
  }
  $response['success'] = "success";
  done($response);
?>
