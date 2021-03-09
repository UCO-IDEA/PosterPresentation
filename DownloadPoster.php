<?php
header('Content-Type: text/html; charset=utf-8');
$template = file_get_contents("baseTemplate.html");

//include base poster css
$baseCSS = file_get_contents("BasePosterStyle.css");

//$baseCSS = str_replace(" ", "", $baseCSS);

$annotations = $_POST['annotations'];
$annotationStyle = $_POST['annotationStyle'];
$annotationDOM = $_POST['annotationDOM'];
$posterURL = $_POST['posterURL']; 

$template = str_replace("<!--basePosterStyle-->", $baseCSS, $template);

$template = str_replace("<!--annotationStyle-->", $annotationStyle, $template);

$template = str_replace("<!--annotationJSON-->", json_encode($annotations), $template);

$template = str_replace("<!--imgSource-->", $posterURL, $template);
$template = str_replace("<!--annotationDOMs-->", $annotationDOM, $template);

echo $template;
?>