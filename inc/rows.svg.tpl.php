<?='<?xml version="1.0" encoding="UTF-8" standalone="no"?>'?>
<?php
	$columnGap = 30;
	$columnCount = $rowsMeta->getCount();
	$viewWidth = $totalWidth + ($columnCount * $columnGap);
?>
<svg
	viewBox="0 0 <?=$viewWidth?> <?=$maxHeight?>"
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
	style="background-color:black"
>
<title>Rows cut</title>
<style>
	line {
		fill: none;
		stroke: red;
		stroke-width: 4px;
		stroke-dasharray: 40 30;
	}
	line.row {
		stroke: yellow;
		stroke-dasharray: 20 20;
	}
</style>
  
	<!-- columns cut to rows -->
	<?php
		$startX = 0;
	?>
	<?php foreach($rowsMeta->getColumns() as $columnIndex => $column) { ?>
		<?php
			$img = $imgs[$columnIndex];
			$columnWidth = $img->w;
			$endX = $startX + $columnWidth;
		?>
		<image
			xlink:href="<?=$columnsSvgPath?><?=$column->img?>"
			height="<?=$img->h?>"
			width="<?=$img->w?>"
			y="0"
			x="<?=$startX?>"
		/>
		<?php foreach($column->rowEnds as $rowIndex => $rowY) { ?>
			<line
				class="row"
				x1="<?=$startX?>"
				y1="<?=$rowY?>"
				x2="<?=$endX?>"
				y2="<?=$rowY?>"
				id="row-<?=$columnIndex?>-<?=$rowIndex?>"	/>
		<?php } ?>
		<?php
			$startX = $endX + $columnGap;
		?>
	<?php } ?>
</svg>