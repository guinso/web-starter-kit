<?php 
namespace Ig;

class Date {
	/**
	 * Get current Date
	 */
	public static function getDate() {
		return date('Y-m-d');
	}
	
	/**
	 * Get current Date and Time
	 */
	public static function getDatetime() {
		return date('Y-m-d H:i:s');
	}
	
	/**
	 * Get date based on week span, which will always be on Monday
	 * @param integer $weekSpan
	 */
	public static function getDateFromWeekSpan($weekSpan) {
		$w = $weekSpan % 52;
		if($w == 0)
			$w = 52;
	
		$y = floor($weekSpan / 52);
	
		$date = new \DateTime();
		$date->setISODate($y, $w);
		return $date->format('Y-m-d');
	}
	
	/**
	 * Calculate weekspan based on ISO-8601
	 * @param String $date
	 */
	public static function getWeekSpan($date) {
		$timestamp = strtotime($date);
	
		$w = date('W', $timestamp);
		$y = date('o', $timestamp);
	
		return $w + $y * 52;
	}
	
	/**
	 * backward date to Monday
	 * @param String $date
	 */
	public static function offsetToMonday($date, $dateformat = 'Y-m-d') {
		$datetime = new \DateTime($date);
		$diff = date('N', strtotime($date)) - 1;
	
		$dateInterval = new \DateInterval("P".$diff."D");
		$datetime->sub($dateInterval);
	
		return $datetime->format($dateformat);
	}
	
	public static function offsetDate($date, $diff, $dateformat = 'Y-m-d') {
		$datetime = new \DateTime($date);
	
		if($diff < 0) {
			$diff *= -1;
			$dateInterval = new \DateInterval("P".$diff."D");
			$datetime->sub($dateInterval);
		} else {
			$dateInterval = new \DateInterval("P".$diff."D");
			$datetime->add($dateInterval);
		}
	
		return $datetime->format($dateformat);
	}
	
	public static function getLastDayOfMonth($date) {
		return date('Y-m-t', strtotime($date));
	}
	
	public static function getDateRange($month, $year) {
		$fromDate = date('Y-m-d', strtotime($year . '-' . $month . '-1'));
		$toDate = self::getLastDayOfMonth($year . '-' . $month . '-1');
		$carryFwdDate = self::offsetDate($fromDate, -1);
	
		return array(
				'fromDate' => $fromDate,
				'toDate' => $toDate,
				'carryFwdDate' => $carryFwdDate
		);
	}
}
?>