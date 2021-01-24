<?php
/**
	Transform columns.json to SVG with initial cut (to columns).
*/
require_once './inc/model/MetaColumns.php';
require_once './inc/model/MetaImage.php';
require_once './inc/SvgAnalyze.php';
require_once './config.php';

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$svgPath = "{$basePath}columns.edit.svg";
$origPath = "{$basePath}columns.json";
$outputPath = "{$basePath}columns.edit.json";

// base json
$json = file_get_contents("{$basePath}columns.json");
$columnsMeta = MetaColumns::fromJson($json);

// read column ends
$svg = new SvgAnalyze($svgPath);
$lines = $svg->getNodesByClass('line');
// echo "\n".count($lines['top'])."\n";
// echo "\n".count($lines['column'])."\n";
if (!empty($lines['top'])) {
	$node = $lines['top'][0];
	$top = SvgAnalyze::getAttribute($node, 'y1');
	$columnsMeta->top = $top;
	echo "\n[INFO] Top found: $top";
}

echo "\n". $columnsMeta->toJson();

// info
echo "\n{$baseUrl}{$outputPath}";