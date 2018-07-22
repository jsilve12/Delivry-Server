<?php
  require_once("../Order/Functions.php");
  start();

  try {
    //Gets results based on the most common Items
    $stmt = $pdo->prepare("SELECT name FROM Items WHERE name like :it ORDER BY num_called DESC");
    $stmt->execute(array(
      ":it" => strtolower(trim($_POST['item']))
    ));
    $response['result'] = array_slice($stmt->FetchAll(PDO::FETCH_ASSOC), 0, $_POST['num_results']);
    done();
  } catch (\Exception $e) {
    $response['error'] = "Error retrieving information from the database";
    done();
  }
?>
