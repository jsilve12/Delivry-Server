<?php
	require_once("../Order/Functions.php");

  try
  {
  $stmt = $pdo->prepare("UPDATE People SET payment = :ch WHERE email = :em");
  $stmt->execute(array(
    ":ch" => $_GET['code'],
    ":em" => $_GET['state']
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
