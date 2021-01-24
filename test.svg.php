<?php
/**
 * Quick test/PoC.
 */

	$img = (object) array(
		'w' => 3888,
		'h' => 2547,
		'cut' => (object) array(
			'top' => 100,
			'gap' => 10,
			'columnEnds' => array(
				299,
				599,
				899,
				1199,
				1503,
				1772,
				2083,
				2373,
				2627,
				2945,
				3223,
				3535
			),
		),
		'src' => 'img-auto-cut/copy.jpg',
	);
	//var_export($img);
?>
<?='<?xml version="1.0" encoding="UTF-8" standalone="no"?>'?>
<svg
	viewBox="0 0 <?=$img->w?> <?=$img->h?>"
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
>
<title>Cut render test</title>
<style>
	line {
		fill: none;
		stroke: red;
		stroke-width: 4px;
		stroke-dasharray: 40 30;
	}
	line.top {
		stroke: darkorange;
	}
</style>
  
	<image
		xlink:href='img-auto-cut/copy.jpg'
		width="3888"
		height="2547"
	/>
	<line class="top" x1="0" y1="<?=$img->cut->top?>" x2="<?=$img->w?>" y2="<?=$img->cut->top?>" />
	<?php foreach($img->cut->columnEnds as $endX) { ?>
		<line class="column" x1="<?=$endX?>" y1="<?=$img->cut->top?>" x2="<?=$endX?>" y2="<?=$img->h?>" />
	<?php } ?>
</svg>