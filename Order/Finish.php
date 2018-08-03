<?php
  //TODO: Add a portal for the receipt to be submitted
  require_once("Functions.php");
  start($pdo);

  //Deletes the entry from Accepted
  $result = array();
  try {
    $stmt = $pdo->prepare("SELECT * FROM Order_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
    if(empty($result))
    {
      $response['error'] = "Order not found";
      done($response);
    }
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
  }
  $id;

  //Adds the entry into the new table
  try {
    $stmt = $pdo->prepare("INSERT INTO Order_Finished(placed_by, accepted_by, receipt, price, address, addr_description, longitude, latitude, store) VALUES(:pb,:ab,:re,:pr,:ad,:ad_de,:lo,:la,:st)");
    $stmt->execute(array(
      ":pb" => $result[0]["placed_by"],
      ":ab" => $result[0]["accepted_by"],
      ":re" => $_FILES['image']['name'],
      ":pr" => strtolower(trim($_POST['price'])),
      ":ad" => $result[0]["address"],
      ":ad_de" => $result[0]["addr_description"],
      ":lo" => $result[0]["longitude"],
      ":la" => $result[0]["latitude"],
      ":st" => $result[0]["store"]
    ));
    $id = $pdo->LastInsertId();
  } catch (\Exception $e) {
    $response['error'] = "SQL error ";
    done($response);
  }

  //Finds the items in the database
  try{
    $stmt = $pdo->prepare("SELECT * FROM Items_Accepted WHERE order_id = ".$_POST['order_id']);
    $stmt->execute();
    $result = $stmt->FetchAll(PDO::FETCH_ASSOC);
    if(empty($result))
    {
      $response['error'] = "SQL error";
      done($response);
    }
  }
  catch(\Exception $e)
  {
    $response['error'] = "SQL error";
    done($response);
  }
    foreach($result as $value)
    {
      try {
        //Enters each item back into the database
        $stmt = $pdo->prepare("INSERT INTO Items_Finished(order_id, description, item_id, price) VALUES(:oi, :de, :ii, :p)");
        $stmt->execute(array(
          ":oi" => $id,
          ":de" => $value['description'],
          ":ii" => $value['item_id'],
          ":p" => $value['price']
        ));
      } catch (\Exception $e) {
        $response['error'] = "SQL error";
        done($response);
      }
    }
    //Deletes everything
    $stmt = $pdo->prepare("DELETE FROM Order_Accepted WHERE order_id= ".$_POST['order_id']);
    $stmt->execute();

    //Handles inputing the image
    if(!isset($_FILES['image']))
    {
      $response['error'] = "Image Missing";
      done($response);
    }
    $target = "../Receipts/".basename($_FILES['image']['name']);

    //Checks that the extension is appropriate
    $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
    if($ext != "jpg" && $ext != "png" && $ext != "jpeg" && $ext != "gif")
    {
      $response['error'] = "Invalid file extension";
      done($response);
    }

    //Makes sure the name is distinct
    if(file_exists($target))
    {
      $response['error'] = "File name in use";
      done($response);
    }

    //Moves the file to the Appropriate folder
    try {
      move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } catch (\Exception $e) {
      $response['error'] = "Error Uploading the file"
    }

  $response['success'] = "success";
  done($response);
?>
