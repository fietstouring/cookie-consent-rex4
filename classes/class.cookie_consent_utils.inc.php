<?php
class rex_cookie_consent_utils {

	public static function sanitizeUrl($url) {
		return preg_replace('@^https?://|/.*|[^\w.-]@', '', $url);
	}

	public static function string_escape($value, $strategy="html") {

		$string = $value;

    if ('' === $string) {
        return '';
    }

		switch($strategy) {
			case 'html':
				return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			case 'url':
				return rawurlencode($string);
			case 'js':
        // escape all non-alphanumeric characters
        // into their \xHH or \uHHHH representations

        if (0 === strlen($string) ? false : 1 !== preg_match('/^./su', $string)) {
            throw new InvalidArgumentException('The string to escape is not a valid UTF-8 string.');
        }

        $string = preg_replace_callback('#[^a-zA-Z0-9,\._]#Su', static function ($matches) {
            $char = $matches[0];

            /*
             * A few characters have short escape sequences in JSON and JavaScript.
             * Escape sequences supported only by JavaScript, not JSON, are ommitted.
             * \" is also supported but omitted, because the resulting string is not HTML safe.
             */
            static $shortMap = [
                '\\' => '\\\\',
                '/' => '\\/',
                "\x08" => '\b',
                "\x0C" => '\f',
                "\x0A" => '\n',
                "\x0D" => '\r',
                "\x09" => '\t',
            ];

            if (isset($shortMap[$char])) {
                return $shortMap[$char];
            }

            // \uHHHH
            $char = mb_convert_encoding($char, 'UTF-16BE', 'UTF-8');
            $char = strtoupper(bin2hex($char));

            if (4 >= strlen($char)) {
                return sprintf('\u%04s', $char);
            }

            return sprintf('\u%04s\u%04s', substr($char, 0, -4), substr($char, -4));
        }, $string);

        return $string;
		}

	}

	public static function checkColorizer() {
		$plugins = array();

		if(OOAddon::isAvailable("be_utilities")) {
			$plugins = OOPlugin::getAvailablePlugins("be_utilities");
		}

		if (in_array("colorizer", $plugins)) {
				return true;
			} else {
				return false;
			}
	}

	public static function appendToPageHeader($params) {
		global $REX;

		$insert = '<!-- BEGIN tracking_code -->' . PHP_EOL;
		$insert .= '<link rel="stylesheet" type="text/css" href="../' . self::getMediaAddonDir() . '/tracking_code/backend.css" />' . PHP_EOL;
		$insert .= '<!-- END tracking_code -->';

		if (self::checkColorizer()) {
			$insert .= '<!-- BEGIN Colorpicker -->' . PHP_EOL;
			$insert .= '<link rel="stylesheet" type="text/css" href="../' . self::getMediaAddonDir() . '/be_utilities/plugins/colorizer/colorpicker/colorpicker.css" />' . PHP_EOL;
			$insert .= '<script type="text/javascript" src="../' . self::getMediaAddonDir() . '/be_utilities/plugins/colorizer/colorpicker/colorpicker.js"></script>' . PHP_EOL;
			$insert .= '<!-- END Colorpicker -->' . PHP_EOL;
		}
	
		return $params['subject'] . PHP_EOL . $insert;
	}

	public static function getMediaAddonDir() {
		global $REX;

		// check for media addon dir var introduced in REX 4.5
		if (isset($REX['MEDIA_ADDON_DIR'])) {
			return $REX['MEDIA_ADDON_DIR'];
		} else {
			return 'files/addons';
		}
	}

	public static function getDataAddonDir() {
		global $REX;

		return $REX['INCLUDE_PATH'] . '/data/addons/cookie_consent/';
	}

	public static function getSettingsFile() {
		return self::getDataAddonDir() . 'settings.inc.php';
	}

	public static function includeSettingsFile() {
		global $REX; // important for include

		$settingsFile = self::getSettingsFile();

		if (!file_exists($settingsFile)) {
			self::updateSettingsFile(false);
		}

		require_once($settingsFile);
	}

