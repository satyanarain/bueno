<?php
  //default timezone
date_default_timezone_set ('Asia/kolkata');
$date = new DateTime(null);
echo 'Timestamp: '.$date->getTimestamp().'<br />'."\r\n";
echo 'Date: '.$date->format(DateTime::ATOM).'<br />'."\r\n";
?>