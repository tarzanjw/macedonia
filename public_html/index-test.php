<?php

echo '<pre>',print_r($_SERVER, true),'</pre>';
die;

require '../protected/extensions/common/helpers/RestHelper.php';

echo '<pre>',print_r($_GET, true),'</pre>';

$data = array(
	'ab'=>'c d',
	'arr'=>array(
		'subArr'=>array(
			'item0','item1',
		),
		'def'=>'atk',
		2=>'x',
	),
);

echo '<pre>',print_r(http_build_query($data), true),'</pre>';

echo '<pre>',print_r(RestHelper::normalizeArrayToString($data), true),'</pre>';