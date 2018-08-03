<?php
  require_once("Functions.php");
  start($pdo);

  try {
    $stmt = $pdo->prepare("INSERT INTO payment_nonces(nonce, people_id) VALUES(:no, :pid)");
    $stmt->execute(array(
      ":no" => $_POST['nonce'],
      ":pid" => $_POST['pid']
    ));
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
    done($response);
  }
  $response['success'] = "success";
?>
