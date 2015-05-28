<?php 
namespace Hx\Logger;

class FileLogger implements \Hx\Logger\LoggerInterface {
	
	private $directory;
	
	public function __construct($logDirectory)
	{
		if (!is_dir($logDirectory))
			Throw new \Hx\Exception\LoggerException("Directory $logDirectory not found.");
		
		$this->directory = $logDirectory;
	}
	
	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function emergency($message, array $context = array()) 
	{
		$this->log(LogLevel::EMERGENCY, $message, $context);
	}
	
	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function alert($message, array $context = array())
	{
		$this->log(LogLevel::ALERT, $message, $context);
	}
	
	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function critical($message, array $context = array())
	{
		$this->log(LogLevel::CRITICAL, $message, $context);
	}
	
	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function error($message, array $context = array())
	{
		$this->log(LogLevel::ERROR, $message, $context);
	}
	
	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function warning($message, array $context = array())
	{
		$this->log(LogLevel::WARNING, $message, $context);
	}
	
	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function notice($message, array $context = array())
	{
		$this->log(LogLevel::NOTICE, $message, $context);
	}
	
	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function info($message, array $context = array())
	{
		$this->log(LogLevel::INFO, $message, $context);
	}
	
	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	*/
	public function debug($message, array $context = array())
	{
		$this->log(LogLevel::DEBUG, $message, $context);
	}
	
	public function log($level, $message, array $context = array())
	{
		$routineTag = '';
		$filepath = '';
		$line = '';
		
		if(array_key_exists('filepath', $context))
			$filepath = $context['filepath'];
		
		if(array_key_exists('linecode', $context))
			$line = $context['linecode'];
		
		$time = date('H:i:s');
		$date = date('Y-m-d');
		
		if(!empty($filepath) && !empty($line))
			$routineTag = '[' . $filepath . ':' . $line . ']';
		
		if (file_exists($this->directory . DIRECTORY_SEPARATOR . $date . '.log'))
		{
			file_put_contents(
				$this->directory . DIRECTORY_SEPARATOR . $date . '.log', 
				'[' . $time . ':' . $level . ']' . $routineTag . ' ' . $message . "\n", 
				FILE_APPEND
			);
		}
		else
		{
			file_put_contents(
				$this->directory . DIRECTORY_SEPARATOR . $date . '.log',
				'[' . $time . ':' . $level . ']' . $routineTag . ' ' . $message . "\n"
			);
		}
	}
}
?>