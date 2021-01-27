<?php
/**
 * Cut to rows based on the cut JSON.
 * 
 * Requires PHP 5.5 or higher (due to using e.g. `imagecrop`).
 */
require_once './config.php';
require_once './inc/model/MetaCut.php';
require_once "./inc/Cutter.php";

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$fullImgPath = "{$basePath}copy.jpg";

// cut-json
$jsonPath = "{$basePath}rows.edit.json";
if (!file_exists($jsonPath)) {
	die(
		'[ERROR] Create rows.json first!'
		.'\nUse e.g. rowsToSvg and svgToRows.'
	);
}
$json = file_get_contents($jsonPath);
$rowsMeta = MetaCut::fromJson($json);

//
// cut to rows (when uneven is true)
//
echo "Cutting: $fullImgPath\n";
$cutter = new Cutter();

$cutter->cutToRows($basePath, $rowsMeta);
$cutter->dumpMessages();

echo "\nDone";
