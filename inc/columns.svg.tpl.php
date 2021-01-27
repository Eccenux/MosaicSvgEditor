<?='<?xml version="1.0" encoding="UTF-8" standalone="no"?>'?>
<svg
	viewBox="0 0 <?=$img->w?> <?=$img->h?>"
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
>
<title>Columns cut</title>
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
	line.column-bottom {
		stroke: yellow;
		stroke-dasharray: 20 20;
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
	<!-- main column-cuts -->
	<?php foreach($img->cut->getEnds() as $key => $endX) { ?>
		<line
			class="column"
			x1="<?=$endX?>"
			y1="<?=$img->cut->top?>"
			x2="<?=$endX?>"
			y2="<?=$img->h?>"
			id="columnEnd<?=$key?>"	/>
	<?php } ?>
	<!-- bottom cuts -->
	<?php
		$startX = 0;
		$minWidth = 300;
	?>
	<?php foreach($img->cut->columnBottoms as $key => $bottomY) { ?>
		<?php
			$endX = $img->cut->getColumnEnd($key, $startX + $minWidth);
		?>
		<line
			class="column-bottom"
			x1="<?=$startX?>"
			y1="<?=$bottomY?>"
			x2="<?=$endX?>"
			y2="<?=$bottomY?>"
			id="columnBottom<?=$key?>"	/>
		<?php
			$startX = $endX;
		?>
	<?php } ?>
</svg>