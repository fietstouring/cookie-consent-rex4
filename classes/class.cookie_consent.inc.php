<?php
class rex_cookie_consent {

	protected static $trackingCode = '';
	protected static $cookieOptions = '';
    protected static $nowrap_js = '';

	const DEFAULT_MODE = 'opt-in';
	const DEFAULT_TEXT = 'Dieser Text wird in der Cookiebar angezeigt.';
	const DEFAULT_BUTTON_TXT = 'OK';
	const DEFAULT_LINK_CONTENT = 'Datenschutzerklärung';
	const DEFAULT_DENY = 'No Cookies';
	const COOKIE_NAME = 'cookieconsent_status';

	public static function getCss()
    {
        $theme = $REX['ADDON']['cookie_consent']['settings']['theme'];

        if ($theme == 'clean') {
            $cssFile = 'css/cookie_consent_insites_clean.css';
        } else {
            $cssFile = 'css/cookieconsent.min.css';
        }
        $getCSS = '<link rel="stylesheet" href="/' . rex_cookie_consent_utils::getMediaAddonDir() . '/cookie_consent/'.$cssFile.'" />' . PHP_EOL;
        
        return $getCSS;
    }

   public static function getJs()
    {
        $makeJsLink = '<script async src="/' . rex_cookie_consent_utils::getMediaAddonDir() . '/cookie_consent/js/cookieconsent.min.js"></script>' . PHP_EOL;
        return $makeJsLink;
    }

  public static function cookie_consent_output() {
  	global $REX;

  	// do nothing when cookie is set and setting accordingly
		if ( isset($REX['ADDON']['cookie_consent']['settings']['hide_on_cookie']) && $REX['ADDON']['cookie_consent']['settings']['hide_on_cookie'] == 1 && isset($_COOKIE[self::COOKIE_NAME])) {
			return '';
		}

		$curlang = $REX['CUR_CLANG'];
        $load_js = $REX['ADDON']['cookie_consent']['settings']['load_js'];
        $load_css = $REX['ADDON']['cookie_consent']['settings']['load_css'];
		$theme = $REX['ADDON']['cookie_consent']['settings']['theme'];
		$position = $REX['ADDON']['cookie_consent']['settings']['position'];
        $cookie_expire = $REX['ADDON']['cookie_consent']['settings']['cookie_expire'];

        $main_message = empty($REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['main_message']) ? self::DEFAULT_TEXT : $REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['main_message'];
        $button_content = empty($REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['button_content']) ? self::DEFAULT_BUTTON_TXT : $REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['button_content'];
        $deny_content = empty($REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['deny_content']) ? self::DEFAULT_DENY : $REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['deny_content'];
		$color_background = empty($REX['ADDON']['cookie_consent']['settings']['cookiebarBackground']) ? '#000' : $REX['ADDON']['cookie_consent']['settings']['cookiebarBackground'];
        $color_main_content = empty($REX['ADDON']['cookie_consent']['settings']['cookiebarColor']) ? '#fff' : $REX['ADDON']['cookie_consent']['settings']['cookiebarColor'];
        $color_button_background = empty($REX['ADDON']['cookie_consent']['settings']['buttonOKBackgroundColor']) ? 'yellow' : $REX['ADDON']['cookie_consent']['settings']['buttonOKBackgroundColor'];
        $color_button_content = empty($REX['ADDON']['cookie_consent']['settings']['buttonOKColor']) ? 'black' : $REX['ADDON']['cookie_consent']['settings']['buttonOKColor'];
        $color_link = empty($REX['ADDON']['cookie_consent']['settings']['cookiebarLink']) ? 'yellow' : $REX['ADDON']['cookie_consent']['settings']['cookiebarLink'];
        $link = empty($REX['ADDON']['cookie_consent']['settings']['privacy']) ? '' : $REX['ADDON']['cookie_consent']['settings']['privacy'];
        $link_content = empty($REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['link_content']) ? self::DEFAULT_LINK_CONTENT : $REX['ADDON']['cookie_consent']['settings']['lang'][$curlang]['link_content'];

    $object = [
        'theme' => $theme,
        'position' => $position,
        'content' => [
            'message' => rex_cookie_consent_utils::string_escape($main_message),
            'deny' => rex_cookie_consent_utils::string_escape($deny_content),
            'allow' => rex_cookie_consent_utils::string_escape($button_content),
            'link' => rex_cookie_consent_utils::string_escape($link_content),
            'href' => rex_getUrl($link, $curlang),
        ],
        'type' => self::DEFAULT_MODE,
        'elements' => [
            'messagelink' => '<span id="cookieconsent:desc" class="cc-message">{{message}} <a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}" target="'.$link_target_type.'">{{link}}</a></span>',
        ],
    ];

    if (($pos = strpos($position, '-pushdown')) !== false) {
        $object['position'] = substr($position, 0, $pos);
        $object['static'] = true;
    }

    $object['cookie'] = [
    	'expiryDays' => $cookie_expire
    ];

    $object['palette'] = [
        'popup' => [
            'background' => rex_cookie_consent_utils::string_escape($color_background),
            'text' => rex_cookie_consent_utils::string_escape($color_main_content),
            'link' => rex_cookie_consent_utils::string_escape($color_link)
        ],
        'button' => [
            'background' => rex_cookie_consent_utils::string_escape($color_button_background),
            'text' => rex_cookie_consent_utils::string_escape($color_button_content),
        ],
    ];

    // if ($theme != 'block') {
    //     $object['palette'] = [
    //         'popup' => [
    //             'background' => rex_cookie_consent_utils::string_escape($color_background),
    //             'text' => rex_cookie_consent_utils::string_escape($color_main_content),
    //         ],
    //         'button' => [
    //             'background' => rex_cookie_consent_utils::string_escape($color_button_background),
    //             'text' => rex_cookie_consent_utils::string_escape($color_button_content),
    //         ],
    //     ];
    // }

    $out = '';
    $jsonConfig = json_encode($object, JSON_PRETTY_PRINT);

    $custom_options = self::getCookieOptions();

   	if ($custom_options && $custom_options != '') {
        $jsonConfig = substr($jsonConfig, 0, strlen($jsonConfig) - 2) . ','.PHP_EOL.$custom_options.PHP_EOL . '}';
    }

    $jsConfigCode = 'window.cookieconsent.initialise('.$jsonConfig.');';

    if($load_css) {
    	$out .= self::getCss();
    }

    if($load_js) {
        $out .= self::getJs();
    }

	$out .= '<script>'.PHP_EOL.'window.addEventListener("load", function() {'.$jsConfigCode . PHP_EOL . '});' . PHP_EOL . '</script>';

	return $out;

  }

