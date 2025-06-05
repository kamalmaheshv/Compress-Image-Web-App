<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$currentTime=  floor(microtime(true)*1000);
$target_dir = "uploads/";
$uploadedImageName="q_".session_id()."_".$currentTime."_". basename($_FILES["fileToUpload"]["name"]);

$target_file = $target_dir.$uploadedImageName;
$originalImageSize=$_FILES["fileToUpload"]["size"];
$originalImageName=$_FILES["fileToUpload"]["name"];
$imageFileType = $_FILES["fileToUpload"]["type"];

$uploadOk = 1;

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
   // echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
    die();
  }
}

// Check if file already exists
/*if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
  die();
    
}
*/
// Check file size not above 50 MB
if ($_FILES["fileToUpload"]["size"] > 50000000) {
  echo "Sorry, your file is too large. Maximum 50 MB.";
  $uploadOk = 0;
  die();
    
}

// Allow certain file formats
if($imageFileType == "image/jpg" || $imageFileType == "image/png" || $imageFileType == "image/jpeg") {
    $uploadOk = 1;

}else
{
echo "Sorry, only JPG & PNG fies are allowed.";
  $uploadOk = 0;
  die();    
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded. Please try again";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "done/".$originalImageSize."/".$originalImageName."/".$uploadedImageName;
   // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file. Please try again";
  }
}
?>