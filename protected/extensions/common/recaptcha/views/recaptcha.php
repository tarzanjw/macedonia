<?php /** @var EReCaptcha $this */ ?>

<div id="recaptcha_widget" class="recaptcha-widget" style="display:none">

	<div class="recaptcha_image_cover">
	<div id="recaptcha_image"></div>
	</div>
	<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>
    <div class="recaptcha-main">
	<span class="recaptcha_only_if_image"><?= Yii::t('view', 'Nhập lại chữ trên:'); ?></span>

	<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />

	<div class="recaptcha-buttons" style="float:left; margin-left:4px;"><a id="recaptcha_reload_btn" href="javascript:Recaptcha.reload()"><i class="" title="<?= Yii::t('view', 'Lấy mã CAPTCHA khác'); ?>"></i></a></div>

	<div class="recaptcha-buttons"><a id="recaptcha_whatsthis_btn" href="javascript:Recaptcha.showhelp()"><i class="" title="<?= Yii::t('view', 'Trợ giúp'); ?>"></i></a></div>
    </div>
</div>

<script type="text/javascript"
	 src="http://www.google.com/recaptcha/api/challenge?k=<?= $publicKey; ?>">
</script>
<noscript>
	<iframe src="http://www.google.com/recaptcha/api/noscript?k=<?= $publicKey; ?>"
		height="300" width="100%" frameborder="0"></iframe><br>
	<textarea name="recaptcha_challenge_field" rows="3" cols="40">
	</textarea>
	<input type="hidden" name="recaptcha_response_field"
		value="manual_challenge">
</noscript>