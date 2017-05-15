<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Класс для создания наблюдателей.
 */
abstract class Core_Observer
{
	/**
	 * Соответствие событий и обработчиков.
	 *
	 * В качестве ключа задается событие, а в качестве значения обрабатываемый это событие
	 * метод класса. Возможно также задавать только событие в качестве значения,
	 * в этом случае будет вызван одноименный метод объекта.
	 *
	 * Например:
	 *
	 * protected $_aEvents = array(
	 *     'shop_item.onBeforeGetXml' => 'onBeforeGetXml',
	 * );
	 *
	 * или 
	 * 
	 * protected $_aEvents = array(
	 *     'shop_item.onBeforeGetXml'
	 * );
	 *
	 * @var array
	 */
	protected $_aEvents = array();

	/**
	 * Экземпляр объекта.
	 *
	 * @var mixed
	 */
	static protected $_instance = NULL;

	/**
	 * Связывает обработчики с событиями.
	 *
	 * @return void
	 */
	static public function attach()
	{
		foreach (static::_getEvents() as $event => $method)
		{
			Core_Event::attach($event, array(__CLASS__, $method));
		}
	}

	/**
	 * Отвязывает обработчики от событий.
	 *
	 * @return void
	 */
	static public function detach()
	{
		foreach (static::_getEvents() as $event => $method)
		{
			Core_Event::detach($event, array(__CLASS__, $method));
		}
	}

	/**
	 * Обрабатывает запуск статического метода.
	 *
	 * @param  string  $name
	 * @param  array  $aArguments
	 * @return mixed
	 */
	static public function __callStatic($name, $aArguments)
	{
		return call_user_func_array(array(static::_instance(), $name), $aArguments);
	}

	/**
	 * Возвраащет экземляр объекта.
	 *
	 * @return mixed
	 */
	static protected function _instance()
	{
		if (!static::$_instance)
		{
			static::$_instance = new static;
		}

		return static::$_instance;
	}

	/**
	 * Возваращает набор событий в едином стиле.
	 *
	 * @return array
	 */
	protected function _getEvents()
	{
		$aEvents = array();

		foreach (static::_instance()->aEvents as $key => $value)
		{
			// Задано событие и обработчик (например, 'shop_item.onBeforeGetXml' = > 'onBeforeGetXml')
			if (!is_numeric($key))
			{
				$event = $key;
				$metod = $value;
			}
			// Задано только событие (например, 'shop_item.onBeforeGetXml')
			else
			{
				$event = $value;
				$method = substr($value, strpos($value, '.') + 1);
			}

			$aEvents[$event] = $method;
		}

		return $aEvents;
	}
}