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
				ini_set("gd.jpeg_ignore_warning", 1);
	        $image = imagecreatefromjpeg($input_image);
					if(!$image){
						$image= imagecreatefromstring(file_get_contents($input_image));
					}
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
  session_start();
  require_once "config.php";
  $msg = "";

  if(isset($_POST['submit'])){

    $image = $_FILES['photoUpload']['name'];
    $target = "uploads/".basename($image);
    $color1 = hexdec("0x" . $_POST['color1']);
    $color2 = hexdec("0x" . $_POST['color2']);
    $color3 = hexdec("0x" . $_POST['color3']);
    $color4 = hexdec("0x" . $_POST['color4']);

    $id = $_SESSION["id"];
    $sql= "INSERT INTO images (image, userid) VALUES ('$image', '$id')";

    mysqli_query($mysqli, $sql);

    $newid = mysqli_query($mysqli, "SELECT MAX(id) FROM images2");

    $newid2 = $newid->fetch_row()[0] + 1;
    $newImagePath = $newid2 . $image;

    $sql2 ="INSERT INTO images2 (id, image, userid) VALUES ('$newid2', '$newImagePath', '$id')";
    mysqli_query($mysqli, $sql2);

    if(move_uploaded_file($_FILES['photoUpload']['tmp_name'], $target)){
      $msg = "image uploaded";
    }
    else{
      $msg = "failed to upload";
    }


  }
  $result = mysqli_query($mysqli, "SELECT * FROM images")

 ?>
 <?php
  //  while ($row = mysqli_fetch_array($result)) {
    //  echo "<div id='img_div'>";
      //	echo "<img src='uploads/".$row['image']."' >";
    //  echo "</div>";
    //}
  ?>

<?php
$color_array = array($color1, $color2, $color3, $color4);
$po = new Image_PixelOperations();
$newPath = "modified/".$newImagePath;
$po->pixelOperation($target, $newPath, array($po, 'palletify'), $color_array);
?>
<html>
<body onload="document.forms['photos'].submit()">
  <form action = "result.php" method ="GET" name="photos">
		<input type="hidden" name="oldpath" value="<?php echo $image; ?>"/>
		<input type="hidden" name="newpath" value="<?php echo $newImagePath; ?>"/>
		<input type="hidden" name="c1" value ="<?php echo $_POST['color1']; ?>"/>
		<input type="hidden" name="c2" value ="<?php echo $_POST['color2']; ?>"/>
		<input type="hidden" name="c3" value ="<?php echo $_POST['color3']; ?>"/>
		<input type="hidden" name="c4" value ="<?php echo $_POST['color4']; ?>"/>
		<input type="submit" value="click"/>
</body>
</html>
