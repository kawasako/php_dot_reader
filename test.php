<?php
$im = imagecreatefrompng("test.png");
$rgb = imagecolorat($im, 266, 3);
$r = ($rgb >> 16) & 0xFF;
$g = ($rgb >> 8) & 0xFF;
$b = $rgb & 0xFF;

var_dump($r, $g, $b);
?>