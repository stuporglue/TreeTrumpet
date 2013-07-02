<?php

// Send the browser resized/thumbnail images with a max dimension of $maxDimension
// works with png, jpg, gif
$maxDimension = 400;

if(!array_key_exists('img',$_GET) || !file_exists("../{$_GET['img']}")){
    header("HTTP/1.0 404 Not Found");
    exit();
}

$info = getimagesize("../{$_GET['img']}");

switch($info[2]){
case IMAGETYPE_JPEG:
    header("Content-Type: image/jpeg");
    $img = imagecreatefromjpeg("../{$_GET['img']}");
    $outfunc = 'imagejpeg';
    break;
case IMAGETYPE_GIF:
    header("Content-Type: image/gif");
    $img = imagecreatefromgif("../{$_GET['img']}");
    $outfunc = 'imagegif';
    break;
case IMAGETYPE_PNG:
    header("Content-Type: image/png");
    $img = imagecreatefrompng("../{$_GET['img']}");
    $outfunc = 'imagepng';
    break;
default:
    header("HTTP/1.0 404 Not Found");
    exit();
}

$width = imagesx( $img );
$height = imagesy( $img );

if ($width > $height) {
    $newwidth = $maxDimension;
    $newheight = floor( $height / ($width / $maxDimension));
}
else {
    $newheight = $maxDimension;
    $newwidth = floor( $width / ($height / $maxDimension) );
}

if($width < $maxDimension && $height < $maxDimension){
    $newwidth = $width;
    $newheight = $height;
}

$tmpimg = imagecreatetruecolor( $newwidth, $newheight );
imagecopyresampled( $tmpimg, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );
$outfunc($tmpimg);
