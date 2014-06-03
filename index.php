<?php
$path = "test3.png";
$img = imagecreatefrompng($path);
$info = getimagesize($path);
$nodes = "div.a>div.b>div.c+div.d";
$nodes = array(
  "0" => array(
    "parent" => "",
    "childs" => array("1"),
    "node" => "div",
    "class" => "a",
    "style" => array(),
    "pos" => array(),
    "offset" => array()
  ),
  "1" => array(
    "parent" => "0",
    "childs" => array("2","3"),
    "node" => "div",
    "class" => "b",
    "style" => array(),
    "pos" => array(),
    "offset" => array()
  ),
  "2" => array(
    "parent" => "1",
    "childs" => array(),
    "node" => "div",
    "class" => "c",
    "style" => array(),
    "pos" => array(),
    "offset" => array()
  ),
  "3" => array(
    "parent" => "1",
    "childs" => array(),
    "node" => "div",
    "class" => "d",
    "style" => array(),
    "pos" => array(),
    "offset" => array()
  ),
);
$colors = array();
$array = array();
// echo $info[0];
// echo $info[1];

for($i = 0; $info[0] > $i; $i++) {
  $rgb = imagecolorat($img, $i, 0);
  $r = ($rgb >> 16) & 0xFF;
  $g = ($rgb >> 8) & 0xFF;
  $b = $rgb & 0xFF;
  if($r<255||$g<255||$b<255) {
    array_push($colors,$r.$g.$b);
  }
}

echo "<pre>";
var_dump($colors);
echo "</pre>";

echo "-------------------------";


for($i = 1; $info[1] > $i; $i++){
  for($ii = 0; $info[0] > $ii; $ii++) {
    $rgb = imagecolorat($img, $ii, $i);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    if($r<255||$g<255||$b<255) {
      if(isset($array[$r.$g.$b])) {
        array_push($array[$r.$g.$b],[$ii+1,$i+1]);
      }else{
        $array[$r.$g.$b] = array([$ii,$i]);
      }
    }
  }
}

echo "<pre>";
var_dump($array);
echo "</pre>";

// set pos
foreach($nodes as $key => $val) {
  $colorKey = $colors[$key];
  $nodes[$key]["pos"] = $array[$colorKey];
}

// pos parse
foreach($nodes as $key => $val) {

  // 親要素からの比較
  if(isset($nodes[$nodes[$key]["parent"]])){
    $parentPos = $nodes[$nodes[$key]["parent"]]["pos"];
    $x = $nodes[$key]["pos"][0][0] - $nodes[$nodes[$key]["parent"]]["pos"][0][0];
    $y = $nodes[$key]["pos"][0][1] - $nodes[$nodes[$key]["parent"]]["pos"][0][1];
    $nodes[$key]["offset"] = array($x, $y);
  }else{
    $nodes[$key]["style"]["width"] = $nodes[$key]["pos"][1][0] - $nodes[$key]["pos"][0][0];
    $nodes[$key]["offset"] = NULL;
  }

  // 兄弟要素との比較
  if(count($nodes[$key]["childs"])>1){
    foreach($nodes[$key]["childs"] as $k => $v) {
      if(isset($nodes[$key]["childs"][$k+1])) {
        $x = $nodes[$nodes[$key]["childs"][$k+1]]["pos"][0][0] - $nodes[$nodes[$key]["childs"][$k]]["pos"][1][0];
        $y = $nodes[$nodes[$key]["childs"][$k+1]]["pos"][0][1] - $nodes[$nodes[$key]["childs"][$k]]["pos"][1][1];
        $nodes[$nodes[$key]["childs"][$k+1]]["relativeOffset"] = array($x, $y);
      }
    }
  }
}

// style parse
foreach($nodes as $key => $val) {
  if($nodes[$key]["offset"]){

  }else{
    $nodes[$key]["style"] = "a";
  }
}

echo "-------------------------";

echo "<pre>";
var_dump($nodes);
echo "</pre>";

// Node Root
// echo "<".$nodes[0]["node"]." class='".$nodes[0]["class"]."'>";
// $endTags = "</".$nodes[0]["node"].">".$endTags;
renderHTML(0);

function renderHTML($nodeKey){
  global $nodes;
  echo "<".$nodes[$nodeKey]["node"]." class='".$nodes[$nodeKey]["class"]."'>";
  if(count($nodes[$nodeKey]["childs"])>0) {
    foreach($nodes[$nodeKey]["childs"] as $key => $val) {
      renderHTML($val);
    }
  }else{
    $x = $nodes[$nodeKey]["pos"][1][0] - $nodes[$nodeKey]["pos"][0][0];
    $y = $nodes[$nodeKey]["pos"][1][1] - $nodes[$nodeKey]["pos"][0][1];
    echo "<img src='http://placehold.it/".$x."x".$y."' alt=''>";
  }
  echo "</".$nodes[$nodeKey]["node"].">";
}

function renderStyleSheet($nodeKey){
  global $nodes;
  echo ".".$nodes[$nodeKey]["class"]."{";
  if(count($nodes[$nodeKey]["childs"])>0) {
    foreach($nodes[$nodeKey]["childs"] as $key => $val) {
      renderHTML($val);
    }
  }else{
    $x = $nodes[$nodeKey]["pos"][1][0] - $nodes[$nodeKey]["pos"][0][0];
    $y = $nodes[$nodeKey]["pos"][1][1] - $nodes[$nodeKey]["pos"][0][1];
    echo "<img src='http://placehold.it/".$x."x".$y."' alt=''>";
  }
  echo "}";
}

?>