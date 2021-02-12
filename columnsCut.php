<?php
/**
 * Cut to columns based on the cut JSON.
 * 
 * Requires PHP 5.5 or higher (due to using e.g. `imagecrop`).
 */
require_once './config.php';
require_once './inc/model/MetaColumns.php';
require_once "./inc/Cutter.php";

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$fullImgPath = "{$basePath}copy.jpg";

// cut-json
$jsonPath = "{$basePath}columns.edit.json";
if (!file_exists($jsonPath)) {
	die(
		'[ERROR] Create columns.json first!'
		.'\nUse e.g. columnsToSvg and svgToColumns.'
	);
}
$json = file_get_contents($jsonPath);
$columnsMeta = MetaColumns::fromJson($json);

//
// cut to columns (when uneven is true)
//
echo "Cutting: $fullImgPath\n";
$cutter = new Cutter();

$cutter->cutToColumns($fullImgPath, $basePath, $columnsMeta);
$cutter->dumpMessages();

