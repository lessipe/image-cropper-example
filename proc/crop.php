<?php
$bound = $_POST['bound'];
$image = $_POST['image'];
$select = $_POST['select'];

$imagePath = __DIR__ . '/../' . $image;
$imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
$size = getimagesize($imagePath);

$visibleWidth = $bound[0];
$visibleHeight = $bound[1];

$originWidth = $size[0];
$originHeight = $size[1];

$widthRatio = $originWidth / $visibleWidth;
$heightRatio = $originHeight / $visibleHeight;

$x = $select['x'] * $widthRatio;
$y = $select['y'] * $heightRatio;

$w = $select['w'] * $widthRatio;
$h = $select['h'] * $heightRatio;

$im = imagecreatefrompng($imagePath);
$im2 = imagecrop($im, [
    'x' => $x,
    'y' => $y,
    'width' => $w,
    'height' => $h,
]);

$filename = date('YmdHisu') . '.' . $imageExtension;
$targetPath = __DIR__ . '/../files/' . $filename;
imagepng($im2, $targetPath);

?>
{
"result": "success",
"filename": "/files/<?php echo $filename ?>"
}
