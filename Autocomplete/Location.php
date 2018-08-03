<?php
  require_once("../Order/Functions.php");
  $person = start($pdo);

  try {
    //Returns first your address, then any other address you've sent to
    $stmt = $pdo->prepare("SELECT address FROM People WHERE people_id = :id AND address like :it");
    $stmt->execute(array(
      ":id" => $person[0]["people_id"],
      ":it" => "%".$_POST['name']."%"
    ));
    echo($_POST['name']);
    $response['results'] = $stmt->FetchAll(PDO::FETCH_ASSOC);
    done($response);
    if(empty($response['results']))
    {
      //Make this a function later because it's in price too
      $tab_name = array("Order_Placed", "Order_Accepted", "Order_Finished", "Order_Conflict");
      $stmt = "";
      foreach($tab_name as $value)
      {
        if($stmt != "")
        {
          $stmt .= " UNION ";
        }
        $stmt .= "SELECT ".$value.".address, COUNT(".$value.".address) FROM ".$value." WHERE ".$value.".address like :it AND ".$value.".placed_by = :id GROUP BY (".$value.".address)";
      }
      $stmt1 = $pdo->prepare($stmt);
      $stmt1->execute(array(
        ":it" => "%".$_POST['name']."%",
        ":id" => $person[0]["people_id"]
      ));
      $response['results'] = array_slice($stmt1->FetchAll(PDO::FETCH_ASSOC), 0, $_POST['num_results']);
    }
  } catch (\Exception $e) {
    $response['error'] = "SQL Error";
  }
  done($response);
?>
