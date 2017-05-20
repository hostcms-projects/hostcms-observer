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
	 * public $aEvents = array(
	 *     'shop_item.onBeforeGetXml' => 'onBeforeGetXml',
	 * );
	 *
	 * или 
	 * 
	 * public $aEvents = array(
	 *     'shop_item.onBeforeGetXml'
	 * );
	 *
	 * @var array
	 */
	public $aEvents = array();

	/**
	 * Выводить сообщение при срабатывании события.
	 *
	 * @var boolean
	 */
	public $verbose = FALSE;

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
		foreach (static::instance()->_getEvents() as $event => $method)
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
		foreach (static::instance()->_getEvents() as $event => $method)
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
		// Выводим событие и название метода
		if (static::instance()->verbose)
		{
			print static::instance()->_getEventByMethod($name) . " --> "
				. get_class(static::instance()) . '.' . $name;
		}

		return call_user_func_array(array(static::instance(), $name), $aArguments);
	}

	/**
	 * Возвраащет экземляр объекта.
	 *
	 * @return mixed
	 */
	static public function instance()
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

		foreach ($this->aEvents as $key => $value)
		{
			// Задано событие и обработчик (например, 'shop_item.onBeforeGetXml' = > 'onBeforeGetXml')
			if (!is_numeric($key))
			{
				$event = $key;
				$method = $value;
			}
			// Задано только событие (например, 'shop_item.onBeforeGetXml'),
			// в таком случае обработчиком будет одноименный метод события
			else
			{
				$event = $value;
				$method = substr($value, strpos($value, '.') + 1);
			}

			$aEvents[$event] = $method;
		}

		return $aEvents;
	}

	/**
	 * Возвращает событие по методу.
	 *
	 * @param  string  $method
	 * @return string
	 */
	protected function _getEventByMethod($method)
	{
		return strval(array_search($method, $this->_getEvents()));
	}
}