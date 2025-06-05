<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field 
  
  $finalCompressedImageName = $_POST['finalCompressedImageName'];
  
  if (empty($finalCompressedImageName)){
   die("Empty input");   
  }else{
      
  $location="uploads/".$finalCompressedImageName;
    
      if(file_exists($location))
      {
       echo "true";   
      }
      else
      {
          echo "false";
      }
      
      
  }
  
}  
  
  
  
?>