<!DOCTYPE html>
<html>
<head>
  <?php require_once("Bootstrap.php") ?>
  <title> Server Testbench for Autocomplete </title>
</head>
<body>
  <form>
    <p>Email:
      <input type = "text" name = "email" />
    </p>
    <p>Password:
      <input type = "text" name = "password" />
    </p>
    <p>Autofill Items:
      <input type = "text" name = "item" />
    </p>
    <p>Number of Results:
      <input type = "text" name = "numres" id = "numres" />
    </p>
    <p>
      <input type = "submit" name = "itemsubmit" id = "itemsubmit" />
    </p>
  </form>
  <div id = "Item Autocomplete" /></div>
  <script>
    $(document).ready(functions(event){
      $('.itemsubmit').on('submit', function(event){
        event.preventDefault();
        $.ajax({
          type: "POST",
          url: "../Autocomplete/Item.php",
          datatype: 'json',
          data: JSON.stringify({
            email:document.getElementById('email'),
            password:document.getElementById('password'),
            item:document.getElementById('item'),
            num_results: document.getElementById('numres')
          })
          success:function(data){
            $('.Item Autocomplete')
          }
        })
      })
    })
