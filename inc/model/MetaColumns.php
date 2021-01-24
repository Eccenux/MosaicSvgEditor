<?php
// require_once './inc/ColumnMeta.php';

/**
 * Meta-data for columns cut.
 */
class MetaColumns {
	public $top = -1;
	public $gap = -1;
	/** Position of column ends (boundaries) */
	private $columnEnds = array();
	private $columnCount = 0;

	/**
	 * Set column ends/widths.
	 *
	 * @param int[] $columnEnds
	 */
	public function setEnds($columnEnds) {
		$this->columnEnds = $columnEnds;
		$this->columnCount = count($columnEnds);
	}
	public function getEnds() {
		return $this->columnEnds;
	}
	public function isEmpty() {
		return $this->columnCount < 1;
	}

	/**
	 * Dump to JSON.
	 *
	 * @return string JSON encoded data.
	 */
	public function toJson() {
		$text = json_encode(array(
			'top' => $this->top,
			'gap' => $this->gap,
			'columnCount' => $this->columnCount,
			'columnEnds' => $this->columnEnds,
		), JSON_PRETTY_PRINT);
		return $text;
	}

	/**
	 * Build from JSON.
	 *
	 * @param string $json JSON encoded data.
	 * @return MetaColumns the class.
	 */
	public static function fromJson($json) {
		$obj = new self();
		if (!empty($json)) {
			$columns = json_decode($json, true);
			$obj->mapProps($columns);
		}
		return $obj;
	}
	/**
	 * Maps data to the class properties.
	 *
	 * @param array $data Data as returned by toJson or similar.
	 */
    public function mapProps($data) {
		$allowed = array('top', 'gap');
        foreach ($data AS $key => $value) {
			if (!in_array($key, $allowed)) {
				continue;
			}
			$this->{$key} = $value;
		}

		if (!empty($data['columnEnds']) && is_array($data['columnEnds'])) {
			$this->setEnds($data['columnEnds']);
		}
	}

	/**
	 * Generate ends roughly.
	 *
	 * @param integer $imageWidth
	 * @param integer $colWidth
	 * @param integer $limit
	 * @return array
	 */
	public static function generateEnds($imageWidth, $colWidth = 300, $limit = 12) {
		$count = floor($imageWidth / $colWidth);
		if ($count > $limit) {
			$count = $limit;
		}
		// thin image
		if ($count <= 1) {
			$ends = array((int)floor($imageWidth / 2));
		} else {
			// standard image
			$final = $colWidth * $count;
			$ends = range((int)$colWidth, (int)$final, (int)$colWidth);
		}
		return $ends;
	}

}