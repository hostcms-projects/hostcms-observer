# hostcms-observer

Для добавления нестандартного поведения для контроллеров и моделей в HostCMS используется паттерн Наблюдателя, некоторые примеры по его использованию есть в [официальной документации](https://www.hostcms.ru/documentation/modules/core/events/). В данном репозитарии содержится класс, немного облегчающий написание наблюдателей.

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

// Прикрепляем наблюдатель
Some_Observer::attach();
```

## Пример

[attachhashedid.php](tests/shop/item/observer/attachhashedid.php)

## Тесты

Для запуска тестов:

```$ vendor/bin/phpunit --test-suffix="test.php" tests/```

## Лицензия
MIT