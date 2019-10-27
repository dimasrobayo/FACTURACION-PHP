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

// example: default module

?>
<div class="module <?php echo $style; ?> <?php echo $color; ?> <?php echo $yootools; ?> <?php echo $first; ?> <?php echo $last; ?>">

	<?php echo $badge; ?>

	<?php if ($showtitle) : ?>
	<h3 class="header"><?php echo $title; ?></h3>
	<?php endif; ?>
	
	<?php echo $content; ?>
		
</div>