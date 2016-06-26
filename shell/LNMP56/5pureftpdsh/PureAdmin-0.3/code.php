<?php
header("content-type:image/PNG");
srand((double) microtime()*1000000);
$im=imagecreate(45,20);

$black=imageColorAllocate($im,0,0,0);
$while=imageColorAllocate($im,255,255,255);
$gray=imageColorAllocate($im,200,200,200);

imagefill($im,0,0,$gray);

while (($authnum=rand()%10000)<1000);
session_start();
$_SESSION['scode']=$authnum;

imagestring($im,6,5,3,$authnum,$black);

for ($i=0;$i<200;$i++){
        $randcolor=imageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
        imagesetpixel($im,rand()%70,rand()%30,$randcolor);
}
imagePNG($im);
imageDestroy($m);
?>