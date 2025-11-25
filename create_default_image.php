<?php
// Create a simple default product image
$width = 400;
$height = 400;

// Create image
$image = imagecreatetruecolor($width, $height);

// Define colors
$bg_color = imagecolorallocate($image, 245, 245, 245);
$border_color = imagecolorallocate($image, 200, 200, 200);
$text_color = imagecolorallocate($image, 100, 100, 100);
$icon_color = imagecolorallocate($image, 150, 150, 150);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Draw border
imagerectangle($image, 10, 10, $width - 10, $height - 10, $border_color);

// Draw a simple package/box icon
$box_size = 150;
$box_x = ($width - $box_size) / 2;
$box_y = ($height - $box_size) / 2 - 30;

// Box body
imagefilledrectangle($image, $box_x, $box_y, $box_x + $box_size, $box_y + $box_size, $icon_color);
imagerectangle($image, $box_x, $box_y, $box_x + $box_size, $box_y + $box_size, $border_color);

// Box top flap
$flap_points = array(
    $box_x, $box_y,
    $box_x + $box_size / 2, $box_y - 30,
    $box_x + $box_size, $box_y
);
imagefilledpolygon($image, $flap_points, 3, imagecolorallocate($image, 180, 180, 180));
imagepolygon($image, $flap_points, 3, $border_color);

// Add text
$font_size = 5;
$text = "No Image Available";
$text_width = imagefontwidth($font_size) * strlen($text);
$text_x = ($width - $text_width) / 2;
$text_y = $box_y + $box_size + 40;
imagestring($image, $font_size, $text_x, $text_y, $text, $text_color);

// Output image
header('Content-Type: image/jpeg');
imagejpeg($image, 'default-product.jpg', 90);
imagedestroy($image);

echo "Default image created successfully!";
?>
