<?php
/**
 * Fuzzy column tests.
 */
namespace classificators;
require_once './inc/classificators/Column.php';

function test(Column $column, $start, $end, $expected, $info) {
	$node = new NodeCandidate();
	$node->start = $start;
	$node->end = $end;
	$result = $column->isInside($node);
	echo "\n\nactual: {$result} (expected: $expected)";
	echo "\ninfo: {$info}?";
}

$column = new Column();
$column->start = 2;
$column->end = 10;

test($column,    2,   9, Column::INSIDE, 'fully inside');
test($column,    0,   1, Column::OUTSIDE, 'fully outside - left');
test($column,   10,  11, Column::OUTSIDE, 'fully outside - right');
test($column,    0,   3, Column::OUTSIDE, 'more outside then in');
test($column,    9,  11, Column::MIDDLE, 'middle');
test($column,    7,  11, Column::INSIDE, 'more inside then out');
test($column,  8.5,  11, Column::INSIDISH, 'bit more inside then out');
