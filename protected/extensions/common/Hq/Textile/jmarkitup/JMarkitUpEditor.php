<?php
/**
 * JMarkitUpEditor class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @license BSD
 */

/**
 * This class is the base for editors based on markitUp! editor
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

abstract class JMarkitUpEditor extends CInputWidget
{
    /**
     * URL where to look for markItUp assets
     * @var string
     */
	public $scriptUrl;

    /**
     * markItUp! script name
     * Defaults to jquery.markitup.js
     * @var string
     */
	public $scriptFile;

    /**
     * URL where to look for a skin
     * @var string
     */
	public $themeUrl;

    /**
     * markItUp! skin name
     * Default to "simple"
     * @var string
     */
	public $theme="simple";

    /**
     * URL where to look for a tag set
     * @var string
     */
	public $settingsUrl;


    /**
     * markItUp options
     * @see http://markitup.jaysalvat.com/documentation/
     * @var array
     */
	public $options=array();

    /**
     * Name of markup sets
     * @var string
     */
	protected $markupSet;

    /**
     * special seetings for an editor
     * @var array
     */
	protected $mySettings = array();



	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the textarea.
	 */
	public function init()
	{
		list($name,$id)=$this->resolveNameId();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->scriptUrl===null || $this->themeUrl===null || $this->settingsUrl===null)
		{
			if($this->scriptUrl===null)
				$this->scriptUrl=CHtml::asset(dirname(__FILE__).'/assets');

			if($this->themeUrl===null)
				$this->themeUrl=$this->scriptUrl.'/skins';

			if($this->settingsUrl===null)
				$this->settingsUrl=$this->scriptUrl.'/sets';
		}

		if($this->scriptFile===null)
			$this->scriptFile='jquery.markitup.js';

		$this->registerClientScript();

		if($this->hasModel())
			echo CHtml::activeTextArea($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textArea($name,$this->value,$this->htmlOptions);
	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	public function registerClientScript()
	{
		$id=$this->htmlOptions['id'];

		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->scriptUrl.'/'.$this->scriptFile);
		$cs->registerScriptFile($this->settingsUrl.'/'.$this->markupSet.'/set.js');

        $acOptions=$this->getClientOptions();
		$options=$acOptions===array()?'' : CJavaScript::encode($acOptions);

		if($options)
            $cs->registerScript(__CLASS__.'#'.$id, "jQuery('#$id').markItUp(mySettings,$options);");
		else
            $cs->registerScript(__CLASS__.'#'.$id, "jQuery('#$id').markItUp(mySettings);");

		$cs->registerCssFile($this->themeUrl.'/'.$this->theme.'/style.css');
		$cs->registerCssFile($this->settingsUrl.'/'.$this->markupSet.'/style.css');
	}

	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		static $properties=array(
			'id',
            'nameSpace',
            'root',
            'previewInWindow',
            'previewAutoRefresh',
			'previewPosition',
            'previewTemplatePath',
            'previewParserPath',
            'previewParserVar',
			'resizeHandle',
            'markupSet');

		static $functions=array(
            'beforeInsert',
            'afterInsert',
            'onEnter',
            'onShiftEnter',
            'onCtrlEnter',
            'onTab');

        $options=array();
		foreach($properties as $property)
		{
            if (array_key_exists($property, $this->options))
                if($this->options[$property]!==null)
                    $options[$property]=$this->options[$property];
		}
		foreach($functions as $func)
		{
            if (array_key_exists($func, $this->options))
                if(is_string($this->options[$func]) && strncmp($this->options[$func],'js:',3))
                    $options[$func]='js:'.$this->$func;
		}

		return $options;
	}

    protected function registerAddon(){

    }
}
