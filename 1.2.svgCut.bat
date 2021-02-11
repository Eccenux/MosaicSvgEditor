@ECHO OFF

rem Load settings
CALL __settings.bat

rem Execute
"%tmpset_PHP_PATH%" svgToColumns.php
"%tmpset_PHP_PATH%" columnsCut.php

echo Done
PAUSE
