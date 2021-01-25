<?php
namespace classificators;

require_once './inc/SvgAnalyze.php';
require_once './inc/model/MetaColumns.php';
require_once './inc/classificators/NodeCandidate.php';
require_once './inc/classificators/Column.php';

/**
 * Fuzzy classificator for column ranges.
 */
class ColumnClassificator {
	private $columns = array();

	public function __construct(\MetaColumns $columnsMeta) {
		// init columns
		$this->init($columnsMeta);
	}

	/**
	 * Lines classification.
	 */
	public function lines($nodes) {
		$candidates = array();
		foreach ($nodes as $node) {
			$candidates[] = NodeCandidate::fromLine($node);
		}
		var_export($candidates);
	}

	/**
	 * Init internals.
	 */
	private function init(\MetaColumns &$columnsMeta) {
		// column boundaries
		$this->columns = array();
		$ends = $columnsMeta->getEnds();
		$start = 0;
		foreach ($ends as $index => $end) {
			$column = new Column();
			$column->start = $start;
			$column->end = $end;
			$column->num = $index + 1;
			$start = $end;
			$this->columns[] = $column;
		}
	}
}