	public static function getTrackingCode() {
		global $REX;
		
		$sql = new rex_sql();
		$sql->setQuery('SELECT * FROM ' . $REX['TABLE_PREFIX'] . 'cookie_consent WHERE id = 1');
	
		// custom options field is not empty
		if ($sql->getRows() > 0) {
			self::$trackingCode = $sql->getValue('tracking_code');
		}

        if (self::$trackingCode == '') {
            return '';
        } else {
            return self::$trackingCode . PHP_EOL;
        }
	}


	public static function getCookieOptions() {
		global $REX;

		$sql = new rex_sql();
		$sql->setQuery('SELECT * FROM ' . $REX['TABLE_PREFIX'] . 'cookie_consent WHERE id = 1');
	
		// custom options field is not empty
		if ($sql->getRows() > 0) {
			self::$cookieOptions = $sql->getValue('cookie_options');
		}

		if (self::$cookieOptions == '') {
			return '';
		} else {
			return self::$cookieOptions . PHP_EOL;
		}

	}

    public static function getNowrapJS() {
        global $REX;

        $sql = new rex_sql();
        $sql->setQuery('SELECT * FROM ' . $REX['TABLE_PREFIX'] . 'cookie_consent WHERE id = 1');
    
        // custom options field is not empty
        if ($sql->getRows() > 0) {
            self::$nowrap_js = $sql->getValue('nowrap_js');
        }

        if (self::$nowrap_js == '') {
            return '';
        } else {
            return self::$nowrap_js . PHP_EOL;
        }

    }

	public static function getCookieConsent() {
        global $REX;

        if ( $REX['ADDON']['cookie_consent']['settings']['activate'] == 1 ) {
            if ( $REX['ADDON']['cookie_consent']['settings']['hide_on_cookie'] == 1 && isset($_COOKIE[self::COOKIE_NAME])) {
                return self::getNowrapJS() . PHP_EOL . self::cookie_consent_output() . PHP_EOL;
            } else {
                return self::$trackingCode . PHP_EOL . self::cookie_consent_output() . PHP_EOL;
            }
        } else {
		  return false;
        }
	}
}

