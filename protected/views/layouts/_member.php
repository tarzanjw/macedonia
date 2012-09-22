<div id='account-box' class="account-box">
<?php
	/** @var CWebUser */$user=Yii::app()->user;
	/** @var Acc */$acc = Acc::model()->findByPk($user->id);
?>
<?php if ($user->getIsGuest()): ?>
	<?php if ($this->getId() != 'SignIn'): ?>
	<a class="btn btn-signin" href="<?= $this->createUrl('/SignIn', array('_cont'=>$this->getCurrentUrl())); ?>"><i class="icon-arrow-right"></i> <?= Yii::t('label', 'Đăng nhập'); ?></a>
	<?php endif; ?>
<?php else: ?>
	<a id="account-popover" class="account-popover">
		<span class="caret"></span>
		<img class="avatar" src="<?= $acc->avatar; ?>" />
		<span class="name"><?= $user->name; ?></span>
  	</a>

  	<?php ob_start(); ?>
		<div class="row-fluid">
			<div class="span4"><img src="<?= $acc->avatar; ?>" alt="<?= $user->name; ?>"></div>
			<div class="span8">
				<div class="info">
					<div class="name"><?= $user->name; ?></div>
					<div class="email"><?= $acc->email; ?></div>
					<div class="phone"><?= $acc->phone; ?></div>
				</div>
				<div class="links">
					<a href="<?= $this->createUrl('/setting/'); ?>"><?= Yii::t('view', 'Tài khoản'); ?></a>
					-
					<a href="<?= $this->createUrl('/setting/security'); ?>"><?= Yii::t('view', 'Bảo mật'); ?></a>
				</div>
			</div>
		</div>
		<div class="row-fluid commands">
			<a class="btn pull-right" href="<?= $this->createUrl('/SignOut', array('_cont'=>$this->getCurrentUrl())); ?>"><i class="icon-off"></i> <?= Yii::t('view', 'Đăng xuất'); ?></a>
		</div>
	<?php $board=ob_get_clean(); ?>

	<script language="JavaScript">
	<!--
	jQuery(function($) {
		$('#account-box').popover({
			placement: 'bottom',
			trigger: 'manual',
			template: '<div id="account-board" class="popover account-board"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p></p></div></div></div>',
			content: <?= CJavaScript::encode($board); ?>
		});

		$('#account-popover').click(function(e) {
        	$('#account-box').popover('toggle');
        	e.preventDefault();
        	var closeAccountBoard = function(e) {
            	var accBoard = $('#account-board');
            	if (accBoard == e.srcElement || accBoard.has(e.srcElement).length) {
					$(document).one('click', closeAccountBoard);
					return true;
            	}

            	$('#account-box').popover('hide');
        	}

        	$(document).one('click', closeAccountBoard);
        	return false;
		});
	});
	//-->
	</script>
<?php endif; ?>
</div>