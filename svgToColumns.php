<?php
/**
 * Transform SVG to columns.edit.json with initial cut (to columns).
 */
require_once './config.php';
require_once './inc/model/MetaColumns.php';
require_once './inc/model/MetaImage.php';
require_once './inc/SvgAnalyze.php';
require_once './inc/SvgEditor.php';

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$svgPath = "{$basePath}columns.edit.svg";
$origPath = "{$basePath}columns.json";
$outputPath = "{$basePath}columns.edit.json";

// base json
$json = file_get_contents("{$basePath}columns.json");
$columnsMeta = MetaColumns::fromJson($json);

// read SVG
$svg = new SvgAnalyze($svgPath);
$editor = new SvgEditor();
$editor->processColumns($svg, $columnsMeta);
// $lines = $svg->getNodesByClass('line');
// echo "\n".count($lines['top'])."\n";
// echo "\n".count($lines['column'])."\n";

// save JSON
//echo "\n". $columnsMeta->toJson();
file_put_contents($outputPath, $columnsMeta->toJson());

// info
echo "\n{$baseUrl}{$outputPath}";