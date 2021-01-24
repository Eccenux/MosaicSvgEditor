<?php
/**
	Transform columns.json to SVG with initial cut (to columns).
*/
require_once './inc/model/MetaColumns.php';
require_once './inc/model/MetaImage.php';
require_once './config.php';

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$fullImgPath = "{$basePath}copy.jpg";
$outputPath = "{$basePath}columns.svg";

$json = file_get_contents("{$basePath}columns.json");
$columnsMeta = MetaColumns::fromJson($json);
//echo $columnsMeta->toJson();

$img = new MetaImage();
$img->load($fullImgPath);
$img->cut = $columnsMeta;
//var_export($img);

ob_start();
include './inc/columns.svg.tpl.php';
$svg = ob_get_contents();
ob_end_clean();
file_put_contents($outputPath, $svg);

echo "{$baseUrl}{$outputPath}";