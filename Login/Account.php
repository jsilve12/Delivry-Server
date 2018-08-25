<?php
	require_once("../Order/Functions.php");

  try
  {
  $stmt = $pdo->prepare("UPDATE People SET payment = :ch WHERE email = :em");
  $stmt->execute(array(
    ":ch" => $_GET['id'],
    ":em" => $_GET['code']
  ));
  $response['success'] = "Success";
  done($response);
}
catch(\Exception $e)
{
  $response['error'] = "SQL entered improperly";
  done($response);
}
?>
