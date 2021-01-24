<?php
require_once './inc/model/MetaColumns.php';

/**
 * Image meta and quick loader.
 */
class MetaImage {
	/** Image width */
	public $w = -1;
	/** Image height */
	public $h = -1;
	/** Image path */
	public $src = '';
	/** Basic cut data (columns) */
	public $cut = null;

	/**
	 * Load/init image data.
	 * return false Upon error.
	 */
    public function load($src) {
		$img = imagecreatefromjpeg($src);
		if ($img === false) {
			echo "Unable to read image!";
			return false;
		}
		$this->w = imagesx($img);
		$this->h = imagesy($img);
		$this->src = $src;
		return true;
	}
}