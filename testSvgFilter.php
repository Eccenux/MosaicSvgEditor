<?php
/**
 * Test filtering too-close cuts.
 */
require_once './inc/SvgEditor.php';

$result = SvgEditor::removeCloseTest(array(
	10,
	219,
	507,
	721,
	937,
	937,
	1221,
	1223,
	1223,
	1515
), $minDistance = 50);
$expected = array (
  0 => 219,
  1 => 507,
  2 => 721,
  3 => 937,
  4 => 1221,
  5 => 1515,
);
echo "\nresult:";
var_export($result);
echo "\nexpected:";
var_export($expected);
