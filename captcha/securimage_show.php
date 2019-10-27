<?php
	include 'securimage.php';
	
	$img = new securimage();
	$img->perturbation = 0.1;
	
	$img->code_length = rand(3,4);
	$img->image_bg_color = new Securimage_Color("#FFFFFF");
	$img->text_color = new Securimage_Color("#000000");	
	$img->show('../images/captcha/bg4.jpg'); // alternate use:  $img->show('/path/to/background_image.jpg');
?>
