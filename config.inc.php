<?php
// init addon
$REX['ADDON']['name']['cookie_consent'] = 'Cookie Consent Rex4';
$REX['ADDON']['page']['cookie_consent'] = 'cookie_consent';
$REX['ADDON']['version']['cookie_consent'] = '1.0';
$REX['ADDON']['author']['cookie_consent'] = "Philipp Hofstätter, RexDude";
$REX['ADDON']['supportpage']['cookie_consent'] = 'forum.redaxo.de';
$REX['ADDON']['perm']['cookie_consent'] = 'cookie_consent[]';

// permissions
$REX['PERM'][] = 'cookie_consent[]';

// includes
require($REX['INCLUDE_PATH'] . '/addons/cookie_consent/classes/class.cookie_consent.inc.php');

// fetch all strings for later usage with getString method
if (!$REX['SETUP']) {
	rex_register_extension('ADDONS_INCLUDED', 'rex_cookie_consent::getTrackingCode');
}

// includes
require($REX['INCLUDE_PATH'] . '/addons/cookie_consent/classes/class.cookie_consent_utils.inc.php');

// default settings (user settings are saved in data dir!)
$REX['ADDON']['cookie_consent']['settings'] = array(
	'activate' => 0,
  'load_js' => 1,
  'load_css' => 1,
  'hide_on_cookie' => 0,
  'theme' => 'classic',
  'position' => 'bottom',
  'cookiebarBackground' => '#000000',
  'cookiebarColor' => '#ffffff',
  'cookiebarLink' => 'yellow',
  'buttonOKColor' => '#000000',
  'buttonOKBackgroundColor' => 'yellow',
  'cookie_expire' => '1'
);


// overwrite default settings with user settings
rex_cookie_consent_utils::includeSettingsFile();

if ($REX['REDAXO']) {
	
	// add lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/cookie_consent/lang/');

	// add subpages
	$REX['ADDON']['cookie_consent']['SUBPAGES'] = array(
		array('settings', $I18N->msg('cookie_consent_settings')),
		array('texts', $I18N->msg('cookie_consent_texts')),
		array('scripts', $I18N->msg('cookie_consent_scripts'))
	);

	if (isset($REX['USER']) && $REX['USER']->isAdmin()) {
		$REX['ADDON']['cookie_consent']['SUBPAGES'][] = array('help', $I18N->msg('cookie_consent_help'));
	}

	// add css/js files to page header
  if (rex_request('page') == 'cookie_consent') {
    rex_register_extension('PAGE_HEADER', 'rex_cookie_consent_utils::appendToPageHeader');
  }
}
