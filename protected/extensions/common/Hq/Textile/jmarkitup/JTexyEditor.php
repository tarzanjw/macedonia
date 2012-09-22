<?php
/**
 * JTexyEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This widget create a Texy editor based on markitUp editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

Yii::setPathOfAlias('JTexyEditor',dirname(__FILE__));
Yii::import('JTexyEditor.JMarkitUpEditor');

class JTexyEditor extends JMarkitUpEditor
{

	/**
	 * This method initialize the widget.
	 */
	public function init()
	{
        $this->markupSet='texy';
		parent::init();
	}

}
