<?php 
namespace Ig\Pdf\Tag;

class Img implements \Ig\Pdf\IPdmTag {
	
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
			
			$pdf->setStyle($k, $v);
		}
		
		//set height to width value if not define by user
		if (empty($xmlObj['height'])) {
			$xmlObj['height'] = 		$pdf->getStyle('width');
			$pdf->setStyle('height', 	$pdf->getStyle('width'));
		}
		
		$src = $xmlObj['src'];
		
		if (substr($src, 0, 1) == '@')
			$src = \Ig\Config\Loader::getRootPath() . DIRECTORY_SEPARATOR . substr($src, 1);
		
		$pdf->drawImage($src);
		
		$x = $pdf->getStyle('x');
		$y = $pdf->getStyle('y') + $pdf->getStyle('height');
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
		
		//set height to width value if not define by user
		if (empty($xmlObj['height'])) {
			$xmlObj['height'] = 		$pdf->getStyle('width');
			$pdf->setStyle('height', 	$pdf->getStyle('width'));
		}
		
		$width = $xmlObj['width'] + \Ig\Pdf\PdmTagHandler::calWidthOffset($pdf);
		$height = $xmlObj['height'] + \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		$pdf->popStyle();
		
		return array('height' => $height, 'width' => $width);
	}
}
?>