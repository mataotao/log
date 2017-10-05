<?php
$gframework_libraries = 'D:\Visual-AMP-x64\www\newwwwwwwwwwwwwwwwwwwwwwwwwwww\framework\branches\deving\src\libraries';
$gframework_cache = 'D:\Visual-AMP-x64\www\newwwwwwwwwwwwwwwwwwwwwwwwwwww\framework\branches\deving\src\cache';
$GLOBALS['app_namesapces'] = array(
	'App' => array(dirname(dirname(__FILE__))),
	'Gredis' => array($gframework_libraries),
	'Gredisclient' => array($gframework_libraries),
	'Crc16' => array($gframework_libraries),
	'Cachepredis' => array($gframework_cache),
);
