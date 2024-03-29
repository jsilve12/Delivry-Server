<?php
  require_once("../Order/Functions.php");
  start($pdo);

  try {
    $stmt = $pdo->prepare("SELECT name FROM Stores WHERE name like :na ORDER BY num_called DESC");
    $stmt->execute(array(
      ":na" => "%".$_POST['name']."%"
    ));
    $response['success'] = array_slice($stmt->FetchAll(PDO::FETCH_ASSOC), 0, $_POST['num_results']);
    done($response);
  } catch (\Exception $e) {
    $response['error'] = "SQL error";
    done($response);
  }

?>
