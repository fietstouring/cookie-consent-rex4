<?php
$error = '';

$sql = new rex_sql();
//$sql->debugsql=1;

$sql->setQuery("
	CREATE TABLE IF NOT EXISTS `" . $REX['TABLE_PREFIX'] . "cookie_consent` (
		`id` INT(11) unsigned NOT NULL auto_increment,
		`tracking_code` TEXT NOT NULL,
		`cookie_options` TEXT NOT NULL,
		`nowrap_js` TEXT NOT NULL,
	PRIMARY KEY ( `id` )
);");

$sql->setQuery('INSERT INTO `' . $REX['TABLE_PREFIX'] . 'cookie_consent` VALUES (1, "", "", "")');                                                                                

if ($error == '') {
	$REX['ADDON']['install']['cookie_consent'] = true;
} else {
	$REX['ADDON']['installmsg']['cookie_consent'] = $error;
}



