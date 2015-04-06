<?php
namespace Ig\Pdf\Tag;

class Page implements \Ig\Pdf\IPdmTag {
	public static function run(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$pdf->AddPage();
		
		//set width to page width
		$pageWidth = $pdf->getPageWidth();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];
		
		$pdf->pushStyle();
		$pdf->setStyle('width', $effectiveWidth);
		$pdf->setStyle('x', $margin['left']);
		$pdf->setStyle('y', $margin['top'] + $margin['header']);
		$pdf->SetXY($margin['left'], $margin['top'] + $margin['header']);
		
		//only process child tag of <row>
		$rows = $xmlObj->children();
		foreach($rows as $row) {
			$tag = $row->getName();
			
			//call Pdm tag parser
			if($tag == 'row' || $tag == 'bottom')
				\Ig\Pdf\PdmTagHandler::handleTag($pdf, $row->getName(), $row);
		}
		
		$pdf->popStyle();
		
		$pdf->endPage();
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$pdf->pushStyle();
		
		$pageWidth = $pdf->getPageWidth();
		$pageHeight = $pdf->getPageHeight();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];
		$effectiveHeight = $pageHeight - $margin['top'] - $margin['bottom'] - $margin['header'] - $margin['footer'];
 		
		$pdf->setStyle('width', $effectiveWidth);
		$pdf->setStyle('x', $margin['left']);
		$pdf->setStyle('y', $margin['top'] + $margin['header']);
		$pdf->SetXY($margin['left'], $margin['top'] + $margin['header']);
		
		$pgNo = 0;
		$h = $margin['top'] + $margin['header'];
		foreach($xmlObj->children() as $child) {
			$tag = $child->getName();
			$size = \Ig\Pdf\PdmTagHandler::calDimension($pdf, $tag, $child);
			
			if($tag == 'bottom') {
				$heightB = $size['height'];
				$residue = $effectiveHeight - $h;
				
				//add one page if not enough
				if($residue < $heightB)
					$pgNo += 1;
				
				$h = $effectiveHeight;
			} else if($tag == 'row') {
				if($h + $size['height'] > $effectiveHeight) {
					$h = $size['height'];
					$pgNo += 1;
				} else {
					$h += $size['height'];
				}
			}
		}
		
		$pdf->popStyle();
		
		//add page if have residue
		if($h > 0)
			$pgNo += 1;
		//once page tag is declared, a page had been created
		if($pgNo == 0)
			$pgNo = 1;
		
		$h = $pgNo * $pdf->getPageHeight();
		
		return array(
			'width' => $effectiveWidth,
			'height' => $h
		);
	}
}
?>