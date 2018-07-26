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
  ?>
</body>
</html>
