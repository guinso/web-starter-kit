<?php 
namespace Ig\Pdf\Tag;

use Ig\Pdf\PdmTagHandler;

class Col implements \Ig\Pdf\IPdmTag {
	
	private static $allowTags = array('Row', 'Span', 'Img', 'Hr');
	
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		self::setDefaultStyle($pdf);

		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
			
			$pdf->setStyle($k, $v);
		}
		
		$pdf->pushStyle();
		$x = $pdf->getStyle('x');
		$y = $pdf->getStyle('y');
		$size = self::simulate($pdf, $xmlObj);
		$children = $xmlObj->children();
		
		foreach ($children as $child) {
			$tag = $child->getName();
			
			if (array_search($tag, self::$allowTags) >= 0) {
				//prevent column cell width exceed
				//$width = doubleval($child['width']);
				//if($width > $colWidth)
				//	$child['width'] = $colWidth;	
			
				\Ig\Pdf\PdmTagHandler::handleTag($pdf, $tag, $child);
			}
		}
		
		$pdf->popStyle();
		$ww = 0;
		if (isset($xmlObj['width'])) {
			//always use hard defined width
			$ww = doubleval($xmlObj['width']);
		} else {
			//otherwise, use estimated width
			$ww = $size['width'];
		}
		
		$pdf->SetXY($x + $ww, $y);
		$pdf->setStyle('x', $x + $ww);
		$pdf->setStyle('y', $y);
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		$pdf->pushStyle();
		self::setDefaultStyle($pdf);
		
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
			
			$pdf->setStyle($k, $v);
		}
		
		$width = \Ig\Pdf\PdmTagHandler::calWidthOffset($pdf);
		$height = 0; //\Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		$colX = $pdf->getStyle('x');
		$colY = $pdf->getStyle('y');
		$x = $pdf->getStyle('x');
		$y = $pdf->getStyle('y');
		$maxX = $x;
		
		//allow multiple tags render ()
		$children = $xmlObj->children();
		foreach ($children as $child) {
			$tag = $child->getName();
			
			if (array_search($tag, self::$allowTags) >= 0) {
				//$width = $child['width'];
				//if(empty($width) || $width > $pdf->getStyle('width'))
				//	$child['width'] = $pdf->getStyle('width');
				
				$size = \Ig\Pdf\PdmTagHandler::calDimension($pdf, $child->getName(), $child);
				/*
				if($child['inline'] == 1) {
					$x += $size['width'];
				} else {
					$y += $size['height'];
					$x = $colX + $size['width'];
				}
				*/
				$y += $size['height'];
				$x = $colX + $size['width'];
				
				if ($x > $maxX)
					$maxX = $x;
			}
		}
		$pdf->popStyle();
		
		return array('width' => $width + $maxX - $colX, 'height' => $height + $y - $colY);
	}
	
	private static function setDefaultStyle(\Ig\Pdf\ExtTcpdf $pdf) 
	{
		$pdf->setStyle('padding-top', 0);
		$pdf->setStyle('padding-left', 0);
		$pdf->setStyle('padding-bottom', 0);
		$pdf->setStyle('padding-right', 0);
		$pdf->setStyle('margin-top', 0);
		$pdf->setStyle('margin-top', 0);
		$pdf->setStyle('margin-top', 0);
		$pdf->setStyle('margin-top', 0);
	}
}
?>