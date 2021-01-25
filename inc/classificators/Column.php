<?php
namespace classificators;

require_once './inc/classificators/NodeCandidate.php';

class State {
	const OUTSIDE = 0;
	const INSIDE = 1;
	const INSIDISH = 0.5;
	const MIDDLE = -1;
	const UNKNOWN = -2;
}

/**
 * Fuzzy column.
 */
class Column {
	public $start = 0;
	public $end = 0;
	public $num = 0;

	/**
	 * Fuzzy is inside
	 *
	 * @param NodeCandidate $node
	 * @return integer See: `State`.
	 */
	public function isInside(NodeCandidate $node) {
		// fully inside
		if ($this->start <= $node->start && $this->end >= $node->end) {
			return State::INSIDE;
		}
		// fully outside
		if ($node->start >= $this->end || $node->end <= $this->start) {
			return State::OUTSIDE;
		}
		// cuts through column start (left = outside)
		if ($this->start > $node->start) {
			$left = $this->start - $node->start;
			$right = $node->end - $this->start;
			return $this->fuzzyCheck($right, $left);
		}
		// cuts through column end (right = outside)
		if ($node->end > $this->end) {
			$left = $this->end - $node->start;
			$right = $node->end - $this->end;
			return $this->fuzzyCheck($left, $right);
		}
		return State::UNKNOWN;
	}

	private function fuzzyCheck($inEdge, $outEdge) {
		$proportion = $inEdge / ($inEdge + $outEdge);
		if ($proportion > 0.48 && $proportion < 0.52) {
			return State::MIDDLE;
		}
		if ($outEdge > $inEdge) {
			return State::OUTSIDE;
		}
		if ($proportion >= 0.67) {
			return State::INSIDE;
		}
		return State::INSIDISH;
	}
}
