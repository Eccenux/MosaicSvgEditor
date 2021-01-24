<?php
/**
	Transform columns.json to SVG.
*/
require_once './inc/model/MetaColumns.php';

$basePath = './img-auto-cut/';

$json = file_get_contents("{$basePath}columns.json");
$columnsMeta = MetaColumns::fromJson($json);
echo $columnsMeta->toJson();
