<?php

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

// save settings
if ($func == 'update') {
	$settings = (array) rex_post('settings', 'array', array());
	$langSettings = (array) rex_post('lang_settings', 'array', array());

	rex_cookie_consent_utils::replaceSettings($settings);

	// type conversion lang settings
	foreach ($REX['CLANG'] as $clangId => $clangName) {
		if (isset($langSettings[$clangId])) {
			foreach ($langSettings[$clangId] as $key => $value) {
				if (isset($langSettings[$clangId][$key]) && isset($REX['ADDON']['cookie_consent']['settings']['lang'][0][$key])) {
					$langSettings[$clangId][$key] = rex_cookie_consent_utils::convertVarType($REX['ADDON']['cookie_consent']['settings']['lang'][0][$key], $langSettings[$clangId][$key]);
				}
			}
		}
	}

	// replace lang settings
	unset($REX['ADDON']['cookie_consent']['settings']['lang']);
	$REX['ADDON']['cookie_consent']['settings']['lang'] = $langSettings;
	
	rex_cookie_consent_utils::updateSettingsFile();
}


?>

<div class="rex-addon-output">
	<div class="rex-form">

		<h2 class="rex-hl2"><?php echo $I18N->msg('cookie_consent_texts'); ?></h2>

		<form action="index.php" method="post">

			<fieldset class="rex-form-col-1">
				<div class="rex-form-wrapper">
					<input type="hidden" name="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
					<input type="hidden" name="func" value="update" />

					<?php 
					foreach ($REX['CLANG'] as $clangId => $clangName) {
						$main_message = isset($REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['main_message']) ? $REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['main_message'] : '';
						$button_content = isset($REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['button_content']) ? $REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['button_content'] : '';
						$deny_content = isset($REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['deny_content']) ? $REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['deny_content'] : '';
						$link_content = isset($REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['link_content']) ? $REX['ADDON']['cookie_consent']['settings']['lang'][$clangId]['link_content'] : '';
					?>

						<fieldset class="rex-form-col-1">
							<legend><?php echo $I18N->msg('seo42_settings_langname_section');?> <?php echo $clangName; ?></legend>
							<div class="rex-form-wrapper">
								<div class="rex-form-row rex-form-element-v1">
									<p class="rex-form-text">
										<label for="lang_settings_<?php echo $clangId; ?>_main_message"><?php echo $I18N->msg('cookie_consent_main_message'); ?></label>
										<textarea cols="50" rows="5" name="lang_settings[<?php echo $clangId; ?>][main_message]" id="lang_settings_<?php echo $clangId; ?>_main_message"><?php echo $main_message; ?></textarea>
									</p>
								</div>

								<div class="rex-form-row rex-form-element-v1">
									<p class="rex-form-text">
										<label for="lang_settings_<?php echo $clangId; ?>_button_content"><?php echo $I18N->msg('cookie_consent_button_content'); ?></label>
										<input type="text" value="<?php echo $button_content; ?>" name="lang_settings[<?php echo $clangId; ?>][button_content]" class="rex-form-text" id="lang_settings_<?php echo $clangId; ?>_button_content">
									</p>
								</div>

								<div class="rex-form-row rex-form-element-v1">
									<p class="rex-form-text">
										<label for="lang_settings_<?php echo $clangId; ?>_deny_content"><?php echo $I18N->msg('cookie_consent_deny_content'); ?></label>
										<input type="text" value="<?php echo $deny_content; ?>" name="lang_settings[<?php echo $clangId; ?>][deny_content]" class="rex-form-text" id="lang_settings_<?php echo $clangId; ?>_deny_content">
									</p>
								</div>

								<div class="rex-form-row rex-form-element-v1">
									<p class="rex-form-text">
										<label for="lang_settings_<?php echo $clangId; ?>_link_content"><?php echo $I18N->msg('cookie_consent_link_text'); ?></label>
										<input type="text" value="<?php echo $link_content; ?>" name="lang_settings[<?php echo $clangId; ?>][link_content]" class="rex-form-text" id="lang_settings_<?php echo $clangId; ?>_link_content">
									</p>
								</div>

							</div>

						</fieldset>

					<?php	
					}

					?>

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
