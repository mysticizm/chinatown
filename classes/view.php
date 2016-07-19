<?php
class View
{
    private $view_name;
	private $header;
	private $footer;
	public $variables;
	public $menuItem;
	public $lang;
	
	function __construct($lang) 
	{
		$this->lang = $lang;
    }

    function render($variables = array())
    {
		$variables["menu"] = new Menu;
		$this->variables = $variables;
        extract($variables);
		$lang = $this->lang;
		
		$template = GLOBAL_PATH_VIEWS.strtolower($this->view_name).'.php';
		$header = GLOBAL_PATH_VIEWS.strtolower($this->header).'.php';
		$footer = GLOBAL_PATH_VIEWS.strtolower($this->footer).'.php';
		
		if (file_exists($header))
			require_once($header);
		if (file_exists($template))
			require($template);
		if (file_exists($footer))
			require_once($footer);
    }
	
	function simpleRender($variables = array())
	{
		$this->variables = $variables;
		$this->showView($this->view_name);
	}
	
	protected function showView($view)
	{
		extract($this->variables);
		$lang = $this->lang;
		
		$template = GLOBAL_PATH_VIEWS.strtolower($view).'.php';
		if (file_exists($template))
			require($template);
	}

    function  setView($view, $header="header", $footer="footer")
    {
        $this->view_name = $view;
		$this->header = $header;
		$this->footer = $footer;
    }
	
	function error($message = "Error!")
	{
		$this->setView("error");
		$this->render(array("message"=>$message));
	}
	
	private function pageButton($page, $checked = false)
	{
		$label = '<label for="page'.$page.'">'.$page.'</label>';
		$checkAttr = '';
		if ($checked)
		{
			$checkAttr = 'checked="checked"';
		}
		$input = '<input type="radio" class="page button" id="page'.$page.'" name="page" value="'.$page.'" '.$checkAttr.'/>';
		return $input.$label;
	}
	
	function pages($p, $pages)
	{
		if ($p > 1)
			echo $this->pageButton(1);
		if ($p-100 > 1)
		{	
			$q = $p - 100;
			echo $this->pageButton($q);
		}
		if ($p-20 > 1)
		{	
			$q = $p - 20;
			echo $this->pageButton($q);
		}
		if ($p-10 > 1)
		{	
			$q = $p - 10;
			echo $this->pageButton($q);
		}
		if ($p-3 > 1)
		{	
			$q = $p - 3;
			echo $this->pageButton($q);
		}
		if ($p-2 > 1)
		{	
			$q = $p - 2;
			echo $this->pageButton($q);
		}
		if ($p-1 > 1)
		{	
			$q = $p - 1;
			echo $this->pageButton($q);
		}
		echo $this->pageButton($p, true);
		if ($p+1 < $pages)
		{	
			$q = $p + 1;
			echo $this->pageButton($q);
		}
		if ($p+2 < $pages)
		{	
			$q = $p + 2;
			echo $this->pageButton($q);
		}
		if ($p+3 < $pages)
		{	
			$q = $p + 3;
			echo $this->pageButton($q);
		}
		if ($p+10 < $pages)
		{	
			$q = $p + 10;
			echo $this->pageButton($q);
		}
		if ($p+20 < $pages)
		{	
			$q = $p + 20;
			echo $this->pageButton($q);
		}
		if ($p+100 < $pages)
		{	
			$q = $p + 100;
			echo $this->pageButton($q);
		}
		if ($p < $pages)
			echo $this->pageButton($pages);
	}
	
