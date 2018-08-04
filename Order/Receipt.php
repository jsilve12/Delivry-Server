<?php
require_once("Functions.php");
start($pdo);
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
  done($response);
}
$response['success'] = "success";
done($response);
?>
