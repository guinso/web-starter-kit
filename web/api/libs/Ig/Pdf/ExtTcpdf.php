<?php
namespace Ig\Pdf;
//Extended Tcpdf class
/**
 * Extened TCPDF class
 * Custom for office document printing (black and white printing)
 * Reduce advance function to increase content creation efficiency
 * Try skip using HTML mode as it is hard to set styling precisely
 * Milestone: create a XML template for faster document stying and databinding with JSON / PHP array
 * @author chingchetsiang
 *
 */
class ExtTcpdf extends \TCPDF {
	var $igHeaderPdmObj;
	var $igFooterPdmObj;
	var $igPageCount;
	
	var $igStackStyle;
	var $igStyle;
	
	function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
	
		$this->igPageCount = 0;
		
		$this->resetStyle();
		
		$this->setCellStyle(self::getDefaultStyle());
	}
	
	public function resetStyle() {
		$this->igStackStyle = array();
		
		$this->pushStyle();
	}
	
	public function pushStyle($useDefaultSetting = false) {
		if($useDefaultSetting)
			$x = self::getDefaultStyle();
		else if(COUNT($this->igStackStyle) == 0) {
			$x = self::getDefaultStyle();
		} else {
			$x = $this->getStyleSet();
		}
		
		array_push($this->igStackStyle, $x);
	}
	
	public function popStyle() {
		array_pop($this->igStackStyle);
	}
	
	/*
	public function getParentStyle() {
		$cnt = COUNT($this->igStackStyle);
		
		if($cnt > 0)
			return $this->igStackStyle[$cnt - 1];
		else 
			return null;
	}
	*/
	
	public function getStyleSet() {
		$x = end($this->igStackStyle);
		$key = key($this->igStackStyle);
		
		return $this->igStackStyle[$key];
	}	
		
	public function getStyle($name) {
		$x = end($this->igStackStyle);
		$key = key($this->igStackStyle);
		
		if(!empty($name) && array_key_exists($name, $this->igStackStyle[$key]))
			return $this->igStackStyle[$key][$name];
		else 
			return null;
	}
	
	public function setStyle($name, $value) {
		$x = end($this->igStackStyle);
		$key = key($this->igStackStyle);
		
		$this->igStackStyle[$key][$name] = $value;
	}
	
	public function setPageCount($pgCnt) {
		$this->igPageCount = $pgCnt;
	}
	
	public function getPageCount() {
		return $this->igPageCount;
	}

	public function setPdmHeader($igHeaderPdmObj) {
		$this->igHeaderPdmObj = $igHeaderPdmObj;
	}
	
	public function setPdmFooter($igFooterPdmObj) {
		$this->igFooterPdmObj = $igFooterPdmObj;
	}
	
	//override parent function - header
	public function Header() {
		if(!empty($this->igHeaderPdmObj)) {
			$tag = $this->igHeaderPdmObj->getName();

			if($tag == 'header')
				\Ig\Pdf\PdmTagHandler::handleTag($this, $tag, $this->igHeaderPdmObj);
			else
				Throw new \Exception("IgPdfTagHeader:- Draw header rejected, you must pass <header> tag.");
		}
	}
	
	//override parent fucntion - footer
	public function Footer() {
		if(!empty($this->igFooterPdmObj)) {
			$tag = $this->igFooterPdmObj->getName();
		
			if($tag == 'footer')
				\Ig\Pdf\PdmTagHandler::handleTag($this, $tag, $this->igFooterPdmObj);
			else
				Throw new \Exception("IgPdfTagHeader:- Draw header rejected, you must pass <footer> tag.");
		}
	}
	
	/**
	 * Set all cell basic style
	 * @param array $param
	 * @return ExtTcpdf
	 */
	public function setCellStyle($param) {
		$textColor = self::hex2rgb($param['text-color']);
		$lineColor = self::hex2rgb($param['line-color']);
		$bgColor = self::hex2rgb($param['background-color']);
		
		$fontStyle = self::convertFontStyle($param['font-style']);
		
		// set font
		$this->SetFont(
			$param['font-family'], 
			$fontStyle, 
			$param['text-size']);
		$this->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
		
		// set cell padding
		$this->setCellPaddings(
			doubleval($param['padding-left']),
			doubleval($param['padding-top']),
			doubleval($param['padding-right']),
			doubleval($param['padding-bottom']));
		
		// set cell margins
		$this->setCellMargins(
			doubleval($param['margin-left']),
			doubleval($param['margin-top']),
			doubleval($param['margin-right']),
			doubleval($param['margin-bottom']));
		
		$this->SetLineStyle(array(
			'width' => 	$param['line-width'], //line width for all line, rectangle and border
			'cap' 	=> 	$param['line-cap'], //butt, round, square
			'join' 	=> 	$param['line-join'], //miter, round, bevel
			'dash' 	=> 	$param['line-dash'], //0, or 'd1,d2' dx is dash length (in number)
			'phase' => 	$param['line-phase'], //dash phase
			'color' => 	$lineColor, //RGB color model
		));
		
		// set color for background
		$this->SetFillColor($bgColor[0], $bgColor[1], $bgColor[2]);
		
		/*
		// set font
		$this->SetFont('times', '', 10);
		$this->SetTextColor(0, 0, 0);
	
		// set cell padding
		$this->setCellPaddings(1, 1, 1, 1);
	
		// set cell margins
		$this->setCellMargins(1, 1, 1, 1);
	
		// set shape (line, rectangle, border)
		$this->SetLineWidth(1);
		$this->SetDrawColor(0, 0, 0);
	
		// set color for background
		$this->SetFillColor(255, 255, 127);
		*/
		
		return $this;
	}

	/**
	 * Get default non-style setting
	 * @return multitype:string number multitype:number
	 */
	public static function getDefaultStyle() {
		return array(
			//general style parameter
			'font-family' => 'arial',
			'font-style' => '',
			'text-size' => 10,
			'text-color' => '#000', //RGB color model
			'padding-left' => 1, 
			'padding-right' => 1,
			'padding-top' => 1,
			'padding-bottom' => 1,
			'margin-left' => 0,
			'margin-right' => 0,
			'margin-top' => 0,
			'margin-bottom' => 0,
			'line-width' => 0, //line width for all line, rectangle and border
			'line-color' => '#000', //RGB color model
			'line-cap' => 'square', //butt, round, square
			'line-join' => '', //miter, round, bevel
			'line-dash' => 0, //0, or 'd1,d2' dx is dash length (in number)
			'line-phase' => 0, //dash phase
			'background-color' => '#FFF', //RGB color model
			
			//border style parameter
			'border' => '', // width - style - color, style can be 'none', 'solid', or 'dash'
			'border-top' => '',
			'border-bottom' => '',
			'border-left' => '',
			'border-right' => '',
			
			'wrap-text' => 1,
			'text-align' => 'left', // 'L', 'C', 'R', or 'J'
			'v-align' => 'top', // 'T', 'M', 'B'
			'width' => 0, // 0 is full width
			'minHeight' => 0, // auto
			'x' => '',
			'y' => '',
			'maxHeight' => 0,
			'height' => 0,
				
			'next' => 0, //0 is to the right, 1 is to the beginning next line, 2 is to below
		);
	}

	public function drawText($text) {
		
		$style = $this->getStyleSet();
		$this->setCellStyle($style);
		
		$border = self::convertBorderStyle($style);
		$bgColor = isset($style['background-color'])?
			self::hex2rgb($style['background-color']): array();
		$vAlign = self::convertVAlign($style['v-align']);
		$textAlign = self::convertTextAlign($style['text-align']);
		
		//basic write text
		$this->MultiCell(
			$style['width'],
			$style['minHeight'],
			$text,
			$border, 	//border
			$textAlign, //text align
			false, //fill background color
			1, //set to: begining of next line 
			$style['x'], //coordinate x
			$style['y'], //coordinate y
			true, //reset last cell height
			0, //font stretch
			false, //is HTML - always set to false
			false, //autopadding
			$style['height'], //maximum height
			$vAlign, //text vertical align
			false //auto scale text to fix cell
		);
		
		return $this;
	}
	
	public function calTextHeight($text) {
		$this->pushStyle();
		$style = $this->getStyleSet();
		$this->setCellStyle($style);
		
		$h = $this->getStringHeight(
			$style['width'], 
			$text, 
			false, false, 
			$style['padding-left'] + $style['padding-right'], 
			$style['border']);

		
		$this->popStyle();
		return $h;
	}
	
	public function calTextWidth($text) {
		$style = $this->getStyleSet();
		$this->pushStyle();
		$this->setCellStyle($this->getStyleSet());
		$fontStyle = self::convertFontStyle($style['font-style']);
		
		$w = $this->GetStringWidth($text, $style['font-family'], $fontStyle, $style['text-size']);
		$this->popStyle();
		return $w;
	}
	
	/**
	 * Draw Horizontal Line
	 */
	public function drawHr() {
		$style = $this->getStyleSet();
		
		$width = $style['width'];
		$x1 = $style['x'];
		$x2 = $x1 + $width;
		
		$y1 = $style['y'] + $style['padding-top'] + $style['margin-top'] + ($style['line-width']/2);
		
		
		$this->setCellStyle($style);
		$this->Line($x1, $y1, $x2, $y1, array(
			'width' => doubleval($style['line-width']),
			'color' => $style['line-color'],
			'cap' => $style['line-cap'],
			'join' => $style['line-join'],
			'dash' => $style['line-dash'],
			'phase' => $style['line-phase'],
		));
	}
	
	/**
	 * Draw horizontal line break
	 * @param boolean $addLineBreak
	 * @return ExtTcpdf
	 */
	public function drawLineBreak($lineStyle = array()) {
		$this->Ln();
		
		$pageWidth    = $this->getPageWidth();   // Get total page width, without margins
		$pageMargins  = $this->getMargins();     // Get all margins as array
		$px2          = $pageWidth - ($pageMargins['left']); // Compute x value for second point of line
		
		$p1x   = $this->getX();
		$p1y   = $this->getY();
		$p2x   = $px2;
		$p2y   = $p1y;  // Use same y for a straight line
		
		$this->Line($p1x, $p1y, $p2x, $p2y, $lineStyle);

		return $this;
	}
	
	public function drawImage($src) {
		if(!is_readable($src))
			Throw new \Exception("Image file $src not found.");
		
		$type = '';
		$imgType = exif_imagetype($src);
		if($imgType == IMAGETYPE_JPEG)
			$type = 'JPEG';
		else if($imgType == IMAGETYPE_PNG)
			$type = 'PNG';
		else 
			Throw new \Exception("Image type other than JPEG and PNG are not supported, period.");
		
		$style = $this->getStyleSet();
		$dpi = 72; //default image quality
		
		$this->Image(
			$src,
			$style['x'], 
			$style['y'],
			$style['width'], $style['height'],
			$type,
			'',  //link
			'', //align T, M, B, N
			false, //resize true, false, 2 (upscale + downscale)
			$dpi, //DPI, used for resize
			'', //Palign L, C, R
			false, //is mask
			false, //mask image object
			1, //border style
			true, //fit box true, false, or combo T,M,B and L,C,R
			false, //hide image
			false, //fit image to page (no break to next page)
			false, //enable alternate image
			null //alternate image sources
		);
	}
	
	public function drawRectangle() {
		$style = $this->getStyleSet();
		
		$border = self::convertBorderStyle($style);
		$bgColor = isset($style['background-color'])? 
			self::hex2rgb($style['background-color']): array();
		
		$this->Rect(
			$style['x'],
			$style['y'],
			$style['width'],
			$style['height'],
			'', // leave blank first 
			$border, 
			$bgColor);
	}
	
	public static function convertFontStyle($fontStyle) {
		$fontStyle = strtolower($fontStyle);
		$result = '';
		$xx = explode(' ', $fontStyle);
		foreach ($xx as $x) {
			if($x == 'bold')
				$result .= 'B';
			
			if($x == 'italic')
				$result .= 'I';
			
			if($x == 'strikethrough')
				$result .= 'D';
			
			if($x == 'underline')
				$result .= 'U';
			
			if($x == 'overline')
				$result .= 'O';
		}
		
		return $result;
	}
	
	public static function convertBorderStyle($style) {
		$result = array();
		
		if(!empty($style['border'])) {
			$x = self::convertBorderLineStyle($style['border']);
			if(!empty($x)) {
				$result['L'] = $x;
				$result['R'] = $x;
				$result['T'] = $x;
				$result['B'] = $x;
			}
		}
		if(!empty($style['border-top'])) {
			$x = self::convertBorderLineStyle($style['border-top']);
			if(!empty($x))
				$result['T'] = $x;
		}
		if(!empty($style['border-bottom'])) {
			$x = self::convertBorderLineStyle($style['border-bottom']);
			if(!empty($x))
				$result['B'] = $x;
		}
		if(!empty($style['border-left'])) {
			$x = self::convertBorderLineStyle($style['border-left']);
			if(!empty($x))
				$result['L'] = $x;
		}
		if(!empty($style['border-right'])) {
			$x = self::convertBorderLineStyle($style['border-right']);
			if(!empty($x))
				$result['R'] = $x;
		}
		
		return $result;
	}
	
	public static function convertBorderLineStyle($lineStyle) {
		$result = array();
		
		$xx = explode(' ', $lineStyle);
		
		$width = empty($xx[0])? 0 : doubleval($xx[0]);
		$style = empty($xx[1])? 'none': strtolower($xx[1]);
		$color = empty($xx[2])? array(0,0,0): self::hex2rgb($xx[2]);
		
		$needToDraw = false;
		$dash = 0;
		if($style == 'solid') {
			$needToDraw = true;
		} else if($style == 'dash') {
			$dash = '2 1';
		}
		
		if($needToDraw) {
			$result = array(
				'width' => $width,
				'cap' => 'square', //end line shape
				'join' => 'miter', //line edge shape
				'dash' => $dash,
				'phase' => 0, //no dash param (phase)
				'color' => $color
			);
		} else 
			$result = 0;
		
		return $result;
	}
	
	public static function convertVAlign($vAlign) {
		$result = '';
		$vAlign = strtolower($vAlign);
		
		if($vAlign == 'top')
			$result = 'T';
		else if($vAlign == 'middle')
			$result = 'M';
		else if($vAlign == 'bottom')
			$result = 'B';
		else 
			$result = 'T';
		
		return $result;
	}
	
	public static function convertTextAlign($tAlign) {
		$result = '';
		$tAlign = strtolower($tAlign);
	
		if($tAlign == 'left')
			$result = 'L';
		else if($tAlign == 'center')
			$result = 'C';
		else if($tAlign == 'right')
			$result = 'R';
		else
			$result = 'L';
	
		return $result;
	}
	
	public static function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
	
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			$rgb = array($r, $g, $b);
		} else if(strlen($hex) == 6) {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
			$rgb = array($r, $g, $b);
		} else {
			$rgb = array(0,0,0);
		}
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}
}
?>