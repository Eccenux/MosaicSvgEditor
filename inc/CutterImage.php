<?php

/**
 * Simple image cutter.
 */
class CutterImage {
	/**
	 * Main image resource.
	 * @var resource
	 */
	private $img = null;
	/** main image width */
	private $w = 0;
	/** main image height */
	private $h = 0;

	/**
	 * Warning/error messages.
	 *
	 * @var array
	 */
	public $messages = array();

	/**
	 * Init.
	 *
	 * @param string $file Raw (un-cut) image path.
	 */
	public function __construct($file) {
		$this->file = $file;
		$this->init();
	}

	/**
	 * Is init OK and ready for cutting.
	 *
	 * @return boolean
	 */
	public function isOk() {
		if (is_null($this->img)) {
			return false;
		}
		return true;
	}

	/**
	 * Crop and save image.
	 *
	 * @param string $outFile Output file path.
	 * @param int $quality JPG quality.
	 * @param array $rect (x,y,width,height).
	 * @param int $scaledWidth (optional) New width.
	 * @return boolean false upon failure.
	 */
	public function crop($outFile, $quality, $rect, $scaledWidth=false) {
		if (is_null($this->img)) {
			return false;
		}
		if ($rect['height'] > $this->h - $rect['y']) {
			$rect['height'] = $this->h - $rect['y'];
		}
		$cropped = imagecrop($this->img, $rect);
		if ($cropped !== false) {
			if ($scaledWidth === false) {
				imagejpeg($cropped, $outFile, $quality);
			} else {
				$scaled = imagescale($cropped, $scaledWidth);
				imagejpeg($scaled, $outFile, $quality);
				imagedestroy($scaled);
			}
			imagedestroy($cropped);
			return true;
		}
		return false;
	}

	/**
	 * Init base data.
	 *
	 * @return false on failure.
	 */
	private function init()
	{
		// prepare input
		$file = $this->file;
		$img = imagecreatefromjpeg($file);
		if ($img === false) {
			$this->messages[] = "[ERROR] Unable to read image!";
			$this->img = null;
			return false;
		}
		$this->img = $img;

		// base props
		$this->w = imagesx($img);
		$this->h = imagesy($img);
		return true;
	}
}