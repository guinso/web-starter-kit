<?php 
namespace Ig\Pdf\Tag;

class Hr implements \Ig\Pdf\IPdmTag {
	
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
			
			$pdf->setStyle($k, $v);
		}
		
		$pdf->drawHr();
		
		$size = self::simulate($pdf, $xmlObj);
		$x = $pdf->getStyle('x');
		$y = $size['height'] + $pdf->getStyle('y');
		
		$pdf->setStyle('x', $x);
		$pdf->setStyle('y', $y);
		$pdf->SetXY($x, $y);
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		$pdf->pushStyle();
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
			
			$pdf->setStyle($k, $v);
		}
		
		$width = $xmlObj['width'] + \Ig\Pdf\PdmTagHandler::calWidthOffset($pdf);
		$height = $pdf->getStyle('line-width') + \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		$pdf->popStyle();
		
		return array('height' => $height, 'width' => $width);
	}
}
?>