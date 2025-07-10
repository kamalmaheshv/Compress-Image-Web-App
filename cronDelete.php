<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// cron job to delete file older than half an hour

$iterator = new FilesystemIterator("/path_to_uploads_folder/");
 $currentTime=  floor(microtime(true));
foreach($iterator as $entry) {
    $filepath = "/path_to_uploads_folder/".$entry->getFilename();
    
$file = new SplFileInfo($filepath);
$timeDifferenceInHours= ($currentTime-$file->getMTime())/1800;
echo $timeDifferenceInHours." Hours <br>";
if($timeDifferenceInHours>1)
unlink($filepath);
}

?>
