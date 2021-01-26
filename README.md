# SvgEditor

SVG cut editor for FS puzzles (mosaics).

## Pre-requists
To use the scripts you will need [PHP](https://www.php.net/downloads) installed on your system. I used PHP 7.4, but it might work with PHP 5.3 too.

To edit SVG you need [Inkscape](https://inkscape.org/) or a similar editor(*). I used Inkscape 1.0.1. 

(*) Any Inkscape 1.0 or later should work. A requirement for the editor is to preserve classes on elements and don't change viewBox. The editor must also copy classes (if you need to add lines). The editor should also preserve CSS styles included in the SVG (CSS mostly adds visual aid of types of elements you use, so not that important if you know what you are doing).

## Scripts

* `config.php` -- adjust to your needs. Especially URL, but only if you want to preview results in a browser.

###Editing columns

* `columnsToSvg.php` -- 1st stage (json->svg): generates columns-cut preview/editor. It assumes "copy.jpg" contains an un-cut image (used as a background for).
* `svgToColumns.php` -- 2nd stage (svg->json): after editing SVG this generates a cut JSON.
* `columnsCut.php` -- 3rd stage (json->col*.jpg). Uses generated JSON to cut columns. 

###Editing rows (cells)

* `rowsToSvg.php` -- 1st stage (json->svg): generates rows-cut preview/editor.
* `svgToRows.php` -- 2nd stage (svg->json): after editing SVG this generates a cut JSON.
* `rowsCut.php` -- 3rd stage (json->col*.jpg). Uses generated JSON to cut rows.