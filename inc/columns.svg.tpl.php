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
		stroke-dasharray: 60 30 30 30;
	}
</style>
  
	<image
		xlink:href='copy.jpg'
	/>
	<line
		class="top"
		x1="0"
		y1="<?=$img->cut->top?>"
		x2="<?=$img->w?>"
		y2="<?=$img->cut->top?>"
		id="topCut"	/>
	<?php foreach($img->cut->getEnds() as $key => $endX) { ?>
		<line
			class="column"
			x1="<?=$endX?>"
			y1="<?=$img->cut->top?>"
			x2="<?=$endX?>"
			y2="<?=$img->h?>"
			id="columnEnd<?=$key?>"	/>
	<?php } ?>
</svg>