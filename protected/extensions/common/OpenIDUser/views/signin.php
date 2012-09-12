<?php
	$this->pageHeader = '';

	$ru = $this->getReturnUrl();
	$providers = array(
		array(
			'name'=>'Bảo Kim',
			'logo'=>$this->assetsUrl.'/images/bk_logo.png',
			'link'=>$this->createUrl('', array('p'=>'baokim','ru'=>$ru)),
		),
		array(
			'name'=>'Google',
			'logo'=>$this->assetsUrl.'/images/google_logo.jpg',
			'link'=>$this->createUrl('', array('p'=>'google','ru'=>$ru)),
		),
		array(
			'name'=>'Yahoo',
			'logo'=>$this->assetsUrl.'/images/yahoo_logo.png',
			'link'=>$this->createUrl('', array('p'=>'yahoo','ru'=>$ru)),
		),
	);
?>

<div class="hero-unit">
	<h2>Choose your OpenID Providers</h2>
    <br>

    <?php foreach ($providers as $p): ?>
    <a href="<?php echo $p['link']; ?>">
    	<img src="<?php echo $p['logo']; ?>" alt="<?php echo $p['name']; ?>">
    </a>
    <?php endforeach; ?>
</div>