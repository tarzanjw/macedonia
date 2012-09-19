<?php
/**
 * JBBCodeEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This widget create a bbcode editor based on markitUp editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

Yii::setPathOfAlias('JBBCodeEditor',dirname(__FILE__));
Yii::import('JBBCodeEditor.JMarkitUpEditor');

class JBBCodeEditor extends JMarkitUpEditor
{

	/**
	 * This method initialize the widget.
	 */
	public function init()
	{
        $this->markupSet='bbcode';
		parent::init();
	}

}
