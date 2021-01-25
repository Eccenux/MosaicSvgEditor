<?php
namespace classificators;

require_once './inc/SvgAnalyze.php';

/**
 * Node candidate for classification.
 * 
 * At least for now we assume this is a candidate to be in a column.
 * So start is xmin, end is xmax for given shape.
 */
class NodeCandidate {
	public $start = 0;
	public $end = 0;
	public $node = null;

	public static function fromLine($node) {
		$self = new self();
		$self->node = $node;
		$self->start = intval(\SvgAnalyze::getAttribute($node, 'x1', 0));
		$self->end = intval(\SvgAnalyze::getAttribute($node, 'x2', 0));
		return $self;
	}
}
