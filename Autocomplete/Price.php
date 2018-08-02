<?php
require_once("../Order/Functions.php");
start($pdo);

//Make this a function because its in location too
$tab_name = array("Item_Placed", "Item_Accepted", "Item_Finished", "Item_Conflict");
$stmt
foreach($tab_name as $value)
{
  if($stmt != "")
  {
    $stmt .= "(UNION"
  }
  $stmt .= "SELECT a.price, COUNT(a.price) FROM ".$value." AS a JOIN Items ON Items.item_id = a.item_id WHERE a.price like %:it%, Items.name = :na"
}
$stmt .= ") ORDER BY COUNT(address)";
$stmt1 = $pdo->prepare($stmt);
$stmt1->execute(array(
  ":it" => $_POST['price'],
  ":na" => $_POST['name']
));
$response['results'] = array_slice($stmt1->FetchAll(PDO::FETCH_ASSOC), 0, $_POST['num_results'])
?>
