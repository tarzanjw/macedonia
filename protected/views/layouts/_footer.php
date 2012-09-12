<div class="footer-bar">
	<div class="footer content">
        <ul>
        	<li><?= Yii::t('label', '&copy; 2012 bởi Vật Giá'); ?></li>
        	<li><a href="#"><?= Yii::t('view', 'Điều khoản dịch vụ'); ?></a></li>
        	<li><a href="#"><?= Yii::t('view', 'Chính sách bảo mật'); ?></a></li>
        	<li><a href="#"><?= Yii::t('view', 'Trợ giúp'); ?></a></li>
        </ul>

        <?php $l = Yii::app()->language;
        	$langs = array(
            	array('value'=>'vi', 'label'=>'Tiếng Việt'),
            	array('value'=>'en_us', 'label'=>'English'),
            	array('value'=>'ja', 'label'=>'日本語‬',),
        	);
        ?>
        <form>
            <select name="_l" id="lang-chooser" class="lang-chooser">
            <?php foreach ($langs as $lang): ?>
            	<?php if ($lang['value'] == $l): ?>
            	<option value="<?= $lang['value']; ?>" selected="selected"><?= $lang['label']; ?></option>
            	<?php else: ?>
            	<option value="<?= $lang['value']; ?>"><?= $lang['label']; ?></option>
            	<?php endif; ?>
            <?php endforeach; ?>
            </select>
        </form>
	</div>
</div>

<script language="JavaScript">
<!--
jQuery(function ($) {
	$('#lang-chooser').change(function () {
        var l = window.location;
        var i = $(this);
        var _l = i.attr('name');
        var lang = _l+'='+i.val();

        if (!l.search.length) l.search = '?'+lang;
        else {
			var r = new RegExp('(\\?|&)'+_l+'=[^&]*');
			if (r.test(l.search)) l.search = l.search.replace(r, '$1'+lang);
			else l.search = l.search + ((l.search.length > 1)?'&':'') + lang;
        }
	});
});
//-->
</script>