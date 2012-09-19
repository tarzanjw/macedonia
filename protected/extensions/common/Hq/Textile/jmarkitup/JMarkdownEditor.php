<?php
/**
 * JMarkdownEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This widget create a markdown editor based on markitUp editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

Yii::setPathOfAlias('JMarkdownEditor',dirname(__FILE__));
Yii::import('JMarkdownEditor.JMarkitUpEditor');

class JMarkdownEditor extends JMarkitUpEditor
{
	/**
	 * This method initialize the widget.
	 */
	public function init()
	{
        $this->markupSet='markdown';
		parent::init();
	}
}
