<?php
namespace Ig\Pdf\Tag;

class Footer implements \Ig\Pdf\IPdmTag {
	
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		$pdf->setStyle('footer', true);
		//set width to page width
		
		$pageWidth = $pdf->getPageWidth();
		$pageHeight = $pdf->getPageHeight();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];

		$size = self::simulate($pdf, $xmlObj);
		$pdf->setFooterMargin($size['height']);
		$y = $pageHeight - $margin['bottom'] - $size['height'];
		
		$pdf->pushStyle();
		$pdf->setStyle('width', $effectiveWidth);
		$pdf->setStyle('x', $margin['left']);
		$pdf->setStyle('y', $y);
		$pdf->SetXY($margin['left'], $y);
		
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
		
			$pdf->setStyle($k, $v);
		}
		
		//only process child tag of <row>
		$rows = $xmlObj->children();
		foreach ($rows as $row) {
			\Ig\Pdf\PdmTagHandler::handleTag($pdf, $row->getName(), $row);
		}
		$pdf->popStyle();
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		$pdf->pushStyle();
		self::setDefaultStyle($pdf);
		
		$pageWidth = $pdf->getPageWidth();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];

		$pdf->setStyle('width', $effectiveWidth);
		$pdf->setStyle('x', $margin['left']);
		$pdf->setStyle('y', $margin['top']);
		$pdf->SetXY($margin['left'], $margin['top']);
		
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v)
				$v = doubleval($v);
		
			$pdf->setStyle($k, $v);
		}
		$effectiveHeight = \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		
		$rows = $xmlObj->children();
		foreach ($rows as $row) {
			$x = \Ig\Pdf\PdmTagHandler::calDimension($pdf, $row->getName(), $row);
			$effectiveHeight += $x['height'];
		}
		$pdf->popStyle();
		
		return array(
			'width' => $effectiveWidth,
			'height' => $effectiveHeight
		);
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