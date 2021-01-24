<?php
/**
 * SVG Analyzer.
 */
class SvgAnalyze {
	public function __construct($svgPath) {
		$this->file = $svgPath;
		$this->svg = new DOMDocument();
		$this->svg->load($svgPath);
	}

	/**
	 * Gets nodes groupped by class.
	 *
	 * @param string $nodeName Node name.
	 * @return array classes will be keys ('_' for elements without class).
	 */
	public function getNodesByClass($nodeName) {
		$rawNodes = $this->svg->getElementsByTagName($nodeName);
		$nodes = array();
		foreach ($rawNodes as $node) {
			$className = self::getAttribute($node, 'class', '_');
			$nodes[$className][] = $node;
		}
		return $nodes;
	}

	/**
	 * Helper to get attribute.
	 *
	 * @param DOMNode $node Node (element).
	 * @param string $name Attr name.
	 * @return string Value or $default if attr not found.
	 */
	public static function getAttribute($node, $name, $default = '') {
		$attr = $node->attributes->getNamedItem($name);
		if (is_null($attr)) {
			return $default;
		}
		return $attr->nodeValue;
	}
}