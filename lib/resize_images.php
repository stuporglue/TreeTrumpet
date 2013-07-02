<?php


function printResize($path){
    $info = getimagesize($path);

    switch($info[2]){
    case IMAGETYPE_JPEG:
        header("Content-Type: image/jpeg");
        $img = imagecreatefromjpeg($path);
        $outfunc = 'imagejpeg';
        break;
    case IMAGETYPE_GIF:
        header("Content-Type: image/gif");
        $img = imagecreatefromgif($path);
        $outfunc = 'imagegif';
        break;
    case IMAGETYPE_PNG:
        header("Content-Type: image/png");
        $img = imagecreatefrompng($path);
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
}
