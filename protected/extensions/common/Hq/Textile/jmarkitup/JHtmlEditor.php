<?php
/**
 * JHtmlEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This widget create a html editor based on markitUp editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

Yii::setPathOfAlias('JHtmlEditor',dirname(__FILE__));
Yii::import('JHtmlEditor.JMarkitUpEditor');

class JHtmlEditor extends JMarkitUpEditor
{

	/**
	 * This method initialize the widget.
	 */
	public function init()
	{
        $this->markupSet='html';
		parent::init();
	}

}
