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

	private function __construct() {
	}

	/** From full metadata. */
	public static function fromColumns(\MetaColumns $columnsMeta) {
		$obj = new self();
		$ends = $columnsMeta->getEnds();
		$obj->init($ends);
		return $obj;
	}
	/** From column ends directly. */
	public static function fromEnds($ends) {
		$obj = new self();
		$obj->init($ends);
		return $obj;
	}

	/**
	 * Lines classification.
	 * @return array of NodeCandidate.
	 */
	public function lines($nodes) {
		$candidates = array();
		foreach ($nodes as $node) {
			$candidates[] = NodeCandidate::fromLine($node);
		}
		// check candidates
		foreach ($this->columns as $column) {
			foreach ($candidates as &$candidate) {
				// already sure about state
				if ($candidate->state == State::INSIDE) {
					continue;
				}
				// evaluate
				$state = $column->isInside($candidate);
				switch ($state) {
					case State::INSIDISH:
					case State::INSIDE:
					case State::MIDDLE:
						$candidate->state = $state;
						$candidate->column = $column->num;
					break;
				}
			}
		}
		//var_export($candidates);
		return $candidates;
	}

	/**
	 * Init internals.
	 */
	private function init($ends) {
		// column boundaries
		$this->columns = array();
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