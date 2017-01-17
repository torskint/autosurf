<?php
$endHour = mktime(00, 00, 00, date("m"), date("d"), date("Y"))-3600;
$nowHour = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
echo date("H:i:s", $endHour - $nowHour);

