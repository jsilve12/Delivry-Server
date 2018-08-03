<?php
  require_once("TestBench.php");
?>
<html>
<head>
  <?php require_once("bootstrap.php"); ?>
</head>
<body>
  <?php
    $Login_Tb = new TestBench('Login');
    $Login_Tb->build_test('Create', array('name', 'email', 'salt', 'password'));
    $Login_Tb->build_test('Login', array('email', 'password'));
    $Login_Tb->build_test('Address', array('email', 'password', 'address', 'longitude', 'latitude'));
    $Login_Tb->build_test('Reset_Ask', array('email'));
    $Login_Tb->build_test('Reset_Grant', array('email','temp_pass','password'));
    echo("</br>");

    $Order_Tb = new TestBench('Order');
    $Order_Tb->build_test('Place', array('email', 'password', 'items', 'address', 'addr_desc', 'long', 'lat', 'store'));
    $Order_Tb->build_test('Standing', array('email', 'password', 'long','lat', 'diff', 'store'));
    $Order_Tb->build_test('My_Orders', array('email', 'password', 'people_id'));
    $Order_Tb->build_test('Accept', array('email', 'password', 'order_id'));
    $Order_Tb->build_test('Amend', array('email', 'password', 'order_id', 'items', 'address', 'addr_description', 'long', 'lat', 'store' ));
    $Order_Tb->build_test('Cancel_Accept', array('email', 'password', 'order_id'));
    $Order_Tb->build_test('Cancel_Order', array('email', 'password', 'order_id'));
    $Order_Tb->build_test('Finish', array('email', 'password', 'order_id', 'price', 'receipt_name', 'receipt'));

    echo("</br>");
    $Auto_Tb = new TestBench("Autocomplete");
    $Auto_Tb->build_test('Item', array('email', 'password', 'item', 'num_results'));
    $Auto_Tb->build_test('Store', array('email', 'password', 'name', 'num_results'));
    $Auto_Tb->build_test('Location', array('email', 'password', 'name', 'num_results'));
    $Auto_Tb->build_test('Price', array('email', 'password', 'item', 'store'));
  ?>
</body>
</html>
