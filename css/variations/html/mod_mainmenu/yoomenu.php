<?php
/**
* @package   yoo_enterprise Template
* @file      yoomenu.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// include template
require_once(dirname(dirname(dirname(__FILE__))).'/lib/php/template.php');

// include default decorator
require_once(dirname(__FILE__).'/decorator/default.php');

jimport('joomla.filesystem.file');
		
class YOOMenu {

	var $params;

	function YOOMenu() {
		$this->params = null;
	}

	function &getInstance() {
		static $instance;

		if ($instance == null) {
			$instance = new YOOMenu();
		}
		
		return $instance;
	}

	function getParams() {
		return $this->params;
	}

	function setParams(&$val) {
		return $this->params = $val;
	}

	function render(&$params, $callback) {

		$suffix = $params->get('class_sfx');
		
		// compatibility for suffix like 'accordion' and 'dropdown' without 'menu-'
		if (!strpos($suffix, '-')) {
			$suffix = 'menu-'.$suffix;
		}
		
		// use custom menu output
		$param = new YOOTemplateParameter($suffix);
		$menu  = strtolower($param->get('menu'));
		if (in_array($menu, array('accordion', 'dropdown'))) {
			
			// include decorator
			require_once(dirname(__FILE__)."/decorator/$menu.php");				

			// generate menu output
			$xml = modMainMenuHelper::getXML($params->get('menutype'), $params, 'YOOMenu'.ucfirst($menu).'Decorator');
			
			if ($xml) {
				$xml->addAttribute('class', 'menu '.$suffix);

				if ($tagId = $params->get('tag_id')) {
					$xml->addAttribute('id', $tagId);
				}

				$result = JFilterOutput::ampReplace(YOOMenuXMLHelper::XMLtoString($xml, (bool) $params->get('show_whitespace')));
				$result = str_replace(array('<ul/>', '<ul />'), '', $result);
				echo $result;
			}

			return;
		}

		// use default joomla menu output
		$params->set('class_sfx', ' '.$suffix);
		modMainMenuHelper::render($params, $callback);
	}

}

class YOOMenuXMLHelper {

	function addChild(&$node, &$child) {
		$name = $child->name();

		if (!isset($node->$name)) {
			$node->$name = array();
		}

		$child->_level = $node->level() + 1;
		$node->{$name}[] =& $child;
		$node->_children[] =& $child;
	}

	function wrapChildren(&$node, &$wrapper, $tag = null) {
		
		$names = array();
		
		// copy children
		foreach ($node->_children as $i => $child) {
			$name = $child->name();

			if ($tag != null && $tag != $name) {
				continue;
			}

			if (!in_array($name, $names)) {
				$names[] = $name;
			}

			if (!isset($wrapper->$name)) {
				$wrapper->$name = array();
			}

			$wrapper->{$name}[] =& $node->_children[$i];
			$wrapper->_children[] =& $node->_children[$i];
			unset($node->_children[$i]);
		}

		// update node
		foreach ($names as $name) {
			unset($node->{$name});
		}

		// add wrapper to node
		$wrapper->_level = $node->level() + 1;
		$name = $wrapper->name();
		if (!isset($node->$name)) {
			$node->$name = array();
		}
		$node->{$name}[] =& $wrapper;
		$node->_children = array_merge($node->_children, array($wrapper));	
	}

	function XMLtoString(&$node, $whitespace = true) {

		// Start a new line, indent by the number indicated in $node->level, add a <, and add the name of the tag
		$out = $whitespace ? "\n".str_repeat("\t", $node->_level) : '';

		// Output prefix
		if (isset($node->_prefix)) {
			$out .= $node->_prefix;
		}

		// Start tag
		$out .= '<'.$node->_name;

		// For each attribute, add attr="value"
		foreach($node->_attributes as $attr => $value) {
			$out .= ' '.$attr.'="'.htmlspecialchars($value).'"';
		}

		// If there are no children and it contains no data, end it off with a />
		if (empty($node->_children) && empty($node->_data)) {
			$out .= " />";
		} else {

			// If there are children
			if(!empty($node->_children)) {

				// Close off the start tag
				$out .= '>';

				// For each child, call the asXML function (this will ensure that all children are added recursively)
				foreach($node->_children as $child)
					$out .= YOOMenuXMLHelper::XMLtoString($child, $whitespace);

				// Add the newline and indentation to go along with the close tag
				if ($whitespace) {
					$out .= "\n".str_repeat("\t", $node->_level);
				}
			}

			// If there is data, close off the start tag and add the data
			elseif(!empty($node->_data))
				$out .= '>'.htmlspecialchars($node->_data);

			// Add the end tag
			$out .= '</'.$node->_name.'>';
		}

		// Output suffix
		if (isset($node->_suffix)) {
			$out .= $node->_suffix;
		}

		// Return the final output
		return $out;
	}

}
