<?php

return array(
	'email.verify.from'=>'Tài khoản Vật Giá <noreply@vatgia.com>',
	'email.verify.to'=>'{first_name} {last_name} <{email}>',
	'email.verify.subject'=>'Kích hoạt tài khoản Vật Giá',
	'email.verify.content'=><<<HTML
Chào bạn {first_name} {last_name},

Bạn vừa tạo mới một tài khoản Vật Giá, bạn hãy click vào link dưới đây để kích hoạt tài khoản
<a href="{verify_link}">{verify_link}</a>
HTML
	,

	'sms.verify.to'=>'{phone}',
	'sms.verify.content'=>'Chuc mung ban da tao moi tai khoan VAT GIA thanh cong. Ma kich hoat tai khoan cua ban la {activation_code}.',
);