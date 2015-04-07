<?php 
namespace Ig\Pdf\Tag;

use Ig\Pdf\PdmTagHandler;

class Span implements \Ig\Pdf\IPdmTag {
	
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		self::setDefaultStyle($pdf);
		
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
				
			$pdf->setStyle($k, $v);
		}
		
		$text = self::getReserveWord($pdf, $xmlObj);
		$size = self::simulate($pdf, $xmlObj);
		$h = $size['height'];
		$w = $size['width'];
		
		if ($pdf->getStyle('wrap-text') == 0) {
			$pdf->setStyle('width', $w);
		}
		
		$pdf->drawText($text);
		
		//update coordinate after draw 
		$x = $pdf->getStyle('x');
		$y = $pdf->getStyle('y');
		/*
		if($pdf->getStyle('inline') == 1) {
			$pdf->setStyle('x', $x + $w);
			$pdf->setStyle('y', $y);
			$pdf->SetXY($x + $w, $y);
		} else {
			$pdf->setStyle('x', $x);
			$pdf->setStyle('y', $y + $h);
			$pdf->SetXY($x, $y + $h);
		}
		*/
		$pdf->setStyle('x', $x);
		$pdf->setStyle('y', $y + $h);
		$pdf->SetXY($x, $y + $h);
		
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
		
		$text = self::getReserveWord($pdf, $xmlObj);
		
		if ($pdf->getStyle('wrap-text') == 0) {
			$width = $pdf->calTextWidth($text) + $pdf->getStyle('padding-left') + $pdf->getStyle('padding-right');
			$pdf->setStyle('width', $width);
		}
		
		$width = $pdf->getStyle('width') + \Ig\Pdf\PdmTagHandler::calWidthOffset($pdf);
		
		if (!empty($xmlObj['height'])) {
			$height = doubleval($xmlObj['height']) + \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		} else {
			$height = $pdf->calTextHeight($text) + \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		}
		
		$pdf->popStyle();
		
		return array('height' => $height, 'width' => $width);
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
		$pdf->setStyle('border', '');
		$pdf->setStyle('border-top', '');
		$pdf->setStyle('border-bottom', '');
		$pdf->setStyle('border-left', '');
		$pdf->setStyle('border-right', '');
		
		$pdf->setStyle('font-style', '');
		$pdf->setStyle('fint-size', 12);
	}
	
	private static function getReserveWord(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		$pageIndex = $pdf->PageNo();
		$pageSize = $pdf->getPageCount();
	
		$text = $xmlObj['text'];
	
		$text = str_replace('[[pgindex]]', $pageIndex, $text);
		$text = str_replace('[[pgsize]]', $pageSize, $text);
	
		return $text;
	}
}
?>