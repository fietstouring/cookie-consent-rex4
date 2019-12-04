<?php
$codeExample1 = '<?php echo rex_cookie_consent::getCookieConsent(); ?>';
$example2 = <<< EOD
onInitialise: function (status) {
 var type = this.options.type;
 var didConsent = this.hasConsented();
 if (didConsent) {
   enable_tracking();
 }
},
onStatusChange: function(status, chosenBefore) {
 var type = this.options.type;
 var didConsent = this.hasConsented();
 if (didConsent) {
   enable_tracking();
 }
}
EOD;

$example3 = <<< EOD
<script>
function enable_tracking() {
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-xxxxxxxx-x', 'auto');
  ga('set', 'anonymizeIp', true);
  ga('send', 'pageview');
}
</script>
EOD;
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('cookie_consent_help'); ?></h2>
	<div class="rex-area-content">
		<p><?php echo $I18N->msg('cookie_consent_help_msg'); ?></p>
		<?php rex_highlight_string($codeExample1); ?>
	</div>

	<h2 class="rex-hl2">Beispiel für Cookie Consent Options:</h2>
	<div class="rex-area-content">
	<p>(ohne script-Tags eintragen!)<p>
		<?php rex_highlight_string($example2); ?>
	</div>

	<h2 class="rex-hl2">Beispiel für Tracking Code:</h2>
	<div class="rex-area-content">
	<p>in Funktion wrappen, mit script-Tags<p>
		<?php rex_highlight_string($example3); ?>
	</div>



</div>

<style type="text/css">
.rex-addon-output p {
	margin-bottom: 5px;
}
</style>
