<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>

<?php
$path = "test-a.png";
$img = imagecreatefrompng($path);
$info = getimagesize($path);
$pos = array();
$cachePos = array(0,0);

// $rgb = imagecolorat($img, 140, 1);
// echo $rgb;
// echo ($rgb >> 16) & 0xFF;
// echo ($rgb >> 8) & 0xFF;
// echo $rgb & 0xFF;
// echo "<br><br>";

for($i = 0; $info[1] > $i; $i=$i+1){
  for($ii = 0; $info[0] > $ii; $ii=$ii+1) {
    $rgb = imagecolorat($img, $ii, $i);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    if($r<220 || $g<220 || $b<220) {
      if($ii < $cachePos[0]+6 && $i < $cachePos[1]+6) {

      }else{
        array_push($pos,[$ii,$i]);
        $cachePos = array($ii,$i);
      }
    }
  }
}

// foreach( $x as $key => $val ) {
//   for($iii = 1; $iii < 2; $iii++) {
//     if( isset($x[$key+$iii]) ) {
//       unset($x[$key+$iii]);
//     }
//     if( isset($y[$val+$iii]) ) {
//       unset($x[$y[$val+$iii]]);
//     }
//   }
// }


// $array = array();
// foreach( $pos as $key => $val ) {
//   // 周辺のポイントを全て削除する
//   for($i = 0; $i < 3; $i++) {
//     for($ii = 0; $ii < 1; $ii++) {
//       if($i!==1||$ii>0) { $key = array_search([$val[0]-1+$i,$val[1]+$ii],$pos); }
//       if($key){ unset($pos[$key]); }
//       $key = false;
//     }
//   }
// }
foreach( $pos as $key => $val ) {
  echo "<div style='position:absolute;width:4px;height:4px;border-radius:10px;background:#000;top:".$val[1]."px;left:".$val[0]."px;''></div>";
}
$json = json_encode($pos);
echo $json;
?>
</body>
</html>