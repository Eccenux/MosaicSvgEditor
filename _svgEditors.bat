@ECHO OFF

rem Load settings
CALL __settings.bat

rem Execute
"%tmpset_PHP_PATH%" columnsToSvg.php
"%tmpset_PHP_PATH%" rowsToSvg.php


echo.
echo Done
PAUSE
