<?php 
namespace Ig\Pdf;

/**
 * Pdf report generator class
 * PDF render engine based on TCPDF
 * @author chingchetsiang
 *
 */
class PdfReport {

	private static function _getDefaultDocParam() {
		return array(
			'orientation' => 'P',
			'unit' => 'mm',
			'format' => 'A4',
			'encoding' => 'UTF-8',
			'author' => '-',
			'title' => '-',
			'subject' => '-',
			'keyword' => 'pdf, pdm',

			'margin-top' => 20,
			'margin-bottom' => 20,
			'margin-left' => 15,
			'margin-right' => 15,
			'margin-header' => 30,
			'margin-footer' => 20,
				
			'autoPageBerak' => true
		);	
	}
	
	/**
	 * Create TCPDF generator instance
	 * In future will support custom parameter
	 * @return ExtTcpdf
	 */
	private static function _preparePdf($param) {
		//get company porfile
		$comProfile = \ComProfile::get();

		//prepare pdf
		$pdf = new ExtTcpdf(
				$param['orientation'], 	//potrait
				$param['unit'], 		//mm
				$param['format'],
				true, 					//support unicode
				$param['encoding'],
				false);					//enable disk cache

		/**************** set document metadata ****************/
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($param['author']);
		$pdf->SetTitle($param['title']);
		$pdf->SetSubject($param['subject']);
		$pdf->SetKeywords($param['keyword']);
		/////////////////////////////////////////////////////////



		/********************* Set Header **********************/
		// customer header
		//x $imgPath = ROOT_DIR . DS . 'img' . DS . 'com-logo.png';
/*
		$pdf->setHeaderHtml($imgPath, '<div style="text-align:center;"><span>Company A</span><br/><span>100k-A</span></div>');
		// set header
		$pdf->SetHeaderData(
				PDF_HEADER_LOGO, //icon source path
				30, //icon width (based on unit, default is mm)
				$comProfile['comName'], //header title (company name)
				$comProfile['addr'], //header test (company address)
				array(0,0,0), //text color
				array(0,0,0)); //line break color
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
*/
		//uncomment to disable print header
		//x $pdf->setPrintHeader(false);
		/////////////////////////////////////////////////////////



		/********************* Set Footer *********************/
		// set footer
		$pdf->setFooterData(
		array(0,0,0),  //text color
		array(0,0,0)); //line break color
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		//uncomment to disable print footer
		//x $pdf->setPrintFooter(false);
		/////////////////////////////////////////////////////////



		/********************** Set Margin ********************/
		$pdf->SetMargins($param['margin-left'], $param['margin-top'], $param['margin-right']);
		$pdf->SetHeaderMargin(0); //$param['margin-header']);
		$pdf->SetFooterMargin($param['margin-footer']);
		/////////////////////////////////////////////////////////



		/****************** set auto page breaks ***************/
		$pdf->SetAutoPageBreak(TRUE, $param['margin-bottom']);
		/////////////////////////////////////////////////////////



		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		return $pdf;
	}

	/**
	 * Generate PDF based on template file and array data
	 * @param array $data			mix associate array
	 * @param string $template		template file path
	 * @param string $outputMode	TCPDF output mode: I, O
	 */
	public static function generatePdf($pdm, $outputMode = 'I') {
		//generate IG Pdf Document Markup
		//x $pdm = self::_generatePdm($data, $template);

		//load script into simpleXML object
		$xml = simplexml_load_string($pdm);
		
		//prepare ExtTcpdf instance
		
		//TODO generate PDF by travesing XML
		//1.  prepare pdf by refering XML->docroot attributes
		$docParam = self::_getDefaultDocParam();
		foreach($xml->attributes() as $key => $value) {
			//overwrite setting
			$docParam[$key] = $value;
		}
		
		//var_dump($xml['unit']);
		$pdf = self::_preparePdf($docParam);
		
		//2.  prepare header by referring XML->header tag
		$header = $xml->header;
		if(!empty($header))
			$pdf->setPdmHeader($header);
		
		//3.  prepare footer by referring XML->footer tag
		
		$pdf->setStyle('y', $pdf->GetY());
		//4.  loop each <page> tags
		$pages = $xml->page;
		foreach($pages as $page) {
			PdmTagHandler::handleTag($pdf, 'page', $page);
		}
		
		//5.  output buffer
		$pdf->Output('asd.pdf', $outputMode);
	}

	/**
	 * Generate PDM file (IG Pdf Document Markup)
	 * @param array $data		mix associate array
	 * @param string $template	template file path
	 */
	public static function generatePdm($data, $template) {
		//create the Tpl object
		$tpl = new \Rain\Tpl();

		//set data value
		$tpl->assign($data);

		//generate output
		return $tpl->draw($template, true);
	}
}
?>