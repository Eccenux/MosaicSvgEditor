<?php

use classificators\ColumnClassificator;
use classificators\NodeCandidate;
use classificators\State;

require_once './inc/SvgAnalyze.php';
require_once './inc/classificators/ColumnClassificator.php';
require_once './inc/classificators/NodeCandidate.php';
require_once './inc/classificators/Column.php';
require_once './inc/model/MetaColumns.php';

/**
 * Svg Editor (processor).
 */
class SvgEditor {
	private $minColumnWidth = 10;

	public function __construct() {
	}

	/**
	 * Process Columns SVG editor.
	 *
	 * @param SvgAnalyze $svg SVG after edit.
	 * @param MetaColumns $columnsMeta Previous meta to modify.
	 */
	public function processColumns(SvgAnalyze $svg, MetaColumns &$columnsMeta) {
		$lines = $svg->getNodesByClass('line');
		// read top
		$this->top($lines, $columnsMeta);
		// read column ends
		$this->ends($lines, $columnsMeta);
		// read bottoms
		$this->bottoms($lines, $columnsMeta);
	}

	/**
	 * Read top.
	 */
	private function top(&$lines, MetaColumns &$columnsMeta) {
		if (empty($lines['top'])) {
			return;
		}
		$node = $lines['top'][0];
		$top = intval(SvgAnalyze::getAttribute($node, 'y1', 0));
		$columnsMeta->top = $top;
		echo "\n[INFO] Top found: $top";
	}
	/**
	 * Read column ends.
	 */
	private function ends(&$lines, MetaColumns &$columnsMeta) {
		if (empty($lines['column'])) {
			return;
		}
		$nodes = $lines['column'];
		echo "\n[INFO] Ends found, count: ". count($nodes);
		$ends = array();
		foreach ($nodes as $node) {
			$end = intval(SvgAnalyze::getAttribute($node, 'x1', 0));
			if ($end >= $this->minColumnWidth) {
				$ends[] = $end;
			}
		}
		sort($ends, SORT_REGULAR);
		$columnsMeta->setEnds($ends);
	}
	/**
	 * Read bottoms.
	 */
	private function bottoms(&$lines, MetaColumns &$columnsMeta) {
		if (empty($lines['column-bottom'])) {
			return;
		}
		$nodes = $lines['column-bottom'];
		echo "\n[INFO] Bottoms found, count: ". count($nodes);
		$ai = new ColumnClassificator($columnsMeta);
		$candidates = $ai->lines($nodes);
		$bottoms = array();
		foreach ($candidates as $candidate) {
			switch ($candidate->state) {
				case State::INSIDE:
					$bottoms[] = $candidate;
				break;
				case State::INSIDISH:
					echo "\n[WARNING] Suspicious bottom (close to a middle of a column {$candidate->column}): ";
					echo SvgAnalyze::getXml($candidate->node);
					$bottoms[] = $candidate;
				break;
				case State::MIDDLE:
					echo "\n[ERROR] Invalid bottom (between columns {$candidate->column}): ";
					echo SvgAnalyze::getXml($candidate->node);
				break;
				default:
					echo "\n[WARNING] Unrecognized (skipped) bottom: ";
					echo SvgAnalyze::getXml($candidate->node);
				break;
			}
		}
		// // sort
		// usort($bottoms, function(NodeCandidate $a, NodeCandidate $b) {
		// 	return $a->column - $b->column;
		// });

		// generate bottoms
		$columns = $columnsMeta->getCount();
		$final = array_fill(0, $columns, 0);
		foreach ($bottoms as $bottom) {
			if ($bottom instanceof NodeCandidate) {
				$final[$bottom->column-1] = (int)round(SvgAnalyze::getAttribute($bottom->node, 'y1', 0));
			}
		}

		$columnsMeta->columnBottoms = $final;
	}
}