<?php
	class Pixel {
    	function Pixel($r, $g, $b){
        	$this->r = ($r > 255) ? 255 : (($r < 0) ? 0 : (int)($r));
        	$this->g = ($g > 255) ? 255 : (($g < 0) ? 0 : (int)($g));
        	$this->b = ($b > 255) ? 255 : (($b < 0) ? 0 : (int)($b));
    	}
	}
	class Image_PixelOperations {

	    function pixelOperation($input_image, $output_image, $operation_callback, $color_array = false)
	    {
	        $image = imagecreatefromjpeg($input_image);
	        $x_dimension = imagesx($image);
	        $y_dimension = imagesy($image);
	        $new_image = imagecreatetruecolor($x_dimension, $y_dimension);

	        if ($operation_callback == 'contrast') {
	            $average_luminance = $this->getAverageLuminance($image);
	        } 
	        else {
	            $average_luminance = false;
	        }

	        for ($x = 0; $x < $x_dimension; $x++) {
	            for ($y = 0; $y < $y_dimension; $y++) {

	                $rgb = imagecolorat($image, $x, $y);
	                $r = ($rgb >> 16) & 0xFF;
	                $g = ($rgb >> 8) & 0xFF;
	                $b = $rgb & 0xFF;

	                $pixel = new Pixel($r, $g, $b);
	                $pixel = call_user_func(
	                    $operation_callback,
	                    $pixel,
	                    $color_array,
	                    $average_luminance
	                );

	                $color = imagecolorallocate(
	                    $image,
	                    $pixel->r,
	                    $pixel->g,
	                    $pixel->b
	                );
	                imagesetpixel($new_image, $x, $y, $color);
	            }

	        }

	        imagejpeg($new_image, $output_image);
	    }

	    function removeGreen($pixel){
	    	$pixel->g = 0;
	    	return $pixel;
	    }

	    function palletify($pixel, $color_array){
	    	$pixel_sum = $pixel->r + $pixel->g + $pixel->b;
	    	if(is_array($color_array)){
		    	$step = (765 / sizeof($color_array)) + 1;
		    	$index = $pixel_sum / $step;

		    	$color = $color_array[$index];
		    	$red = ($color >> 16) & 0xFF;
		    	$green = ($color >> 8) & 0xFF;
		    	$blue = $color & 0xFF;

		    	$pixel->r = $red;
		    	$pixel->g = $green;
		    	$pixel->b = $blue;
		    }
	    	return $pixel;
	    }
	}
?>

<?php
$color_array = array(0x000000, 0x8219e3, 0xAAAA03, 0x3a4b3f, 0xFFFFFF);
$po = new Image_PixelOperations();
$po->pixelOperation('images/juanita.jpg', 'images/new_juanita.jpg', array($po, 'palletify'), $color_array);
echo '<img src="images/juanita.jpg"/><img src="images/new_juanita.jpg"/>';
?>