<?php
/**
* @package   yoo_enterprise Template
* @file      template.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

/*
	Class: YOOTemplate
		Provides advanced templating functions.
*/
class YOOTemplate {

	/* document */
	var $document;

	/* base url */
	var $url;

	/* parameters */
	var $params;

	/* browser */
	var $browser = array();

	function YOOTemplate() {

		// set params
		$this->params = new YOOTemplateParameter();
		$this->params->loadFile(dirname(dirname(dirname(__FILE__))).'/params.ini');
		$this->params->loadPageSuffix();
		
		// ie browser
		if (array_key_exists('HTTP_USER_AGENT', $_SERVER) && preg_match('/(MSIE\\s?([\\d\\.]+))/', $_SERVER['HTTP_USER_AGENT'], $matches)) {
			$this->browser['ie7'] = intval($matches[2]) == 7;
			$this->browser['ie6'] = intval($matches[2]) == 6;
		}		
	}

	function &getInstance() {
		static $instance;

		if ($instance == null) {
			$instance = new YOOTemplate();
		}
		
		return $instance;
	}

	function setDocument(&$document) {
		$this->document =& $document;
		$this->url = $document->baseurl.'/templates/'.$document->template;
	}

	/* CSS */

	function getCSS() {

		/* wrapper */
		$css = '.wrapper { width: '.intval($this->params->get('template_width')).$this->params->get('template_width_unit')."; }\n";
		
		/* 3-column-layout */
		if ($this->document->countModules('left')) {
			$css .= '#main-shift { margin-left: '.(intval($this->params->get('left_width')) + intval($this->params->get('left_space')))."px; }\n";
			$css .= '#left { width: '.intval($this->params->get('left_width'))."px; }\n";
		}
		
		if ($this->document->countModules('right')) {
			if (!$this->document->countModules('left')) { $this->params->set('left_width', '0'); }
			$css .= '#main-shift { margin-right: '.(intval($this->params->get('right_width')) + intval($this->params->get('right_space')))."px; }\n";
			$css .= '#right { width: '.intval($this->params->get('right_width')).'px; margin-left: -'.(intval($this->params->get('left_width')) + intval($this->params->get('right_width')))."px; }\n";
		}
		
		/* inner 3-column-layout */
		if ($this->document->countModules('contentleft')) {
			$css .= '#content-shift { margin-left: '.(intval($this->params->get('contentleft_width')) + intval($this->params->get('contentleft_space')))."px; }\n";
			$css .= '#contentleft { width: '.intval($this->params->get('contentleft_width'))."px; }\n";
		}		
		
		if ($this->document->countModules('contentright')) {
			if (!$this->document->countModules('contentleft')) { $this->params->set('contentleft_width', '0'); }
			$css .= '#content-shift { margin-right: '.(intval($this->params->get('contentright_width')) + intval($this->params->get('contentright_space')))."px; }\n";
			$css .= '#contentright { width: '.intval($this->params->get('contentright_width')).'px; margin-left: -'.(intval($this->params->get('contentleft_width')) + intval($this->params->get('contentright_width')))."px; }\n";
		}

		/* drop down menu */
		$css .= '#menu .dropdown { width: '.intval($this->params->get('menu_width'))."px; }\n";
		$css .= '#menu .columns2 { width: '.(2*intval($this->params->get('menu_width')))."px; }\n";
		$css .= '#menu .columns3 { width: '.(3*intval($this->params->get('menu_width')))."px; }\n";
		$css .= '#menu .columns4 { width: '.(4*intval($this->params->get('menu_width')))."px; }\n";

		return $css;
	}

	/* Javascript */

	function getJavaScript() { 
		
		$script = '';
		$separator = false;
		$params = array('tplurl' => "'$this->url'");

		if ($color = $this->document->params->get('color')) {
			$params['color'] = "'$color'";
		}

		if ($itemcolor = $this->document->params->get('itemcolor')) {
			$params['itemColor'] = "'$itemcolor'";
		}
		
		foreach ($params as $name => $value) {
			$separator ? $script .= ', ' : $separator = true;			
			$script .= $name.': '.$value;
		}

		return 'var YtSettings = { '.$script.' };';
	}
			
	function replaceMootools() { 
		$headdata = $this->document->getHeadData();
		$headdata['scripts'] = array_merge(array($this->url.'/lib/js/mootools.js' => 'text/javascript'), $headdata['scripts']);
		unset($headdata['scripts'][$this->document->baseurl.'/media/system/js/mootools.js']);
		$this->document->setHeadData($headdata);
	}
	
	/* Colorswitcher */
	
	function getCurrentColor() {

		if ($color = JRequest::getVar('yt_color', null, 'default', 'alnum')) {
			setcookie('ytcolor', $color, time() + 3600, '/');
			return $color;
		}
		
		return JRequest::getVar('ytcolor', $this->params->get('color'), 'cookie', 'alnum');
	}

	/* Browser */

	function isIe($version) {
		if (array_key_exists('ie'.$version, $this->browser)) {
			return $this->browser['ie'.$version];
		}
		return false;
	}

}

/*
	Class: YOOTemplateParameter
		Template parameter.
*/
class YOOTemplateParameter {

	/* parameters */
	var $params = array();

	function YOOTemplateParameter($data = null) {
		$this->parseString($data);
	}

	function get($key, $default = '') {

		if (array_key_exists($key, $this->params)) {
			return $this->params[$key];
		}

		return $default;
	}

	function set($key, $value) {
		$this->params[$key] = $value;
	}

	function toArray() {
		return $this->params;
	}

	function loadFile($file) {

		// read parameters from file
		if (is_file($file)) {
			$handle = fopen($file, 'r');
			if ($handle !== false) {
				while ($l = fgets($handle)) {
					if (preg_match('/^#/', $l) == false) {
						if (preg_match('/^(.*?)=(.*?)$/', $l, $regs)) {
							$this->params[$regs[1]] = $regs[2];
						}
					}
				}
				@fclose($handle);
			}
		}
	}

	function loadPageSuffix() {
		global $mainframe;

		// read parameters from joomla page class suffix
		if (isset($mainframe)) {
			$params =& $mainframe->getParams();
			$this->parseString($params->get('pageclass_sfx'));
		}
	}

	function parseString($string) {
		$parts = preg_split('/[\s]+/', $string);

		foreach ($parts as $part) {
			if (strpos($part, '-') !== false) {
				list($key, $value) = explode('-', $part, 2);
				$this->params[$key] = $value;
			}
		}
	}

}