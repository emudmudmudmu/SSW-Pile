<?php namespace Laravel;

use Laravel\Session;

class Log {

	/**
	 * Log an exception to the log file.
	 *
	 * @param  Exception  $e
	 * @return void
	 */
	public static function exception($e)
	{
		static::write('error', static::exception_line($e));
	}

	/**
	 * Format a log friendly message from the given exception.
	 *
	 * @param  Exception  $e
	 * @return string
	 */
	protected static function exception_line($e)
	{
		return $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	}

	/**
	 * Write a message to the log file.
	 *
	 * <code>
	 *		// Write an "error" message to the log file
	 *		Log::write('error', 'Something went horribly wrong!');
	 *
	 *		// Write an "error" message using the class' magic method
	 *		Log::error('Something went horribly wrong!');
	 *
	 *		// Log an arrays data
	 *		Log::write('info', array('name' => 'Sawny', 'passwd' => '1234', array(1337, 21, 0)), true);
	 *      //Result: Array ( [name] => Sawny [passwd] => 1234 [0] => Array ( [0] => 1337 [1] => 21 [2] => 0 ) )
	 *      //If we had omit the third parameter the result had been: Array
	 * </code>
	 *
	 * @param  string  $type
	 * @param  string  $message
	 * @return void
	 */
	public static function write($type, $message, $pretty_print = false)
	{
		$message = ($pretty_print) ? print_r($message, true) : $message;

		// If there is a listener for the log event, we'll delegate the logging
		// to the event and not write to the log files. This allows for quick
		// swapping of log implementations for debugging.
		if (Event::listeners('laravel.log'))
		{
			Event::fire('laravel.log', array($type, $message));
		}

		$message = static::format($type, $message);

		File::append(path('storage').'logs/'.date('Y-m-d').'.log', $message);
	}

	/**
	 * Format a log message for logging.
	 *
	 * @param  string  $type
	 * @param  string  $message
	 * @return string
	 */
	protected static function format($type, $message)
	{
		$timestamp = microtime();
		$timestampstr = explode(' ', $timestamp);
		$timestamp = date('Y-m-d H:i:s', $timestampstr[1]) . substr($timestampstr[0], 1);
		
		$user = 'no session';
		if( Session::started() ) {
			$user_ = Session::get('6'); // KEY_USER
			if ($user_ && isset($user_->login_id)) {
				$user = $user_->login_id;
			}
		}
		$user = str_pad($user, 12, ' ', STR_PAD_LEFT);
		
		$pid = getmypid();
		$pid = str_pad($pid, 6, ' ', STR_PAD_LEFT);
		
		$ip  = str_pad($_SERVER['REMOTE_ADDR'], 15, ' ', STR_PAD_LEFT);
		
		$type = str_pad(strtoupper($type), 6, ' ', STR_PAD_RIGHT);
		
		return "{$timestamp}|{$type}|{$pid}|{$ip}|{$user}| {$message}".PHP_EOL;
	}

	/**
	 * Dynamically write a log message.
	 *
	 * <code>
	 *		// Write an "error" message to the log file
	 *		Log::error('This is an error!');
	 *
	 *		// Write a "warning" message to the log file
	 *		Log::warning('This is a warning!');
	 *
	 *		// Log an arrays data
	 *		Log::info(array('name' => 'Sawny', 'passwd' => '1234', array(1337, 21, 0)), true);
	 *      //Result: Array ( [name] => Sawny [passwd] => 1234 [0] => Array ( [0] => 1337 [1] => 21 [2] => 0 ) )
	 *      //If we had omit the second parameter the result had been: Array
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		$parameters[1] = (empty($parameters[1])) ? false : $parameters[1];

		static::write($method, $parameters[0], $parameters[1]);
	}

}