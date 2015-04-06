<?php 
namespace Ig\Pdf\Tag;

class Row implements \Ig\Pdf\IPdmTag {
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		self::setDefaultStyle($pdf);
		
		foreach($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v)
				$v = doubleval($v);
			
			$pdf->setStyle($k, $v);
		}
		
		$margins = $pdf->getMargins();
		$yy = $pdf->GetY();
		$availableHeight = $pdf->getPageHeight() - $yy -
			$margins['bottom'] - $margins['footer'];
		
		$calSize = self::simulate($pdf, $xmlObj);
		$height = ceil($calSize['height']);
		
		$y = $pdf->getStyle('y') + $calSize['height'];
		//set to next page if not enough space (height)
		if($pdf->getStyle('footer') === null && 
		   $pdf->getStyle('header') === null &&
			$availableHeight < $height) {

			$y = $margins['top'] + $margins['header'];
			$pdf->endPage();
			$pdf->AddPage();
			
			$pdf->setY($y);
			$pdf->setStyle('y', $y);
			
			$y = $y + $calSize['height'];
		}
		
		$x = $pdf->getStyle('x');
		
		$pdf->pushStyle();
		$avrWidth = self::_getAvrWidth($pdf, $xmlObj);
		
		$cols = $xmlObj->col;
		foreach($cols as $col) {
			//assign width if not define yet
			if(empty($col['width']))
				$col['width'] = $avrWidth;
			
			//run each col
			\Ig\Pdf\PdmTagHandler::handleTag($pdf, 'col', $col);
		}
		$pdf->popStyle();
		
		//update x, y position
		$pdf->SetXY($x, $y);
		$pdf->setStyle('x', $x);
		$pdf->setStyle('y', $y);
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$pdf->pushStyle();
		self::setDefaultStyle($pdf);
		
		foreach($xmlObj->attributes() as $k => $v) {
			if($v == (string)(double)$v)
				$v = doubleval($v);
				
			$pdf->setStyle($k, $v);
		}
		
		$width = $pdf->getStyle('width') + \Ig\Pdf\PdmTagHandler::calWidthOffset($pdf);
		$height = \Ig\Pdf\PdmTagHandler::calHeightOffset($pdf);
		$h = 0;
		
		$avrWidth = self::_getAvrWidth($pdf, $xmlObj);
		
		//only run 'col' tags
		$cols = $xmlObj->col;
		foreach($cols as $col) {
			//assign width if not define yet
			if(empty($col['width']))
				$col['width'] = $avrWidth;
			
			$x = \Ig\Pdf\PdmTagHandler::calDimension($pdf, 'col', $col);
			
			if($x['height'] > $h)
				$h = $x['height'];
		}
		$pdf->popStyle();
		
		return array(
			'width' => $width,
			'height' => $height + $h
		);
	}
	
	private static function _getAvrWidth(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$cols = $xmlObj->col;
		
		$nonWcnt = 0;
		$usedW = 0;
		foreach($cols as $col) {
			if(empty($col['width']))
				$nonWcnt += 1;
			else
				$usedW += $col['width'];
		}
		$avrWidth = 0;
		if($nonWcnt > 0)
			$avrWidth = ($pdf->getStyle('width') - $usedW) / $nonWcnt;
		
		return $avrWidth;
	}
	
	private static function setDefaultStyle(\Ig\Pdf\ExtTcpdf $pdf) {
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