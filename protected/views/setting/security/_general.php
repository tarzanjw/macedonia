<p class="lead"><?= Yii::t('view', 'Email'); ?></p>
<p>
<?= Yii::t('view', 'Chúng tôi sẽ sử dụng địa chỉ email của bạn để thực hiện những việc như xác minh bạn là chủ sở hữu của tài khoản, đặt lại mật khẩu và hơn thế nữa.'); ?>
</p>
<p>
<?= Yii::t('view', 'Email hiện tại của bạn là <b>:email</b>', array(':email'=>Yii::app()->user->email)); ?>
</p>
<hr>

<p class="lead"><?= Yii::t('view', 'Điện thoại di động'); ?></p>
<p>
	<?= Yii::t('view', 'Chúng tôi sẽ sử dụng điện thoại của bạn để thực hiện nhằm xác thực chi tiết hơn với tài khoản của bạn.'); ?>
	<br>
	<a href="#" rel="tooltip" title="Điện thoại quan trong thế nào?"><?= Yii::t('view', 'Điện thoại quan trong thế nào?'); ?></a>
</p>
<p>
	<?= Yii::t('view', 'Số điện thoại hiện tại của bạn là <b>:phone</b>', array(':phone'=>Yii::app()->user->phone)); ?>
	&nbsp;&nbsp;&nbsp;
	<a href="<?= $this->createUrl('security',array('tab'=>'phone')) ?>" class="btn btn-small"><i class="icon-pencil"></i> <?= Yii::t('view', 'Thay đổi'); ?></a>
</p>
<hr>

<p class="lead"><?= Yii::t('view', 'Câu hỏi bí mật'); ?></p>
<p>
	<?= Yii::t('view', 'Đây là hình thức xác thực cao nhất chúng tôi sẽ dùng để khôi phục tài khoản của bạn.'); ?>
	<br>
	<a href="#" rel="tooltip" title="Ý nghĩa của câu hỏi bí mật?"><?= Yii::t('view', 'Ý nghĩa của câu hỏi bí mật?'); ?></a>
</p>
<p>
	<?= $acc->auth->secret_question; ?>
	&nbsp;&nbsp;&nbsp;
	<a href="<?= $this->createUrl('security',array('tab'=>'question')); ?>" class="btn btn-small"><i class="icon-pencil"></i> <?= Yii::t('view', 'Thay đổi'); ?></a>
</p>
<hr>