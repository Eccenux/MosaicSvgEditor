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
$minColumnWidth = 10;
$svgPath = "{$basePath}columns.edit.svg";
$origPath = "{$basePath}columns.json";
$outputPath = "{$basePath}columns.edit.json";

// base json
$json = file_get_contents("{$basePath}columns.json");
$columnsMeta = MetaColumns::fromJson($json);

// read SVG
$svg = new SvgAnalyze($svgPath);
$lines = $svg->getNodesByClass('line');
// echo "\n".count($lines['top'])."\n";
// echo "\n".count($lines['column'])."\n";

// read top
if (!empty($lines['top'])) {
	$node = $lines['top'][0];
	$top = intval(SvgAnalyze::getAttribute($node, 'y1', 0));
	$columnsMeta->top = $top;
	echo "\n[INFO] Top found: $top";
}

// read column ends
if (!empty($lines['column'])) {
	echo "\n[INFO] Ends found, count: ". count($lines['column']);
	$ends = array();
	foreach ($lines['column'] as $node) {
		$end = intval(SvgAnalyze::getAttribute($node, 'x1', 0));
		if ($end >= $minColumnWidth) {
			$ends[] = $end;
		}
	}
	$columnsMeta->setEnds($ends);
}

//echo "\n". $columnsMeta->toJson();
file_put_contents($outputPath, $columnsMeta->toJson());

// info
echo "\n{$baseUrl}{$outputPath}";