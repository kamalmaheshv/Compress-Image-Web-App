<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field 
  
  $imageUploadedName = $_POST['imageUploadedName'];
  $toBeCompressedFileSizeBytes = $_POST['toBeCompressedFileSizeBytes'];
  
  if (empty($imageUploadedName)||empty($toBeCompressedFileSizeBytes)) {
    echo "Empty input";die();
  } else {
      //compress process here


 $currentTime=  floor(microtime(true));


   $imageUploadedPath="uploads/".$imageUploadedName;

if(!file_exists($imageUploadedPath))
die("failed1/Please upload an image and try again.");


$myArray = explode (".", $imageUploadedName); //removing extension and appending .jpg to file name
$newImageName="q".$currentTime.$myArray[0].".jpg";

   $imageCompressedPath="uploads/".$newImageName;
   
// delete if already exist and rename 
if(file_exists($imageCompressedPath))
{
unlink($imageCompressedPath); 
}
   
$success=false;
$incomplete=true;
$compress=100;

$imageType=mime_content_type($imageUploadedPath);


if($imageType=="image/jpeg"||$imageType=="image/jpg"){
    correctImageOrientation($imageUploadedPath);

  $sourceImage=  imagecreatefromjpeg($imageUploadedPath);
}
else if($imageType=="image/png")
  $sourceImage=  imagecreatefrompng($imageUploadedPath);


     //createImage with compress=100
     imagejpeg($sourceImage, $imageCompressedPath, $compress);
     

    while($incomplete){
        $tempLoopLength=filesize($imageCompressedPath);

        if(($tempLoopLength+1)>$toBeCompressedFileSizeBytes)
        
        {
            $success=false;
        unlink($imageCompressedPath);

        
        if($compress>0){

                            if($compress>70)
                                $compress = $compress - 1;
                            else if($compress>45)
                                $compress=$compress-2;
                            else
                                $compress=$compress-3;

            
                                    //createImage
                                         imagejpeg($sourceImage, $imageCompressedPath, $compress);


                    }else
                    {
                        $compress=1;
                        //createImage
                             imagejpeg($sourceImage, $imageCompressedPath, $compress);

                        break;
                    }
        
        
    }
    else
    {
         $success=true;
         $incomplete=false; 
    }
    
    }
    
    
    //print result here
    if($success){
        echo "success/Image compressed to ".number_format((filesize($imageCompressedPath)/1024),2)." KB or ".number_format((filesize($imageCompressedPath)/(1024*1024)),3)." MB"."/".$newImageName;
    }else
    {       
        echo "failed2/Failed to compress below ".number_format(($toBeCompressedFileSizeBytes/1024),2)." KB or ".number_format(($toBeCompressedFileSizeBytes/(1024*1024)),3)." MB"."/".$newImageName;

        //echo "Failed to compress";
    }
    
  }
}


///function

function correctImageOrientation($filename) {
  if (function_exists('exif_read_data')) {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      if($orientation != 1){
   
  $img=  imagecreatefromjpeg($filename);


         
        
        $deg = 0;
        switch ($orientation) {
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if ($deg) {
          $img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename 
        imagejpeg($img, $filename, 100);


        
        
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
}

?>