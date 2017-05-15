# hostcms-observer

Для добавления нестандартного поведения для контроллеров и моделей HostCMS используется паттерн Наблюдатель, некоторые примеры по его использованию есть в [официальной документации](https://www.hostcms.ru/documentation/modules/core/events/). В данном репозитарии содержится класс, который немного облегчает написание наблюдателей.

Его основные возможности:
* упрощенное связывание событий;
* использование объектов вместо классов.

## Использование

```
/**
 * Расширяем класс наблюдателя.
 */
class Some_Observer extends Core_Observer
{
	/**
	 * Задаем массив событий.
	 */
	public $aEvents = array(
		'shop_item.onBeforeGetXml'
	);

	/**
	 * Задаем обработчик события.
	 */
	public function onBeforeGetXml($oShopItem, $aArguments)
	{
		
	}
}
```

## Пример

[examples/observer.php](examples/observer.php)

## Лицензия
MIT