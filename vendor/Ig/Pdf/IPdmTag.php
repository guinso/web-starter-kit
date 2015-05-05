<?php 
namespace Ig\Pdf;

/**
 * IG Pdf document markup parser interface class
 * @author chingchetsiang
 *
 */
interface IPdmTag {
	public static function run(ExtTcpdf $pdf, $xmlObj);
	
	public static function simulate(ExtTcpdf $pdf, $xmlObj);
}
?>