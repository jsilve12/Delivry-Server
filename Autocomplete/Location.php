<<?php
  require_once("../Order/Functions.php");
  $person = start($pdo);

  try {
    //Returns first your address, then any other address you've sent to
    $stmt = $pdo->prepare("SELECT address FROM People WHERE people_id = :id, address like :it");
    $stmt->execute(array(
      ":id" => $person[0]["people_id"],
      ":it" => "%".$_POST['address']."%"
    ));
    $response['results'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
    if(empty($response['results']))
    {
      //Make this a function later because it's in price too
      $tab_name = array("Order_Placed", "Order_Accepted", "Order_Finished", "Order_Conflict");
      $stmt
      foreach($tab_name as $value)
      {
        if($stmt != "")
        {
          $stmt .= "(UNION"
        }
        $stmt .= "SELECT address, COUNT(address) FROM ".$value."WHERE address like %:it% "
      }
      $stmt .= ") ORDER BY COUNT(address)";
      $stmt1 = $pdo->prepare($stmt);
      $stmt1->execute(array(
        ":it" => $_POST['address']
      ));
      $response['results'] = array_slice($stmt1->FetchAll(PDO::FETCH_ASSOC), 0, $_POST['num_results'])
    }
  } catch (\Exception $e) {
    $response['error'] = "SQL Error";
  }
  done($response);
?>
