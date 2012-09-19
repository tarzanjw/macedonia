<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>

<?php
	$ru = $this->getReturnUrl();

	$openIdProviders = array(
		array(
			'name'=>'Google',
			'logo'=>$this->module->assetsUrl.'/images/google_logo.jpg',
			'link'=>$this->createUrl('google', array('ru'=>$ru)),
		),
		array(
			'name'=>'Yahoo',
			'logo'=>$this->module->assetsUrl.'/images/yahoo_logo.png',
			'link'=>$this->createUrl('yahoo', array('ru'=>$ru)),
		),
	);
?>

<center>
	<div style="width: 600px;">
		<h1>Signing in required</h1>
		<h3>Your last action requires you to sign in.</h3>
		<h4>Please choose one of those below methods</h4>

		<hr>

		<div class="loginBtns" align="center">
			<?php foreach ($openIdProviders as $op): ?>
			<a href="<?php echo $op['link']; ?>" title="Sign in by <?php echo $op['name'] ?>" class="loginBtn"><img src="<?php echo $op['logo']; ?>" alt="<?php echo $op['name']; ?>"></a>
			<?php endforeach; ?>
		</div>
    </div>
</center>

<script type='text/javascript'>
jQuery(function ($) {
	$('.loginBtns a.loginBtn').each(function (idx, btn) {
		$(btn).click(function (e) {
			console.log(this);
			e.preventDefault();

			var screenx	=	(typeof window.screenLeft == 'number') ? window.screenLeft : window.screenX;
		    var screeny	=	(typeof window.screenTop == 'number') ? window.screenTop : window.screenY;

		    var width = 870;
		    var topW	=	(screen.height-486)/2+screeny;
		    var leftW	=	(screen.width-width)/2+screenx;

		    var href = $(this).attr('href');
		    href.replace(/popup=[^&]+/i, '');
		    if (href.indexOf('?') >= 0) href = href + '&popup=1';
		    else href= href + '?popup=1';

		    var popup = window.open(href,'ciao','height=485,width='+width+',top='+topW+',left='+leftW);
		    popup.focus();
			return false;
		});
	});
});
</script>
