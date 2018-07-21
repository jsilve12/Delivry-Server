<?php
  //You can only do this if your order hasn't been accepted yet
  //TODO: Change the numbers in how often stores are hit and how items correlate to stores
  require_once("Functions.php");
  start();

  if("SELECT * FROM Order_Placed WHERE order_id = ".$_POST['order_id'])
  {
    $response['error'] = "Order can not be deleted";
    done();
  }
  $changes = array();
  $items = array();
  $sql_stmt = "UPDATE Order_Placed SET ";
  foreach($_POST as $key => $value)
  {
    //So you don't accidentally change extraneous values
    if($key == "email" || $key == "password" || $key == "order_id") continue;

    //If you changing an item send in a key => value array with the name => description, and send in all items that are remaining
    if($key = "items")
    {
      $items = $value;
    }
    //This part adds the change to the array that tracks changes
    $changes[":".$key] => $value;
    $sql_stmt .= $key."= :".$key;
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
    //Enters the ones that remain back in
    $stmt = $pdo->prepare("INSERT INTO Items_Placed(order_id, description, item_id) VALUES(:oi, :de, :ii)");
    $stmt->execute(array(
      ":oi" => $_POST['order_id'],
      ":de" => $value,
      ":ii" => $key
    ));
  }
?>
