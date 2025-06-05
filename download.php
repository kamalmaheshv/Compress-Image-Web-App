<?php
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
$img=$_GET["img"];
$name=$_GET["name"];

 if (empty($img)||empty($name))
 {
 die("empty inputs");
 }
 else
 {

$location="uploads/".$img;

    header("Cache-Control: public"); 
    header("Content-Description: File Transfer"); 
    header("Content-Disposition: attachment; filename=$name"); 
    header("Content-Type: image/jpg"); 
    header("Content-Transfer-Encoding: binary"); 
                 header('Content-Length: ' . filesize($location));

     //ob_end_clean();

    // Read the file 
    readfile($location); 
    exit; 
}
}
?>