	function thumbnail($image, $thmbValue = 200, $options = array())
	{
		// initial options
		$allOptions = array(
			"byHeight",
			"crop",
			"keepHigh",
			"keepWide",
			"grayscale",
			"watermark"
		);
		foreach ($allOptions as $key => $option)
		{
			if (!isset($options[$option]))
			{
				$options[$option] = false;
			}
		}
	
		// parse path for the extension
		$info = pathinfo($image);
		// continue only if this is a JPEG image
		if ( strtolower($info['extension']) == 'jpg' )
		{
			if (!is_dir(IMAGE_DIR.$info['dirname'].'/thmb'))
			{
				mkdir(IMAGE_DIR.$info['dirname'].'/thmb');
			}
			$thmb_name = $info['dirname'].'/thmb/'.$info['basename'];
			$thmb_name = str_replace(".jpg", '-'.$thmbValue.".jpg", $thmb_name);
			$thmb_name = str_replace(".JPG", '-'.$thmbValue.".JPG", $thmb_name);
			if ($options['byHeight'])
			{
				$thmb_name = str_replace(".jpg", "h.jpg", $thmb_name);
				$thmb_name = str_replace(".JPG", "h.JPG", $thmb_name);
			}
			if ($options['grayscale'])
			{
				$thmb_name = str_replace(".jpg", "g.jpg", $thmb_name);
				$thmb_name = str_replace(".JPG", "g.JPG", $thmb_name);
			}
			
			if (!file_exists(IMAGE_DIR.$thmb_name))
			{
				// load image and get image size
				$img = imagecreatefromjpeg(IMAGE_DIR.$image);
				$width = imagesx( $img );
				$height = imagesy( $img );
				
				// initial aspect ratio
				if ($options['crop'])
				{
					$ratio = $options['crop'];
					
					if ($options['keepHigh'] && ($width / $height < $ratio))
					{
						$ratio = $width / $height;
					}
					
					if ($options['keepWide'] && ($width / $height > $ratio))
					{
						$ratio = $width / $height;
					}
				}
				else
				{
					$ratio = $width / $height;
				}

				// calculate thumbnail size
				if ($options['byHeight'])
				{
					$new_height = $thmbValue;
					$new_width = floor( $thmbValue * $ratio );
				}
				else
				{
					$new_width = $thmbValue;
					$new_height = floor( $thmbValue * ( 1 / $ratio ) );
				}
				
				// create a new temporary image
				$tmp_img = imagecreatetruecolor( $new_width, $new_height );

				if ($ratio > $width / $height)
				{
					$cropWidth = $width;
					$cropHeight = $width * (1 / $ratio);
					
					$x = 0;
					$y = floor(($height - $cropHeight) / 2);
				}
				else
				{
					$cropWidth = $height * $ratio;
					$cropHeight = $height;
					
					$x = floor(($width - $cropWidth) / 2);
					$y = 0;
				}
				
				// copy and resize old image into new image
				imagecopyresized( 
					$tmp_img,   // destination image
					$img, 		// source image
					0, 			// destination x
					0, 			// destination y
					$x, 			// source x
					$y, 			// source y
					$new_width, // destination width
					$new_height,// destination height 
					$cropWidth, 	// source width
					$cropHeight 	// source height
				);
				
				if ($options['grayscale'])
				{
					imagefilter( $tmp_img, IMG_FILTER_GRAYSCALE );
				}
				
				// watermark
				if (($thmbValue > 300) && ($options['watermark']))
				{
					// Load the stamp and the photo to apply the watermark to
					$stamp = imagecreatefrompng(IMAGE_DIR.'watermark.png');
					/*
					1.  width / 2  center  opacity 32
					2.  width / 3  center  opacity 32
					3.  width / 4  top left obacity 128
					4.  width / 4  bottom right obacity 128
					*/
					$stampWidth = round($new_width / 4);
					$stampHeight = round(($stampWidth / 360) * 134);
					$x = $new_width - $stampWidth - 20; 
					if ($new_width > $new_height)
					{
						$y = $new_height - $stampHeight - 20;
					}
					else
					{
						$y = round($new_width * 0.75) - $stampHeight - 20;
					}
					imagecopyresized(
						$tmp_img, 
						$stamp, 
						$x,
						$y,
						0,
						0,
						$stampWidth,
						$stampHeight,
						360,
						134
					);
				}

				// save thumbnail into a file
				imagejpeg( $tmp_img, IMAGE_DIR.$thmb_name, 100 );
			}
			
			return IMAGE_URL.$thmb_name;
		}
		
		return IMAGE_URL.$image;
	}
	
	function checkboxes($name, $resultset)
	{
		while ($row = $resultset->fetch_object())
		{
			echo '<input type="checkbox" ';
			if ($row->checked)
			{
				echo 'checked="checked" ';
			}
			echo 'id="'.$name.$row->id.'" name="'.$name.'[]" value="'.$row->id.'">';
			echo '<label for="'.$name.$row->id.'">'.$row->label.'</label>';
			echo "<br/>\n";
		}
	}
	
	function checkList($resultset, $itemProp = null)
	{
		while ($row = $resultset->fetch_object())
		{
			echo '<li';
			if ($itemProp)
			{
				echo ' itemprop="'.$itemProp.'"';
			}
			echo '><span class="ui-icon ui-icon-check listCheck"></span>';
			echo $row->label.'</li>';
		}
	}
}

?>