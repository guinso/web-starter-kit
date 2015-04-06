<?php 
namespace Ig;

class Excel {
	public static function loadExcel($excel_name) {
		
		$objPHPExcel = \PHPExcel_IOFactory::load($excel_name);
		
		return $objPHPExcel;
	}
	
	public static function exportXLSX(\PHPExcel $phpExcelObj, $filename){
		$mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	
		$writer = \PHPExcel_IOFactory::createWriter(
				$phpExcelObj, 'Excel2007');
	
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
	
		$writer->save('php://output');
		exit;
	}
	
	public static function saveXLSX(\PHPExcel $phpExcelObj, $filepath) {
		$writer = \PHPExcel_IOFactory::createWriter(
			$phpExcelObj, 'Excel2007');
		
		$result = false;
		try {
			$writer->save($filepath);
			$result = true;
		} catch(Exception $ex) {
			$result = false;
		}
		
		return $result;
	}

	public static function getDate($phpExcelworkSheet, $col, $row, $dateFormat = 'Y-m-d') {
		$cell = $phpExcelworkSheet->getCell($col.$row);
		
		$format = $phpExcelworkSheet->getStyle($col.$row)->getNumberFormat()->getFormatCode();
		$value = $cell->getValue();

		if(\PHPExcel_Shared_Date::isDateTimeFormatCode($format) && is_numeric($value)) {
			$timestamp = intVal($cell->getValue());
			$invDate = date($dateFormat, \PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()));
		}

		return $invDate;
	}
}
?>