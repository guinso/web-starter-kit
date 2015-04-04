<?php
namespace Ig\Pdf\Tag;

use Ig\Pdf\PdmTagHandler;
class Header implements \Ig\Pdf\IPdmTag {
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		//set width to page width
		
		$pageWidth = $pdf->getPageWidth();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];

		$pdf->pushStyle();
		$pdf->setStyle('width', $effectiveWidth);
		$pdf->setStyle('x', $margin['left']);
		$pdf->setStyle('y', $margin['top']);
		$pdf->SetXY($margin['left'], $margin['top']);
		
		$size = self::simulate($pdf, $xmlObj);
		$pdf->setHeaderMargin($size['height']);
		
		foreach($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v)
				$v = doubleval($v);
		
			$pdf->setStyle($k, $v);
		}
		
		//only process child tag of <row>
		$rows = $xmlObj->children();
		foreach($rows as $row) {
			\Ig\Pdf\PdmTagHandler::handleTag($pdf, $row->getName(), $row);
		}
		$pdf->popStyle();
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$pdf->pushStyle();
		$pageWidth = $pdf->getPageWidth();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];

		$pdf->setStyle('width', $effectiveWidth);
		$pdf->setStyle('x', $margin['left']);
		$pdf->setStyle('y', $margin['top']);
		$pdf->SetXY($margin['left'], $margin['top']);
		
		foreach($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v)
				$v = doubleval($v);
		
			$pdf->setStyle($k, $v);
		}
		$effectiveHeight = PdmTagHandler::calHeightOffset($pdf);
		
		$rows = $xmlObj->children();
		foreach($rows as $row) {
			$x = PdmTagHandler::calDimension($pdf, $row->getName(), $row);
			$effectiveHeight += $x['height'];
		}
		$pdf->popStyle();
		
		return array(
			'width' => $effectiveWidth,
			'height' => $effectiveHeight
		);
	}
}
?>