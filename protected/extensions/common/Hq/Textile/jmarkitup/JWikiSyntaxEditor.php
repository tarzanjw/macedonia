<?php
/**
 * JWikiSyntaxEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This widget create a wiki syntax editor based on markitUp editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

Yii::setPathOfAlias('JWikiSyntaxEditor',dirname(__FILE__));
Yii::import('JWikiSyntaxEditor.JMarkitUpEditor');

class JWikiSyntaxEditor extends JMarkitUpEditor
{

	/**
	 * This method initialize the widget.
	 */
	public function init()
	{
        $this->markupSet='wiki';
		parent::init();
	}

}
