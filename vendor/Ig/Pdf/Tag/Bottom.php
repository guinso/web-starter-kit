<?php
namespace Ig\Pdf\Tag;

class Bottom implements \Ig\Pdf\IPdmTag {
	
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		self::setDefaultStyle($pdf);
		
		foreach ($xmlObj->attributes() as $k => $v) {
			if ($v == (string)(double)$v) {
				$v = doubleval($v);
			}
			
			$pdf->setStyle($k, $v);
		}
		
		$size = self::simulate($pdf, $xmlObj);
		$height = ceil($size['height']);
		$pageMargins = $pdf->getMargins();
		$pageHeight = $pdf->getPageHeight();
		$availableHeight = $pageHeight -
			//$pageMargins['top'] - $pageMargins['header'] - 
			$pageMargins['bottom'] - $pageMargins['footer'] -
			$pdf->GetY();
		
		if ($height > $availableHeight) {
			Throw new \Exception("Footer height is greater than available page height.");
		}
		
		//go to next page if not enough to print
		if ($height > $availableHeight) {
			$pdf->endPage();
			$pdf->AddPage();
		}
		
		$offsetY = $pdf->getPageHeight() - $pageMargins['bottom'] - $pageMargins['footer'] - $height;
		/*
		$bottom = $pageMargins['bottom'];
		$footer = $pageMargins['footer'];
		error_log("Bottom:- ph:$pageHeight, m-bottom:$bottom, m-footer:$footer, height:$height, offsetY:$offsetY");
		*/
		$pdf->setStyle('x', $pageMargins['left']);
		$pdf->setStyle('y', $offsetY);
		$pdf->SetXY($pageMargins['left'], $offsetY);
		
		$pdf->pushStyle();
		$rows = $xmlObj->row;
		foreach ($rows as $row) {
			\Ig\Pdf\PdmTagHandler::handleTag($pdf, $row->getName(), $row);
		}
		$pdf->popStyle();
		
		//set to end of page
		$pdf->SetXY($pageMargins['left'], $pdf->getPageHeight() - $pageMargins['bottom'] - $pageMargins['footer']);
		$pdf->setStyle('x', $pageMargins['left']);
		$pdf->setStyle('y', $pdf->getPageHeight() - $pageMargins['bottom'] - $pageMargins['footer']);
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) 
	{
		$pdf->pushStyle();
		self::setDefaultStyle($pdf);
		
		foreach ($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v) {
				$v = doubleval($v);
			}
			
			$pdf->setStyle($k, $v);
		}
		
		$width = $pdf->getStyle('width');
		$height = \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		
		$rows = $xmlObj->row;
		foreach ($rows as $row) {
			$size = \Ig\Pdf\PdmTagHandler::calDimension($pdf, $row->getName(), $row);
			$height += $size['height'];
		}
		
		$pdf->popStyle();
 		
		return array(
			'width' => $width,
			'height' => $height
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