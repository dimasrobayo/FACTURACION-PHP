<?php
/**
* @package   yoo_enterprise Template
* @file      dropdown.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

function YOOMenuDropdownDecorator(&$node, $args) {

	$yoomenu  = &YOOMenu::getInstance();
	$params   = $yoomenu->getParams();
	$user     = &JFactory::getUser();
	$menu     = &JSite::getMenu();
	$active   = $menu->getActive();
	$path     = isset($active) ? array_reverse($active->tree) : null;

	// remove child items deeper than end level
	if (($args['end']) && ($node->attributes('level') >= $args['end'])) {
		$children = $node->children();
		foreach ($node->children() as $child) {
			if ($child->name() == 'ul') {
				$node->removeChild($child);
			}
		}
	}

	if ($node->name() == 'ul') {
		// remove inaccessable items according to user rights
		foreach ($node->children() as $child) {
			if ($child->attributes('access') > $user->get('aid', 0)) {
				$node->removeChild($child);
			}
		}
		
		// set order/first/last for li
		$count = count($node->children());
		foreach ($node->children() as $i => $child) {
			if ($i == 0) $child->addAttribute('first', 1);
			if ($i == $count - 1) $child->addAttribute('last', 1);
			$child->addAttribute('order', $i + 1);
		}
		
		// set ul level
		if (isset($node->_children[0])) {
			$level = $node->_children[0]->attributes('level') - $params->get('startLevel');
			$css = 'level' . $level;
			if ($node->attributes('first')) $css .= ' first';
			if ($node->attributes('last')) $css .= ' last';
			$node->attributes('class') ? $node->addAttribute('class', $node->attributes('class') . ' ' . $css) : $node->addAttribute('class', $css);
		}
	}

	// set item styling
	if ($node->name() == 'li') {

		$item        = $menu->getItem($node->attributes('id'));
		$item_params = new JParameter($item->params);
		$sfx_params  = new YOOTemplateParameter($params->get('class_sfx'));	
		$page_params = new YOOTemplateParameter($item_params->get('pageclass_sfx'));	
		$level       = $node->attributes('level') - $params->get('startLevel');
		$images      = $sfx_params->get('images') != 'off';
		$color       = $page_params->get('itemcolor', '');
		$columns     = (int) $page_params->get('columns', 1);
		$width       = (int) $page_params->get('columnwidth');
		$css         = 'level' . $level . ' item' . $node->attributes('order');
		$span_css    = '';

		if ($color) $css .= ' '.$color;
		if ($node->attributes('first')) $css .= ' first';
		if ($node->attributes('last')) $css .= ' last';
		if (isset($node->ul) && ($args['end'] == 0 || $node->attributes('level') < $args['end'])) $css .= ' parent';
		if (isset($path) && in_array($node->attributes('id'), $path)) $css .= ' active';
		if (isset($path) && $node->attributes('id') == $path[0]) $css .= ' current';
		if ($item->type == 'separator') $css .= ' separator';

		// add a/span css classes
		if (isset($node->_children[0])) {
			$node->_children[0]->attributes('class') ? $node->_children[0]->addAttribute('class', $node->_children[0]->attributes('class') . ' ' . $css) : $node->_children[0]->addAttribute('class', $css);
		}

		// add item css classes
		$node->attributes('class') ? $node->addAttribute('class', $node->attributes('class') . ' ' . $css) : $node->addAttribute('class', $css);

		// add item background image
		if ($item_params->get('menu_image') && $item_params->get('menu_image') != -1) {
			if (isset($node->_children[0])) {
				if ($images && isset($node->_children[0]->span[0])) {
					$img = 'images/stories/'.$item_params->get('menu_image');
					$node->_children[0]->span[0]->addAttribute('style', 'background-image: url('.JURI::base().$img.');');
					$span_css .= 'icon';
				}
				if ($img = $node->_children[0]->getElementByPath('img')) {
					$node->_children[0]->removeChild($img); // remove old item image
				}
			}
		}

		// add span css and subtitle span
		if (isset($node->_children[0]) && isset($node->_children[0]->span[0])) {
			$node->_children[0]->span[0]->addAttribute('class', 'bg '.$span_css);
			$title = $node->_children[0]->span[0];
			$split = explode('||', $title->data(), 2);
			if (count($split) == 2) {
				$span =& $node->_children[0]->span[0]->addChild('span', array('class' => 'title'));
				$span->setData(trim($split[0]));
				$span =& $node->_children[0]->span[0]->addChild('span', array('class' => 'subtitle'));
				$span->setData(trim($split[1]));
			}
		}

		// dropdown remove inaccessable items according to user rights
		if (isset($node->ul[0])) {
			foreach ($node->ul[0]->children() as $child) {
				if ($child->attributes('access') > $user->get('aid', 0)) {
					$node->ul[0]->removeChild($child);
				}
			}
			if (count($node->ul[0]->children()) == 0) {
				unset($node->ul);
			}
		}

		// dropdown multi-columns
		if (isset($node->ul[0]) && $level == 1) {
			$remove = array();
			$rows   = ceil(count($node->ul[0]->_children) / $columns);		
			foreach ($node->ul[0]->_children as $i => $child) {
				$column = intval($i / $rows);
				$attribute = 'col'.($column + 1);
				if ($i == 0) {
					$node->ul[0]->addAttribute('class', $attribute);
				}
				if ($column > 0) {
					if (!isset($node->ul[$column])) {
						$node->addChild('ul', array('class' => $attribute));
					}
					YOOMenuXMLHelper::addChild($node->ul[$column], $node->ul[0]->_children[$i]);
					$remove[] =& $node->ul[0]->_children[$i];
				}
			}
			foreach ($remove as $i => $child) {
				$node->ul[0]->removeChild($remove[$i]);
			}
		}
		
		// dropdown level 1 wrapper
		if (isset($node->ul[0]) && $level == 1) {

			// count ul
			$count = count($node->ul);

			// set first/last for ul
			$node->ul[0]->addAttribute('first', 1);
			$node->ul[$count - 1]->addAttribute('last', 1);

			// wrap ul
			$style = $width ? ' style="width:'.($count * $width).'px;"' : null;
			$div = new JSimpleXMLElement('div', array('class' => 'dropdown-3'));
			$div->_prefix = '<div class="dropdown columns'.$count.'"'.$style.'><div class="dropdown-t1"><div class="dropdown-t2"><div class="dropdown-t3"></div></div></div><div class="dropdown-1"><div class="dropdown-2">';
			$div->_suffix = '</div></div><div class="dropdown-b1"><div class="dropdown-b2"><div class="dropdown-b3"></div></div></div></div>';
			YOOMenuXMLHelper::wrapChildren($node, $div, 'ul');
		}
		
		// dropdown level 2 wrapper
		if (isset($node->_children[0]) && $level == 2) {

			// wrap link (span/a)
			$link = isset($node->a) ? 'a' : 'span';
			$div = new JSimpleXMLElement('div', array('class' => 'hover-box4'));
			$div->_prefix = '<div class="hover-box1"><div class="hover-box2"><div class="hover-box3">';
			$div->_suffix = '</div></div></div>';
			YOOMenuXMLHelper::wrapChildren($node, $div, $link);

			// wrap ul
			if (isset($node->ul) && count($node->ul)) {
				$div = new JSimpleXMLElement('div', array('class' => 'sub'));
				YOOMenuXMLHelper::wrapChildren($node, $div, 'ul');
			}

			// wrap all 
			$div = new JSimpleXMLElement('div', array('class' => 'group-box5'));
			$div->_prefix = '<div class="group-box1"><div class="group-box2"><div class="group-box3"><div class="group-box4">';
			$div->_suffix = '</div></div></div></div>';
			YOOMenuXMLHelper::wrapChildren($node, $div);
		}				
	}
	
	// remove inactive child items except for accordion
	if (!(isset($path) && in_array($node->attributes('id'), $path))) {
		if (isset($args['children']) && !$args['children'])	{
			$children = $node->children();
			foreach ($node->children() as $child) {
				if ($child->name() == 'ul') {
					$node->removeChild($child);
				}
			}
		}
	}

	$node->removeAttribute('id');
	$node->removeAttribute('rel');
	$node->removeAttribute('level');
	$node->removeAttribute('access');
	$node->removeAttribute('order');
	$node->removeAttribute('first');
	$node->removeAttribute('last');
}
