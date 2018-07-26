<?php
  /**
   * The purpose of this class is to automatically generate the necessary testing environment
   * for each page on the server. It does this by creating the necessary input textboxes in
   * html then submitting the data via post request, and outputting the result
   *
   * This class access the server based on the folder, and then uses functions to access the pages
   */
  class TestBench
  {
    public $folder;

    function __construct($folders)
    {
      $this->folder = $folders;
      echo("<h1>Server TestBench for ".$this->folder."</h1> </br>");
    }

    function build_test($page, array $inps)
    {
      //echos the form and div tag
      echo("<h2>Server TestBench for ".$this->folder."/".$page.".php </h2> </br>
      <form> <p>");
      foreach ($inps as $key) {
        echo($key.": <input type='text' id='".$key."_".$page."'/>
        </p> </br>");
      }
      echo("<p>submit <input type ='submit' id = '".$page."_submit'
      </form>
      <div id='".$page."'/><div> </br>");

      //Handles the Javascript (new echo for readibility)
      echo("<script>
      $(document).ready(function(event){
        console.log('Page Loaded');
        $('#".$page."_submit').click(function(event){
        console.log('Registered Submit Press');
        event.preventDefault();
        $.ajax({
          type: 'post',
          url: '../".$this->folder."/".$page.".php',
          datatype: 'json',
          data: JSON.stringify({
            ");
            //Please excuse the interruption in javascript to allow for the parameters to be entered
            foreach ($inps as $value) {
              echo($value.":document.getElementById('".$value."_".$page."').value,");
            }
            //This line is necessary to avoid an error from having a comma on the last row. The values are junk
            echo("never_use_this:'abcdef'");
            echo("
          }),
          success:function(data)
          {
            console.log('succeeded');
            $('#".$page."').text('The Result is ' + data.toString());
          }
        })
      })
    })
  </script>
  </br>");
    }
  }

?>
