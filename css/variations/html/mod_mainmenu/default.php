<?php
/**
* @package   yoo_enterprise Template
* @file      default.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// include YOOmenu system
require_once(dirname(__FILE__).'/yoomenu.php');

// render YOOmenu
$yoomenu = &YOOMenu::getInstance();
$yoomenu->setParams($params);
$yoomenu->render($params, 'YOOMenuDefaultDecorator');

?>