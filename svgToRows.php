<?php
/**
 * Transform SVG to rows.edit.json with final cut (to rows).
 */
require_once './config.php';
require_once './inc/model/MetaCut.php';
require_once './inc/SvgAnalyze.php';
require_once './inc/SvgEditor.php';

$basePath = BASE_PATH;
$baseUrl = BASE_URL;
$svgPath = "{$basePath}rows.edit.svg";
$origPath = "{$basePath}rows.json";
$outputPath = "{$basePath}rows.edit.json";

// base json
$json = file_get_contents("{$basePath}rows.json");
$rowsMeta = MetaCut::fromJson($json);

// read SVG
$svg = new SvgAnalyze($svgPath);
$editor = new SvgEditor();
$editor->processRows($svg, $rowsMeta);
// $lines = $svg->getNodesByClass('line');
// echo "\n".count($lines['top'])."\n";
// echo "\n".count($lines['column'])."\n";

// save JSON
//echo "\n". $rowsMeta->toJson();
file_put_contents($outputPath, $rowsMeta->toJson());

// info
echo "\n{$baseUrl}{$outputPath}";