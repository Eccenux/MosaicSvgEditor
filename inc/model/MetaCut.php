<?php
require_once './inc/model/MetaColumn.php';

/**
 * Meta-data for cuting all columns to rows (cells).
 */
class MetaCut {
	/** List of single column cuts. */
	private $columns = array();

	/**
	 * Set column ends/widths.
	 *
	 * @param int[] $columnEnds
	 */
	public function append($img, $rowEnds) {
		$column = new MetaColumn($img, $rowEnds);
		$this->columns[] = $column;
	}

	/**
	 * Dump to JSON.
	 *
	 * @return string JSON encoded data.
	 */
	public function toJson() {
		$text = json_encode(array(
			'columns' => $this->columns,
		), JSON_PRETTY_PRINT);
		return $text;
	}

	/**
	 * Build from JSON.
	 *
	 * @param string $json JSON encoded data.
	 * @return MetaCut the class.
	 */
	public static function fromJson($json) {
		$obj = new self();
		if (!empty($json)) {
			$data = json_decode($json, true);
			foreach ($data['columns'] as $column) {
				$obj->append($column['img'], $column['rowEnds']);
			}
		}
		return $obj;
	}

	public function getColumns()
	{
		return $this->columns;
	}
	public function getCount()
	{
		return count($this->columns);
	}
	public function clear()
	{
		$this->columns = array();
	}
}