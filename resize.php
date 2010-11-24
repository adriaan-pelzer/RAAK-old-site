<?php
// The file
$filename = $_GET['filename'];

// Content type
if (preg_match ("/\.jpg/", $_GET['filename'])) {
    $image = imagecreatefromjpeg($filename);
} else {
    $image = imagecreatefrompng($filename);
}

header('Content-type: image/jpeg');

// Get new dimensions
list($width, $height) = getimagesize($filename);
$new_width = $_GET['width'];
$new_height = $_GET['height'];

// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);
$white = imagecolorallocate($image_p, 255, 255, 255);
imagefill ($image_p, 0, 0, $white);

imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Output
imagejpeg($image_p, null, 100);
?>
