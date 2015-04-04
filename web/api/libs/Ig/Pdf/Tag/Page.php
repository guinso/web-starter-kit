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
			//call Pdm tag parser
			\Ig\Pdf\PdmTagHandler::handleTag($pdf, $row->getName(), $row);
		}
		
		$pdf->popStyle();
		
		$pdf->endPage();
	}
	
	public static function simulate(\Ig\Pdf\ExtTcpdf $pdf, $xmlObj) {
		$pageWidth = $pdf->getPageWidth();
		$pageHeight = $pdf->getPageHeight();
		$margin = $pdf->getMargins();
		$effectiveWidth = $pageWidth - $margin['left'] - $margin['right'];
		$effectiveHeight = $pageHeight - $margin['top'] - $margin['bottom'] - $margin['header'] - $margin['footer'];
 		
		return array(
			'width' => $effectiveWidth,
			'height' => $effectiveHeight
		);
	}
}
?>