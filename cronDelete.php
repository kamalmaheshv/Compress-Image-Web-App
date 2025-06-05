<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// cron job to delete file older than half an hour

$iterator = new FilesystemIterator("/home/u139404774/public_html/compress/uploads");
 $currentTime=  floor(microtime(true));
foreach($iterator as $entry) {
    $filepath = "/home/u139404774/public_html/compress/uploads/".$entry->getFilename();
    
$file = new SplFileInfo($filepath);
$timeDifferenceInHours= ($currentTime-$file->getMTime())/1800;
echo $timeDifferenceInHours." Hours <br>";
if($timeDifferenceInHours>1)
unlink($filepath);
}

?>