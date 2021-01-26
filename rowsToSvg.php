<?php
/**
	Transform rows.json to SVG with final cut (to cells).
*/
require_once './inc/model/MetaCut.php';
require_once './inc/model/MetaImage.php';
require_once './config.php';

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$columnsSvgPath = "cols/";	// base path for SVG
$columnsBasePath = "{$basePath}cols/";
$outputPath = "{$basePath}rows.svg";
$outputPathEdit = "{$basePath}rows.edit.svg";

$jsonPath = "{$basePath}rows.json";
$json = '';
if (file_exists($jsonPath)) {
	$json = file_get_contents($jsonPath);
}
$rowsMeta = MetaCut::fromJson($json);

// data for images width/height
$imgs = array();
$totalWidth = 0;
$maxHeight = 0;
foreach($rowsMeta->getColumns() as $column) {
	$img = new MetaImage();
	$img->load($columnsBasePath.$column->img);
	$imgs[] = $img;
	if ($maxHeight < $img->h) {
		$maxHeight = $img->h;
	}
	$totalWidth += $img->w;
}

// generate svg
ob_start();
include './inc/rows.svg.tpl.php';
$svg = ob_get_contents();
ob_end_clean();
echo "\n[INFO] Generated SVG for preview: $outputPath";
file_put_contents($outputPath, $svg);
if (!file_exists($outputPathEdit)) {
	echo "\n[INFO] Generated SVG for editing: $outputPathEdit";
	file_put_contents($outputPathEdit, $svg);
}

echo "\n{$baseUrl}{$outputPath}";