<!DOCTYPE html>
<html>
<head>
  <?php require_once("Bootstrap.php") ?>
  <title> Server Testbench for Login </title>
</head>
<body>
  <h1>TestBench for Login/Create.php</h1>
  <form>
    <p>Name:
      <input type="text" id="name" />
    </p>
    </br>
    <p>Email:
      <input type="text" id="email" />
    </p>
    </br>
    <p>Salt:
      <input type="text" id="salt" />
    </p>
    </br>
    <p>Password:
      <input type="password" id="password" />
    </p>
    </br>
    <p>submit
      <input type="submit" id="Create_Submit" />
    </p>
    </br>
  </form>
  <div id = "Login_Create" /></div>
  </br>
  <script>
    $(document).ready(function(event){
      $('#Create_Submit').on('submit',function(event){
        event.preventDefault();
        $.ajax({
          type:"POST",
          url: "../Login/Create.php",
          dataype: 'json',
          data: JSON.stringify({
            email:document.getElementbyId('email'),
            name:document.getElementById('name'),
            salt:document.getElementById('salt'),
            password:document.getElementById('password')
          })
          success:function(data)
          {
            $('#Login_Create').text() = "The Result is " + data.toString();
          }
        })
      })
    })
  </script>
  </br>
  <h1> Server Testbench for Login.php </h1>
  </br>
  <form>
    <p>Email:
      <input type ="text" id="email"/>
      </br>
    </p>
    <p>Password:
      <input type = "password" id="Password"/>
      </br>
    </p>
    <p>Submit
      <input type = "submit" id ="Login_Submit" />
      </br>
    </p>
  </form>
  <div id = 'Login_Login' /div>
  <script>
    $(document).ready(function(event){
      event.preventDefault();
      $.ajax({
        type: "POST",
        url: "../Login/Login.php",
        data: JSON.stringify({
          email:document.getElementById('email'),
          password:document.getElementById('password'),
        })
        success:function(data){
          $('#Login_Login').text() = "The Result is " + data.toString();
        }
      })
    })
  </script>
</br>
<h1>Server Testbench for Address.php</h1>
<form>
  <p>Email:
    <input type = "text" id ="email" />
    </br>
  </p>
  <p>Password:
    <input type = "password" id="password" />
    </br>
  </p>
  <p>Address:
    <input type = "text" id="address"/>
    </br>
  </p>
  <p>submit
    <input type = "Submit" id="Address_Submit" />
    </br>
  </p>
