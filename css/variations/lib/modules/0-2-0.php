<?php
/**
* @package   yoo_enterprise Template
* @file      0-2-0.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// example: angled module with border

?>
<div class="module <?php echo $style; ?> <?php echo $color; ?> <?php echo $yootools; ?> <?php echo $first; ?> <?php echo $last; ?>">

	<?php echo $badge; ?>
	
	<div class="box-1">
		<div class="box-2 deepest">
		
			<?php if ($showtitle) : ?>
			<h3 class="header"><span class="header-2"><span class="header-3"><?php echo $title; ?></span></span></h3>
			<?php endif; ?>
			
			<?php echo $content; ?>
			
		</div>
	</div>
		
</div>