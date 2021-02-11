@ECHO OFF

rem Load settings
CALL __settings.bat

rem Execute
"%tmpset_PHP_PATH%" columnsToSvg.php

echo Done
PAUSE
