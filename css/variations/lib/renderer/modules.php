<?php
/**
* @package   yoo_enterprise Template
* @file      modules.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

/*
	Class: JDocumentRendererYOOModules
		Provides custom modules rendering.
*/
class JDocumentRendererYOOModules extends JDocumentRenderer {

	/**
	 * Renders multiple modules script and returns the results as a string
	 */
	function render($position, $params = array(), $content = null) {

		// init vars
		$renderer =& $this->_doc->loadRenderer('module');
		$modules  =& JModuleHelper::getModules($position);
		$count    = count($modules);
		$contents = '';

		foreach ($modules as $index => $module)  {
					
			// set additional params
			$params['count'] = $count;
			$params['order'] = $index + 1;
			$params['first'] = $params['order'] == 1;
			$params['last'] = $params['order'] == $count;

			// get module output
			$output = $renderer->render($module, $params, $content);
			
			// wrap module output
			if (isset($params['wrapper']) && $params['wrapper']) {
				$output = JDocumentRendererYOOModules::getWrapper($output, $params);
			}
			
			$contents .= $output;
		}

		return $contents;
	}

	function getWrapper($content, $params = array()) {

		$layout = JDocumentRendererYOOModules::getLayout(isset($params['layout']) ? $params['layout'] : null);
		$class  = array($params['wrapper']);

		// set width
		if (isset($layout[$params['count']][$params['order'] - 1])) {
			$class[] = $layout[$params['count']][$params['order'] - 1];
		}

		// set separator
		if (!$params['last']) {
			$class[] = 'separator';
		}

		return sprintf('<div class="%s">%s</div>', implode(' ', $class), $content);
	}

	function getLayout($name) {
		
		switch ($name) {
			case 'goldenratio':
				$layout = array(
						1 => array('width100'),
						2 => array('width65', 'width35'),
						3 => array('width54', 'width23', 'width23'),
						4 => array('width45', 'width18', 'width18', 'width18'),
						5 => array('width40', 'width15', 'width15', 'width15', 'width15'));
				break;
			
			default:
				$layout = array(
						1 => array('width100'),
						2 => array('width50', 'width50'),
						3 => array('width33', 'width34', 'width33'),
						4 => array('width25', 'width25', 'width25', 'width25'),
						5 => array('width20', 'width20', 'width20', 'width20', 'width20'));
				break;
		}

		return $layout;
	}

}