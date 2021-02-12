@ECHO OFF

rem Load settings
CALL __settings.bat

rem Execute
"%tmpset_PHP_PATH%" svgToColumns.php
echo.
"%tmpset_PHP_PATH%" columnsCut.php

echo.
echo Done
PAUSE
