<?php
require_once './inc/SvgAnalyze.php';
require_once './inc/classificators/ColumnClassificator.php';
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
		$ai = new classificators\ColumnClassificator($columnsMeta);
		$ai->lines($nodes);
	}
}