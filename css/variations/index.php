<?php
/**
* @package   yoo_enterprise Template
* @file      index.php
* @version   1.5.0 March 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.mootools');

// include config	
include_once(dirname(__FILE__).'/config.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
<link rel="apple-touch-icon" href="<?php echo $template->url ?>/apple_touch_icon.png" />
</head>

<body id="page" class="yoopage <?php echo $this->params->get('columns'); ?> <?php echo $this->params->get('itemcolor'); ?> <?php echo $this->params->get('toolscolor'); ?>">

	<?php if($this->countModules('absolute')) : ?>
	<div id="absolute">
		<jdoc:include type="yoomodules" name="absolute" />
	</div>
	<?php endif; ?>

	<div id="page-body">
		<div class="wrapper">

			<div id="header">
			
				<div class="header-1">
					<div class="header-2">
						<div class="header-3">
					
							
			
										
						</div>	
					</div>
				</div>

				<div class="header-b1">
					<div class="header-b2">
						<div class="header-b3"></div>
					</div>
				</div>

						
				<div id="logo">
					<a class="logo-icon correct-png" href="http://www.unellez.edu.ve/" title="Principal"></a>
				</div>
				

				
				<div id="menu">
					<?include 'templates/unellez/menu.html'?>
				</div>
				

				<?php if($this->countModules('search')) : ?>
				<div id="search">
					<jdoc:include type="yoomodules" name="search" />
				</div>
				<?php endif; ?>

				<?php if ($this->countModules('banner')) : ?>
				<div id="banner">
					<jdoc:include type="yoomodules" name="banner" />
				</div>
				<?php endif; ?>

			</div>
			<!-- header end -->
			                        <div class="unellez">


   

                            <b><h3>Universidad Nacional Experimental de los Llanos Occidentales "Ezequiel Zamora" UNELLEZ</h3></b>
        

							
  
                        </div>
			<?php if ($this->countModules('breadcrumbs')) : ?>
			<div id="breadcrumbs">
			
				<div class="breadcrumbs-1">
					<div class="breadcrumbs-2">
						
						<jdoc:include type="yoomodules" name="breadcrumbs" />
						
						<?php if($this->params->get('date')) : ?>
						<div id="date">
							<?php echo JHTML::_('date', 'now', JText::_('DATE_FORMAT_LC')) ?>
						</div>
						<?php endif; ?>
							
					</div>
				</div>	
			</div>
			<?php endif; ?>
			

			<?php  if ($this->countModules('top + topblock')) : ?>
			<div id="top">

				<?php if ($this->countModules('top')) : ?>
					<jdoc:include type="yoomodules" name="top" wrapper="topbox float-left" layout="<?php echo $this->params->get('top'); ?>" style="yoo" />
				<?php endif; ?>

				<?php if($this->countModules('topblock')) : ?>
				<div class="topblock width100 float-left">
					<jdoc:include type="yoomodules" name="topblock" style="yoo" />
				</div>
				<?php endif; ?>

			</div>
			<!-- top end -->
			<?php endif; ?>

					
			<div id="middle">
				<div id="middle-expand">

					<div id="main">
						<div id="main-shift">

							<?php if ($this->countModules('maintop')) : ?>
							<div id="maintop">
								<jdoc:include type="yoomodules" name="maintop" wrapper="maintopbox float-left" layout="<?php echo $this->params->get('maintop'); ?>" style="yoo" />									
							</div>
							<!-- maintop end -->
							<?php endif; ?>

							<div id="mainmiddle">
								<div id="mainmiddle-expand">
								
									<div id="content">
										<div id="content-shift">

											<?php if ($this->countModules('contenttop')) : ?>
											<div id="contenttop">
												<jdoc:include type="yoomodules" name="contenttop" wrapper="contenttopbox float-left" layout="<?php echo $this->params->get('contenttop'); ?>" style="yoo" />
											</div>
											<!-- contenttop end -->
											<?php endif; ?>
											
											<div class="wrapper-t1">
												<div class="wrapper-t2">
													<div class="wrapper-t3"></div>
												</div>
											</div>
											
											<div class="wrapper-1">
												<div class="wrapper-2">
													<div class="wrapper-3">
														<jdoc:include type="message" />
														<jdoc:include type="component" />
													</div>
												</div>
											</div>
											
											<div class="wrapper-b1">
												<div class="wrapper-b2">
													<div class="wrapper-b3"></div>
												</div>
											</div>

											<?php if ($this->countModules('contentbottom')) : ?>
											<div id="contentbottom">
												<jdoc:include type="yoomodules" name="contentbottom" wrapper="contentbottombox float-left" layout="<?php echo $this->params->get('contentbottom'); ?>" style="yoo" />
											</div>
											<!-- mainbottom end -->
											<?php endif; ?>
										
										</div>
									</div>
									<!-- content end -->
									
									<?php if($this->countModules('contentleft')) : ?>
									<div id="contentleft">
										<jdoc:include type="yoomodules" name="contentleft" style="yoo" />
									</div>
									<?php endif; ?>
									
									<?php if($this->countModules('contentright')) : ?>
									<div id="contentright">
										<jdoc:include type="yoomodules" name="contentright" style="yoo" />
									</div>
									<?php endif; ?>
									
								</div>
							</div>
							<!-- mainmiddle end -->

							<?php if ($this->countModules('mainbottom')) : ?>
							<div id="mainbottom">
								<jdoc:include type="yoomodules" name="mainbottom" wrapper="mainbottombox float-left" layout="<?php echo $this->params->get('mainbottom'); ?>" style="yoo" />
							</div>
							<!-- mainbottom end -->
							<?php endif; ?>
						
						</div>
					</div>
					
					<?php if($this->countModules('left')) : ?>
					<div id="left">
						<jdoc:include type="yoomodules" name="left" style="yoo" />
					</div>
					<?php endif; ?>
					
					<?php if($this->countModules('right')) : ?>
					<div id="right">
						<jdoc:include type="yoomodules" name="right" style="yoo" />
					</div>
					<?php endif; ?>

				</div>
			</div>
					

			<?php if ($this->countModules('bottom + bottomblock')) : ?>
			<div id="bottom">
			
				<?php if ($this->countModules('bottom')) : ?>
					<jdoc:include type="yoomodules" name="bottom" wrapper="bottombox float-left" layout="<?php echo $this->params->get('bottom'); ?>" style="yoo" />
				<?php endif; ?>
				
				<?php if($this->countModules('bottomblock')) : ?>
				<div class="bottomblock width100 float-left">
					<jdoc:include type="yoomodules" name="bottomblock" style="yoo" />
				</div>
				<?php endif; ?>
				
			</div>
			<!-- bottom end -->
			<?php endif; ?>


			<?php if ($this->countModules('footer + debug')) : ?>
			<div id="footer">

				<a class="anchor" href="#page"></a>
				<jdoc:include type="yoomodules" name="footer" />
				<jdoc:include type="yoomodules" name="debug" />
				
			</div>
			<!-- footer end -->
			<?php endif; ?>

		</div>
	</div>

</body>
</html>
