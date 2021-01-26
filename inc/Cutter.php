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
			$this->messages[] = "[ERROR] Unable to int cut for '$file'.";
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
	 * Cut column file (single column).
	 * 
	 * @param int $column Column number (for stats and file names).
	 * @return false on failure.
	 */
	public function cutColumn($column) {
		if (!$this->init(true)) {
			return false;
		}
		$logger = new Logger($this->getLogPath(), sprintf("col_cut_%03d", $column));

		// we assume column is already cut to size
		$colh = $this->h;

		// find rows
		ob_start();
		echo "\n[rowEnds]";
		$rowEnds = $this->rowEnds($column, $colh);

		// log cut info
		$logger->log(ob_get_clean());
		
		// dump messages
		if (!empty($this->messages)) {
			echo "\n".implode("\n", $this->messages)."\n";
			$this->messages = array();
		}

		// crop images to cells
		$rowEnds[] = $colh;
		$startY = 0;
		$startX = 0;
		$imgW = $this->w;
		for ($r=1; $r <= count($rowEnds); $r++) { 
			$endY = $rowEnds[$r-1];
			$imgH = $endY - $startY + 2;
			$output = $this->out . sprintf("/col_%03d_%03d.jpg", $column, $r);
			$this->crop($output, 100, array(
				'x'=>$startX, 'y'=>$startY,
				'width'=>$imgW, 'height'=>$imgH,
			));
			// next
			$startY = $endY + $this->gap - 1;
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
}