<?php

require_once dirname(__FILE__).'/jmarkitup/JTextileEditor.php';

class TextilePreviewAction extends CAction
{
    function renderPreviewHtml($html)
    {
        return <<<HTML
<html>
<head>
</head>
<body style="background-color:#EFEFEF;font:70% Verdana, Arial, Helvetica, sans-serif;">
{$html}
</body>
</html>
HTML;
    }

	function run()
	{
        if (!isset($_POST['data'])) throw new CHttpException(400);

        require_once dirname(__FILE__).'/Textile.php';

        $html = Textile::parse($_POST['data']);

        echo $this->renderPreviewHtml($html);
	}
}
