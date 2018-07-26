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
  ?>
</body>
</html>
