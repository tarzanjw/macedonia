<?php
/**
 * JTextileEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This widget create a textile editor based on markitUp editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

Yii::setPathOfAlias('JTextileEditor',dirname(__FILE__));
Yii::import('JTextileEditor.JMarkitUpEditor');

class JTextileEditor extends JMarkitUpEditor
{
	/**
	 * This method initialize the widget.
	 */
	public function init()
	{
        $this->markupSet='textile';
		parent::init();
	}

}
