<?php

$sql = new rex_sql();
$sql->setQuery('DROP TABLE IF EXISTS `' . $REX['TABLE_PREFIX'] . 'cookie_consent`');

$REX['ADDON']['install']['cookie_consent'] = false;

