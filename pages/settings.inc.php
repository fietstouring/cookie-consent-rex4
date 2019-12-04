<?php

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

// save settings
if ($func == 'update') {
	$settings = (array) rex_post('settings', 'array', array());

	rex_cookie_consent_utils::replaceSettings($settings);
	rex_cookie_consent_utils::updateSettingsFile();
}

// Privacy Policy Link Button
$linkButton = rex_input::factory('linkbutton');
$linkButton->setButtonId(1);
$linkButton->setValue($REX['ADDON']['cookie_consent']['settings']['privacy']);
$linkButton->setAttribute('name', 'settings[privacy]');
$linkButton->setAttribute('id', 'privacy');

// Cookie Consent Theme
$tselect = new rex_select;
$tselect->setSize(1);
$tselect->setName('settings[theme]');
$tselect->setStyle('class="rex-form-select"');
$tselect->setId('theme');
$tselect->addOption('Block','block');
$tselect->addOption('Edgeless','edgeless');
$tselect->addOption('Classic','classic');
$tselect->setSelected($REX['ADDON']['cookie_consent']['settings']['theme']);

// Cookie Consent Position
$pselect = new rex_select;
$pselect->setSize(1);
$pselect->setName('settings[position]');
$pselect->setStyle('class="rex-form-select"');
$pselect->setId('position');
$pselect->addOption('Oben','top');
$pselect->addOption('von oben reinfahren','top-pushdown');
$pselect->addOption('Unten','bottom');
$pselect->addOption('Unten links','bottom-left');
$pselect->addOption('Unten rechts','bottom-right');
$pselect->setSelected($REX['ADDON']['cookie_consent']['settings']['position']);

?>

<div class="rex-addon-output">
	<div class="rex-form">

		<h2 class="rex-hl2"><?php echo $I18N->msg('cookie_consent_settings'); ?></h2>

		<form action="index.php" method="post">

			<fieldset class="rex-form-col-1">
				<div class="rex-form-wrapper">
					<input type="hidden" name="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
					<input type="hidden" name="func" value="update" />

					<div class="rex-form-row rex-form-element-v1">
						<p class="rex-form-checkbox">
							<label for="activate"><?php echo $I18N->msg('cookie_consent_activate'); ?></label>
							<input type="hidden" name="settings[activate]" value="0" />
							<input type="checkbox" name="settings[activate]" id="activate" value="1" <?php if ($REX['ADDON']['cookie_consent']['settings']['activate']) { echo 'checked="checked"'; } ?>>
						</p>
					</div>

					<div class="rex-form-row rex-form-element-v1">
						<p class="rex-form-checkbox">
							<label for="load_js"><?php echo $I18N->msg('cookie_consent_load_js'); ?></label>
							<input type="hidden" name="settings[load_js]" value="0" />
							<input type="checkbox" name="settings[load_js]" id="load_js" value="1" <?php if ($REX['ADDON']['cookie_consent']['settings']['load_js']) { echo 'checked="checked"'; } ?>>
						</p>
					</div>

					<div class="rex-form-row rex-form-element-v1">
						<p class="rex-form-checkbox">
							<label for="load_css"><?php echo $I18N->msg('cookie_consent_load_css'); ?></label>
							<input type="hidden" name="settings[load_css]" value="0" />
							<input type="checkbox" name="settings[load_css]" id="load_css" value="1" <?php if ($REX['ADDON']['cookie_consent']['settings']['load_css']) { echo 'checked="checked"'; } ?>>
						</p>
					</div>

					<div class="rex-form-row rex-form-element-v1">
						<p class="rex-form-checkbox">
							<label for="resources"><?php echo $I18N->msg('cookie_consent_load_after_consent'); ?></label>
							<input type="hidden" name="settings[hide_on_cookie]" value="0" />
							<input type="checkbox" name="settings[hide_on_cookie]" id="hide_on_cookie" value="1" <?php if ($REX['ADDON']['cookie_consent']['settings']['hide_on_cookie']) { echo 'checked="checked"'; } ?>>
						</p>
					</div>

					<div class="rex-form-row">
						<p class="rex-form-col-a rex-form-select">
							<label for="theme"><?php echo $I18N->msg("cookie_consent_theme"); ?></label>
							<?php echo $tselect->get(); ?>
						</p>
					</div>

					<div class="rex-form-row">
						<p class="rex-form-col-a rex-form-select">
							<label for="theme"><?php echo $I18N->msg("cookie_consent_position"); ?></label>
							<?php echo $pselect->get(); ?>
						</p>
					</div>

					<div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_cookiebar_background"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[cookiebarBackground]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['cookiebarBackground']; ?>" />
            </p>
          </div>

          <div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_cookiebar_color"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[cookiebarColor]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['cookiebarColor']; ?>" />
            </p>
          </div>

          <div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_cookiebar_link"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[cookiebarLink]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['cookiebarLink']; ?>" />
            </p>
          </div>

					<div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_btn_ok_color"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[buttonOKColor]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['buttonOKColor']; ?>" />
            </p>
          </div>

					<div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_btn_ok_background"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[buttonOKBackgroundColor]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['buttonOKBackgroundColor']; ?>" />
            </p>
          </div>

          <div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_cookie_expire"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[cookie_expire]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['cookie_expire']; ?>" />
            </p>
          </div>

          <!-- <div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_btn_deny_color"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[buttonDenyColor]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['buttonDenyColor']; ?>" />
            </p>
          </div>

					<div class="rex-form-row rex-form-element-v1">
            <p class="rex-form-col-a rex-form-text">
              <label for="labelcolor"><?php echo $I18N->msg("cookie_consent_btn_deny_background"); ?></label>
              <input class="rex-form-text" data-colorpicker type="text" name="settings[buttonDenyBackgroundColor]" value="<?php echo $REX['ADDON']['cookie_consent']['settings']['buttonDenyBackgroundColor']; ?>" />
            </p>
          </div> -->

					<div class="rex-form-row rex-form-element-v1">
						<div class="rex-form-col-a">
							<label for="privacy"><?php echo $I18N->msg('cookie_consent_privacy'); ?></label>
							<?php echo $linkButton->getHtml(); ?>
						</div>
					</div>

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
<?php if (rex_cookie_consent_utils::checkColorizer()) : ?>

<script type="text/javascript">
jQuery(document).ready( function() {
	jQuery('<img src="../<?php echo rex_colorizer_utils::getMediaAddonDir(); ?>/be_utilities/plugins/colorizer/colorpicker/images/colorpicker_background.png" />');

	jQuery('input[data-colorpicker]').keyup(function() {
		updateColorPreview();
	});

	jQuery('input[data-colorpicker]').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			jQuery(el).val('#' + hex);
			jQuery(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		}
		// onChange: function (hsb, hex, rgb) {
		// 	console.log(jQuery(this));
		// 	jQuery(this).val('#' + hex);
		// }
	})
	.bind('keyup', function(){
		jQuery(this).ColorPickerSetColor(this.value);
	});
});

<?php endif; ?>

</script>
