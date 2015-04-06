<?php 
namespace Ig\Pdf;

class PdmTagHandler {
	public static function handleTag(ExtTcpdf $pdf, $tag, $xmlObj) {
		$tagHanlderClass = "\\Ig\\Pdf\\Tag\\" . ucwords(strtolower($tag));
		
		//check class exists or not
		if(!class_exists($tagHanlderClass))
			Throw new \Exception("Ig::PdmTagHandler - Unspecified tag <$tag> detected.");
		
		//run handler
		$tagHanlderClass::run($pdf, $xmlObj);
	}
	
	public static function calDimension(ExtTcpdf $pdf, $tag, $xmlObj) {
		$tagHanlderClass = "\\Ig\\Pdf\\Tag\\" . ucwords(strtolower($tag));
		
		//check class exists or not
		if(!class_exists($tagHanlderClass))
			Throw new \Exception("Ig::report::PdmTagHandler - Unspecify tag <$tag> $tagHanlderClass detected.");
		
		//run handler
		return $tagHanlderClass::simulate($pdf, $xmlObj);
	}
	
	public static function calHeightOffset(ExtTcpdf $pdf) {
		$style = $pdf->getStyleSet();
		$border = $pdf->convertBorderStyle($style);
		
		$borderTop = isset($border['T'])? $border['T']['width'] : 0;
		$borderBottom = isset($border['B'])? $border['B']['width'] : 0;
		
		$paddingTop = $pdf->getStyle('padding-top');
		$paddingBottom = $pdf->getStyle('padding-bottom');
		
		return $pdf->getStyle('margin-top') + $pdf->getStyle('margin-bottom') + 
			$borderTop + $borderBottom + $paddingBottom + $paddingTop;
	}

	public static function calWidthOffset(ExtTcpdf $pdf) {
		$style = $pdf->getStyleSet();
		$border = $pdf->convertBorderStyle($style);
		
		$borderLeft = isset($border['L'])? $border['L']['width'] : 0;
		$borderRight = isset($border['R'])? $border['R']['width'] : 0;
		
		$paddingLeft = $pdf->getStyle('padding-left');
		$paddingRight = $pdf->getStyle('padding-right');
		
		return $pdf->getStyle('margin-left') + $pdf->getStyle('margin-right') + 
			$borderLeft + $borderRight + $paddingLeft + $paddingRight;
	}
}
?>