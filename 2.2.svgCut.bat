@ECHO OFF

rem Load settings
CALL __settings.bat

rem Execute
"%tmpset_PHP_PATH%" svgToRows.php
"%tmpset_PHP_PATH%" rowsCut.php

echo Done
PAUSE