	public static function updateSettingsFile($showSuccessMsg = true) {
		global $REX, $I18N;

		$settingsFile = self::getSettingsFile();
		$msg = self::checkDirForFile($settingsFile);

		if ($msg != '') {
			if ($REX['REDAXO']) {
				echo rex_warning($msg);			
			}
		} else {
			if (!file_exists($settingsFile)) {
				self::createDynFile($settingsFile);
			}

			$content = "<?php\n\n";
		
			foreach ((array) $REX['ADDON']['cookie_consent']['settings'] as $key => $value) {
				$content .= "\$REX['ADDON']['cookie_consent']['settings']['$key'] = " . var_export($value, true) . ";\n";
			}

			if (rex_put_file_contents($settingsFile, $content)) {
				if ($REX['REDAXO'] && $showSuccessMsg) {
					echo rex_info($I18N->msg('cookie_consent_configfile_update'));
				}
			} else {
				if ($REX['REDAXO']) {
					echo rex_warning($I18N->msg('cookie_consent_configfile_nosave'));
				}
			}
		}
	}

	public static function replaceSettings($settings) {
		global $REX;

		// type conversion
		foreach ($REX['ADDON']['cookie_consent']['settings'] as $key => $value) {
			if (isset($settings[$key])) {
				$settings[$key] = self::convertVarType($value, $settings[$key]);
			}
		}

		$REX['ADDON']['cookie_consent']['settings'] = array_merge((array) $REX['ADDON']['cookie_consent']['settings'], $settings);
	}

	public static function createDynFile($file) {
		$fileHandle = fopen($file, 'w');

		fwrite($fileHandle, "<?php\r\n");
		fwrite($fileHandle, "// --- DYN\r\n");
		fwrite($fileHandle, "// --- /DYN\r\n");

		fclose($fileHandle);
	}

	public static function checkDir($dir) {
		global $REX, $I18N;

		$path = $dir;

		if (!@is_dir($path)) {
			@mkdir($path, $REX['DIRPERM'], true);
		}

		if (!@is_dir($path)) {
			if ($REX['REDAXO']) {
				return $I18N->msg('cookie_consent_install_make_dir', $dir);
			}
		} elseif (!@is_writable($path . '/.')) {
			if ($REX['REDAXO']) {
				return $I18N->msg('cookie_consent_install_perm_dir', $dir);
			}
		}
		
		return '';
	}

	public static function checkDirForFile($fileWithPath) {
		$pathInfo = pathinfo($fileWithPath);

		return self::checkDir($pathInfo['dirname']);
	}

	public static function convertVarType($originalValue, $newValue) {
		$arrayDelimiter = ',';

		switch (gettype($originalValue)) {
			case 'string':
				return trim($newValue);
				break;
			case 'integer':
				return intval($newValue);
				break;
			case 'boolean':
				return (bool) $newValue;
				break;
			case 'array':
				if ($newValue == '') {
					return array();
				} else {
					return explode($arrayDelimiter, $newValue);
				}
				break;
			default:
				return $newValue;
				
		}
	}

	public static function rrmdir($dir) { // removes all subdirs and files recursively
		foreach(glob($dir . '/*') as $file) {
		    if (is_dir($file)) {
		        self::rrmdir($file);
			} else {
		        unlink($file);
			}
		}

		rmdir($dir);
	}

	public static function removeDataAddonDir() {
		$dataAddonDir = self::getDataAddonDir();
		self::rrmdir($dataAddonDir);
	}

	public static function getHtmlFromMDFile($mdFile, $search = array(), $replace = array(), $setBreaksEnabled = true) {
		global $REX;

		$curLocale = strtolower($REX['LANG']);

		if ($curLocale == 'de_de') {
			$file = $REX['INCLUDE_PATH'] . '/addons/cookie_consent/' . $mdFile;
		} else {
			$file = $REX['INCLUDE_PATH'] . '/addons/cookie_consent/lang/' . $curLocale . '/' . $mdFile;
		}

		if (file_exists($file)) {
			$md = file_get_contents($file);
			$md = str_replace($search, $replace, $md);
			$md = self::makeHeadlinePretty($md);

			if (method_exists('Parsedown', 'set_breaks_enabled')) {
				$out = Parsedown::instance()->set_breaks_enabled($setBreaksEnabled)->parse($md);
			} elseif (method_exists('Parsedown', 'setBreaksEnabled')) {
				$out = Parsedown::instance()->setBreaksEnabled($setBreaksEnabled)->parse($md);
			} else {
				$out = Parsedown::instance()->parse($md);
			}

			return $out;
		} else {
			return '[translate:' . $file . ']';
		}
	}
}

