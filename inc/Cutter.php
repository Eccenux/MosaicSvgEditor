<?php
require_once './inc/model/MetaColumns.php';
require_once './inc/CutterImage.php';

/**
 * FS puzzle image cutter.
 */
class Cutter {
	/**
	 * Warning/error messages.
	 *
	 * @var array
	 */
	private $messages = array();

	/**
	 * Cut raw jpg file to columns.
	 *
	 * @param string $file Raw jpg file.
	 * @param string $baseDir Base output dir.
	 * @param MetaColumns $meta
	 * @return false upon error
	 */
	public function cutToColumns($file, $baseDir, MetaColumns $meta) {
		$allDir = "{$baseDir}";
		$columnsDir = "{$baseDir}cols/";
		// $cellsDir = "{$baseDir}cells/";

		// prepare
		$img = new CutterImage($file);
		if (!$img->isOk()) {
			$this->messages[] = "[ERROR] Unable to init cut for '$file'.";
			return false;
		}
		$this->clearImageDir($columnsDir);

		// crop image to columns
		$startY = $meta->top;
		$startX = 0;
		$colEnds = $meta->getEnds();
		foreach ($colEnds as $cutNum => $colEnd) {
			$imgW = $colEnd - $startX;

			// get height
			$bottom = $meta->getBottom($cutNum);
			if ($bottom < 1) {
				$bottom = $img->getHeight();
			}
			$imgH = $bottom - $startY;

			$output = $columnsDir . sprintf("/col_%03d.jpg", $cutNum+1);
			$img->crop($output, 100, array(
				'x'=>$startX, 'y'=>$startY,
				'width'=>$imgW, 'height'=>$imgH,
			));
			$startX = $colEnd;
		}

		// all.jpg
		$output = $allDir . "all.jpg";
		$startY = $meta->top;
		$startX = 0;
		$imgW = max($colEnds) - $startX;
		$imgH = max($meta->columnBottoms) - $startY;
		$img->crop($output, 100, array(
			'x'=>$startX, 'y'=>$startY,
			'width'=>$imgW, 'height'=>$imgH,
		));

		return true;
	}

	/**
	 * Cut columns to rows (cells).
	 *
	 * @param string $baseDir Base IO dir.
	 * @param MetaColumns $meta
	 * @return false upon error
	 */
	public function cutToRows($baseDir, MetaCut $meta) {
		$columnsDir = "{$baseDir}cols/";
		$cellsDir = "{$baseDir}cells/";

		$this->clearImageDir($cellsDir);
		$columns = $meta->getColumns();
		foreach ($columns as $index=>$column) {
			$this->cutColumn($column, $index+1, $columnsDir, $cellsDir);
		}
	}

	/**
	 * Cut column file (single column).
	 * 
	 * @param MetaColumn $column Column meta.
	 * @return false on failure.
	 */
	private function cutColumn(MetaColumn $column, $columnNo, $columnsDir, $cellsDir) {
		// prepare
		$file = $columnsDir.$column->img;
		$img = new CutterImage($file);
		if (!$img->isOk()) {
			$this->messages[] = "[ERROR] Unable to init cut for '$file'.";
			return false;
		}

		// rows
		$rowEnds = $column->rowEnds;

		// crop images to cells
		$startY = 0;
		$startX = 0;
		$imgW = $img->getWidth();
		for ($r=1; $r <= count($rowEnds); $r++) { 
			$endY = $rowEnds[$r-1];
			$imgH = $endY - $startY + 2;
			$output = $cellsDir . sprintf("/col_%03d_%03d.jpg", $columnNo, $r);
			$img->crop($output, 100, array(
				'x'=>$startX, 'y'=>$startY,
				'width'=>$imgW, 'height'=>$imgH,
			));
			// next
			$startY = $endY;
		}

		return true;
	}

	/**
	 * Clear/init dir with images.
	 * 
	 * Note! All old images are removed from the dir.
	 */
	public function clearImageDir($dirPath)
	{
		if (!file_exists($dirPath)) {
			mkdir($dirPath, 0777, true);
		}
		$files = glob($dirPath . '/*.jpg');
		foreach($files as $file) {
			if(is_file($file))
				unlink($file);
		}
	}

	/** Dump messages. */
	public function dumpMessages()
	{
		if (!empty($this->messages)) {
			echo "\n".implode("\n", $this->messages)."\n";
			$this->messages = array();
		}
	}
}