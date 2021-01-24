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
$outputPathEdit = "{$basePath}columns.edit.svg";

$jsonPath = "{$basePath}columns.json";
$json = '';
if (file_exists($jsonPath)) {
	$json = file_get_contents($jsonPath);
}
$columnsMeta = MetaColumns::fromJson($json);
//echo $columnsMeta->toJson();

$img = new MetaImage();
$img->load($fullImgPath);
$img->cut = $columnsMeta;
//var_export($img);

// setup defaults
if ($columnsMeta->top < 0) {
	$columnsMeta->top = 100;
}
if ($columnsMeta->isEmpty()) {
	$ends = $columnsMeta->generateEnds($img->w);
	$columnsMeta->setEnds($ends);
}

// generate svg
ob_start();
include './inc/columns.svg.tpl.php';
$svg = ob_get_contents();
ob_end_clean();
echo "\n[INFO] Generated SVG for preview: $outputPath";
file_put_contents($outputPath, $svg);
if (!file_exists($outputPathEdit)) {
	echo "\n[INFO] Generated SVG for editing: $outputPathEdit";
	file_put_contents($outputPathEdit, $svg);
}

echo "\n{$baseUrl}{$outputPath}";