<?php 
namespace Ig\Pdf\Tag;

use Ig\Pdf\PdmTagHandler;
class Span implements \Ig\Pdf\IPdmTag {
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		foreach($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v)
				$v = doubleval($v);
				
			$pdf->setStyle($k, $v);
		}
		
		$text = $xmlObj['text'];
		$size = self::simulate($pdf, $xmlObj);
		$h = $size['height'];
		$w = $size['width'];
		
		if($pdf->getStyle('wrap-text') == 0) {
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
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$pdf->pushStyle();
		foreach($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v)
				$v = doubleval($v);
				
			$pdf->setStyle($k, $v);
		}
		
		$text = $xmlObj['text'];
		
		if($pdf->getStyle('wrap-text') == 0) {
			$width = $pdf->calTextWidth($text) + $pdf->getStyle('padding-left') + $pdf->getStyle('padding-right');
			$pdf->setStyle('width', $width);
		}
		
		$width = $pdf->getStyle('width') + \Ig\Pdf\PdmTagHandler::calWidthOffset($pdf);
		$height = $pdf->calTextHeight($text) + \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		$pdf->popStyle();
		
		return array('height' => $height, 'width' => $width);
	}
}
?>