<?php
$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

if (rex_request('func', 'string') == 'save') {
	$trackingCode = trim(rex_request('tracking_code', 'string'));
	$cookieOptions = trim(rex_request('cookie_options', 'string'));
	$nowrap_js = trim(rex_request('nowrap_js', 'string'));

	$sql = new rex_sql();
	// $sql->debugsql = 1;
	$sql->setQuery('UPDATE ' . $REX['TABLE_PREFIX'] . 'cookie_consent SET tracking_code = "' . $trackingCode . '", cookie_options = "' . $cookieOptions . '", nowrap_js = "' . $nowrap_js . '" WHERE id = 1');

	if ($sql->getError() == '')  {
		echo rex_info($I18N->msg('cookie_consent_configfile_update'));
	} else {
		echo rex_warning($I18N->msg('cookie_consent_configfile_nosave'));
	}
} 

// rex_tracking_code::init();
$trackingCode = rex_cookie_consent::getTrackingCode();
$cookieOptions = rex_cookie_consent::getCookieOptions();
$nowrap_js = rex_cookie_consent::getNowrapJS();

?>

<div class="rex-addon-output">
	<div class="rex-form">
		<h2 class="rex-hl2"><?php echo $I18N->msg('cookie_consent_scripts'); ?></h2>
		
			<form action="index.php" method="post">
				<fieldset class="rex-form-col-1">
					<div class="rex-form-wrapper">
						<div class="rex-form-row rex-form-element-v1">
							<label for="tracking_code" class="width100"><?php echo $I18N->msg('cookie_consent_tracking_code'); ?></label>
							<textarea <?php if (OOPlugin::isAvailable('be_utilities', 'codemirror') || (isset($REX['ADDON']['be_style']['plugin_customizer']['codemirror']) && $REX['ADDON']['be_style']['plugin_customizer']['codemirror'] == 1)) { ?>style="display: none;"<?php } ?> class="codemirror" codemirror-mode="php/htmlmixed" name="tracking_code"><?php echo $trackingCode; ?></textarea>
						</div>

						<div class="rex-form-row rex-form-element-v1">
							<label for="cookie_options" class="width100"><?php echo $I18N->msg('cookie_consent_cookie_options'); ?></label>
							<textarea <?php if (OOPlugin::isAvailable('be_utilities', 'codemirror') || (isset($REX['ADDON']['be_style']['plugin_customizer']['codemirror']) && $REX['ADDON']['be_style']['plugin_customizer']['codemirror'] == 1)) { ?>style="display: none;"<?php } ?> class="codemirror" codemirror-mode="php/htmlmixed" name="cookie_options"><?php echo $cookieOptions; ?></textarea>
						</div>

							<div class="rex-form-row rex-form-element-v1">
							<label for="nowrap_js" class="width100"><?php echo $I18N->msg('cookie_consent_nowrap_js'); ?></label>
							<textarea <?php if (OOPlugin::isAvailable('be_utilities', 'codemirror') || (isset($REX['ADDON']['be_style']['plugin_customizer']['codemirror']) && $REX['ADDON']['be_style']['plugin_customizer']['codemirror'] == 1)) { ?>style="display: none;"<?php } ?> class="codemirror" codemirror-mode="php/htmlmixed" name="nowrap_js"><?php echo $nowrap_js; ?></textarea>
						</div>

						<input type="hidden" name="page" value="<?php echo $page; ?>" />
						<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
						<input type="hidden" name="func" value="save" />
						<div class="rex-form-row rex-form-element-v1">
								<p class="rex-form-submit">
									<input type="submit" class="rex-form-submit" name="sendit" value="<?php echo $I18N->msg('cookie_consent_save_button'); ?>" />
								</p>
						</div>
					</div>
				</fieldset>
			</form>
	
	</div>
</div>

<style type="text/css">
#rex-page-cookie-consent div.rex-form .CodeMirror {
	width: 98%;
	height: 320px !important;
}

#rex-page-cookie-consent .width100 {
	width: 100%;
}

#rex-page-cookie-consent .button {
	float: right; 
	margin-top: 10px;
	margin-bottom: 10px; 
	margin-right: 5px;
}

#rex-page-cookie-consent .button input {
	padding: 2px;
}

.CodeMirror {
    border: 1px solid #999999 !important;
}
</style>
