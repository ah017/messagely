<?php namespace Wibleh\Messagely;

/**
/**
 * A Laravel bundle to help storage and retrieval of messages
 * for output to users.  Based on the Laravel 3 package by 
 * JonoB (http://github.com/JonoB/flare-messagely)
 */
 
class Messagely
{
	/**
	 * The message container
	 */
	private static $messages = array();

	/**
	 * Flash message container
	 */
	private static $new_flash_messages = array();

	/**
	 * The name of the flash \Session container
	 */
	private static $flash_container = 'messagely';

	/**
	 * Store if the old flash messages have been retrieved
	 */
	private static $init = false;

	/**
	 * Add a message
	 *
	 * @param 	string $group
	 * @param 	mixed $message If this is an array, then each item will be added to the specified group
	 * @param 	bool $flash set to true if this is a flash message
	 * @access 	public
	 * @return 	void
	 */
	public static function add($group, $message = '', $flash = false)
	{
		// Skip empty messages
		if (empty($message))
		{
			return;
		}

		if (is_array($message))
		{
			foreach ($message as $msg)
			{
				static::add($group, $msg, $flash);
			}
		}
		else
		{
			if ($flash)
			{
				static::$new_flash_messages[$group][] = $message;
				\Session::flash(static::$flash_container, static::$new_flash_messages);
			}
			else
			{
				static::$messages[$group][] = $message;
			}
		}
	}

	/**
	 * Add messages to flash
	 *
	 * @param 	string $group
	 * @param 	mixed $message If this is an array, then each item will be added to the specified group
	 * @access 	public
	 * @return 	void
	 */
	public static function flash($group, $message = '')
	{
		static::add($group, $message, true);
	}

	/**
	 * Fetches all messages for the specified group. If no group was found this
	 * method will return FALSE instead of an array.
	 *
	 * @access	public
	 * @param	string $group The name of the group you want to retrieve.
	 * @return	array
	 */
	public static function get($group = '')
	{
		if ( ! static::$init)
		{
			// Append all the old flash messages to the messages array
			$flash = \Session::get(static::$flash_container);
			if ($flash)
			{
				foreach ($flash as $flash_group => $msgs)
				{
					foreach($msgs as $msg)
					{
						static::$messages[$flash_group][] = $msg;
					}
				}
			}
			static::$init = true;
		}

		// If a group is specified we'll return it
		if ( ! empty($group))
		{
			return (isset(static::$messages[$group])) ? static::$messages[$group] : array();
		}

		// Otherwise we'll return all items
		else
		{
			return static::$messages;
		}
	}
	
	/**
	 * Return a string of HTML to be displayed.
	 */
	public static function display($group = '')
	{
		if ($group != '') {
			$groups[$group] = static::get($group);
		}
		else {
			$groups = static::get();
		}
		
		$groups = array_filter($groups, function($v) {
			return (is_array($v)  &&  count($v));
		});
		
		if (!count($groups)) return "";
		
		ob_start();
		
		?><div class="messagely"><?php
		
		foreach ($groups as $group => $messages)
		{
			?><div class="alert alert-<?php echo e($group); ?>"><?php
			
			foreach ($messages as $message)
			{
				?><p><?php echo e($message); ?></p><?php
			}
			
			?></div><?php
		}
		
		?></div><?php
		
		return ob_get_clean();
	}
}